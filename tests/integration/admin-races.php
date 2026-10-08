<?php
declare(strict_types=1);
// Incluido exclusivamente en la suite aislada; cada carrera utiliza un evento nuevo.
if (Eventify\Support\Config::get('APP_ENV') !== 'test' || Eventify\Support\Config::get('DB_DATABASE') !== 'eventify_test') { throw new RuntimeException('Carreras requieren base aislada.'); }
$raceAdmin = new Eventify\Domain\AdminService($pdo);
$raceCategory = $raceAdmin->category(null, ['name'=>'Carreras administrativas', 'slug'=>'admin-races', 'is_active'=>'1']);
$raceUser = (int)$pdo->query("SELECT id FROM users WHERE email='otro@eventify.test'")->fetchColumn();
$historicalUser = (int)$pdo->query("SELECT id FROM users WHERE email='usuario@eventify.test'")->fetchColumn();
$waitFile = static function(string $file): void {
    $deadline=microtime(true)+8;
    while(!is_file($file)) { if(microtime(true)>$deadline) { throw new RuntimeException('Barrera de carrera administrativa vencida.'); } usleep(10000); }
};
foreach ([['capacity','reserve'],['reserve','capacity'],['cancel','reserve'],['reserve','cancel']] as $case => $operations) {
    $label='Carrera administrativa ' . implode(' → ', $operations);
    $isCapacity=in_array('capacity',$operations,true);
    $input=['name'=>'Carrera administrativa ' . $case,'slug'=>'admin-race-' . $case,'description'=>'Fixture aislada','category_id'=>(string)$raceCategory,'image_path'=>'/assets/icon.svg','starts_at'=>'2090-06-20T12:00:45','location'=>'Lugar ficticio','capacity'=>$isCapacity?'2':'3','price'=>'100.25','status'=>'PUBLICADO','latitude'=>'','longitude'=>''];
    $raceEvent=$raceAdmin->event(null,$input);
    $history=null;
    if(!$isCapacity) { $history=(new Eventify\Domain\ReservationService($pdo))->create($historicalUser,$raceEvent,1); }
    $directory=sys_get_temp_dir().'/eventify-admin-race-'.bin2hex(random_bytes(8));
    if(!mkdir($directory,0700)) { throw new RuntimeException('Directorio de carrera no disponible.'); }
    file_put_contents($directory.'/job.json',json_encode(['operations'=>$operations,'user_id'=>$raceUser,'event_id'=>$raceEvent,'quantity'=>$isCapacity?2:1,'input'=>$input],JSON_THROW_ON_ERROR));
    $processes=[]; $pipesList=[];
    try {
        foreach([0,1] as $index) {
            $process=proc_open([PHP_BINARY,__DIR__.'/admin-race-worker.php',$directory.'/job.json',(string)$index],[['file','/dev/null','r'],['pipe','w'],['pipe','w']],$pipes,PROJECT_ROOT);
            if(!is_resource($process)) { throw new RuntimeException('No se pudo iniciar proceso de carrera.'); }
            $processes[]=$process; $pipesList[]=$pipes;
            if($index===0) { $waitFile($directory.'/locked'); } else { $waitFile($directory.'/attempt-1'); }
        }
        // El primer servicio mantiene su lock mientras el segundo intenta adquirirlo.
        usleep(150000);
        $assert(proc_get_status($processes[0])['running'] && proc_get_status($processes[1])['running'], $label . ': procesos solapados antes de liberar lock');
        touch($directory.'/release');
        $results=[];
        foreach($pipesList as $index=>$pipes) {
            stream_set_timeout($pipes[1],15); stream_set_timeout($pipes[2],15);
            $results[]=stream_get_contents($pipes[1]); $error=stream_get_contents($pipes[2]);
            fclose($pipes[1]); fclose($pipes[2]); $pipesList[$index]=[];
            $exit=proc_close($processes[$index]); $processes[$index]=null;
            if($exit!==0 || $error!=='') { throw new RuntimeException('Proceso de carrera administrativa falló.'); }
        }
        $expected=$isCapacity?['OK','CONFLICT']:($operations[0]==='cancel'?['OK','CONFLICT']:['OK','OK']);
        $assert($results===$expected, $label . ': resultado de ambas operaciones');
        $query=$pdo->prepare("SELECT e.capacity,e.status,COALESCE(SUM(CASE WHEN r.status='CONFIRMADA' THEN r.quantity ELSE 0 END),0) AS occupied,COUNT(r.id) AS reservations FROM events e LEFT JOIN reservations r ON r.event_id=e.id WHERE e.id=? GROUP BY e.id,e.capacity,e.status");
        $query->execute([$raceEvent]); $end=$query->fetch();
        $expectedQuantity=$isCapacity?($operations[0]==='capacity'?0:2):($operations[0]==='cancel'?1:2);
        $expectedCapacity=$isCapacity?($operations[0]==='capacity'?1:2):3;
        $assert((int)$end['occupied']===$expectedQuantity && (int)$end['capacity']===$expectedCapacity && (int)$end['occupied']<=(int)$end['capacity'] && $end['status']===($isCapacity?'PUBLICADO':'CANCELADO'), $label . ': aforo y estado coherentes');
        $query=$pdo->prepare('SELECT COUNT(*) FROM tickets t JOIN reservations r ON r.id=t.reservation_id WHERE r.event_id=?'); $query->execute([$raceEvent]);
        $assert((int)$query->fetchColumn()===(int)$end['reservations'], $label . ': un ticket por reserva conservada');
        if($history!==null) {
            $query=$pdo->prepare('SELECT status,unit_price,total FROM reservations WHERE id=?'); $query->execute([$history['id']]); $saved=$query->fetch();
            $assert($saved['status']==='CONFIRMADA' && $saved['unit_price']==='100.25' && $saved['total']==='100.25', $label . ': historial previo intacto');
        }
    } finally {
        foreach($pipesList as $pipes) { foreach($pipes as $pipe) { if(is_resource($pipe)) fclose($pipe); } }
        foreach($processes as $process) { if(is_resource($process)) { proc_terminate($process); proc_close($process); } }
        // Solo archivos de barrera propios; nunca eliminar bases o almacenamiento de aplicación.
        foreach(glob($directory.'/*') ?: [] as $file) { unlink($file); } rmdir($directory);
    }
}
