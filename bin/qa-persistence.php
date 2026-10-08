<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
use Eventify\Infrastructure\Database;
use Eventify\Support\Config;
try {
    if (Config::get('APP_ENV') !== 'local' || Config::get('DB_DATABASE') !== 'eventify_qa' || $argc !== 2 || !in_array($argv[1], ['before','after'], true)) { throw new RuntimeException('Solo se permite la base QA.'); }
    $pdo=Database::connect();
    if ($pdo->query('SELECT DATABASE()')->fetchColumn() !== 'eventify_qa') { throw new RuntimeException('Base inesperada.'); }
    $marker=PROJECT_ROOT.'/storage/qa-persistence-marker.bin';
    $baseline=PROJECT_ROOT.'/storage/qa-persistence-snapshot.json';
    if ($argv[1]==='before' && !is_file($marker)) {
        if (file_put_contents($marker,random_bytes(32)) !== 32 || !chmod($marker,0600)) { throw new RuntimeException('No se pudo guardar marcador.'); }
    }
    $tables=['roles'=>'id','users'=>'id','categories'=>'id','events'=>'id','reservations'=>'id','tickets'=>'id','schema_migrations'=>'version','demo_seed_runs'=>'seed_key','auth_limits'=>'bucket'];
    $snapshot=['tables'=>[], 'files'=>[]];
    $pdo->beginTransaction();
    try {
        foreach($tables as $table=>$key) {
            $rows=$pdo->query('SELECT * FROM '.$table.' ORDER BY '.$key)->fetchAll();
            $snapshot['tables'][$table]=['count'=>count($rows),'sha256'=>hash('sha256',json_encode($rows,JSON_THROW_ON_ERROR))];
        }
    } finally { $pdo->rollBack(); }
    foreach(['marker'=>$marker,'credentials'=>PROJECT_ROOT.'/storage/demo-credentials.json'] as $name=>$file) {
        if (!is_file($file)) { throw new RuntimeException('Falta archivo persistente.'); }
        $digest=hash_file('sha256',$file); if($digest===false) { throw new RuntimeException('Archivo no legible.'); }
        $snapshot['files'][$name]=$digest;
    }
    $images=glob(PROJECT_ROOT.'/public/assets/images/*.svg') ?: [];
    if(count($images)<3) { throw new RuntimeException('Galería incompleta.'); }
    foreach($images as $file) { $digest=hash_file('sha256',$file); if($digest===false) { throw new RuntimeException('Imagen no legible.'); } $snapshot['files']['image:'.basename($file)]=$digest; }
    if ($argv[1]==='before') {
        if (file_put_contents($baseline,json_encode($snapshot,JSON_THROW_ON_ERROR)) === false || !chmod($baseline,0600)) { throw new RuntimeException('No se pudo guardar snapshot.'); }
        echo "Snapshot QA guardado; no se imprimen filas ni credenciales.\n";
    } else {
        $expected=json_decode((string)file_get_contents($baseline),true,512,JSON_THROW_ON_ERROR);
        if ($snapshot !== $expected) { throw new RuntimeException('Persistencia distinta al snapshot.'); }
        echo "Persistencia QA: nueve tablas, marcador, credenciales e imágenes conservados tras recreación.\n";
    }
} catch(Throwable $error) {
    fwrite(STDERR,'Verificación de persistencia QA falló ('.get_class($error)."). No se muestran datos ni credenciales.\n"); exit(1);
}
