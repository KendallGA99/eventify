<?php
declare(strict_types=1);
// Se ejecuta tras migraciones y antes de sembrar: fixtures transaccionales, sin DELETE.
$read = new Eventify\Infrastructure\AdminReadRepository($pdo);
$empty = $read->dashboard();
$assert((int)$empty['total_events'] === 0 && $empty['occupation'] === '0,00' && $empty['top_event_id'] === null && $empty['top_category_id'] === null, 'Dashboard vacío');
$pdo->beginTransaction();
try {
    $pdo->exec("INSERT INTO categories(name,slug) VALUES ('Lectura A','read-a'),('Lectura B','read-b'),('Lectura C','read-c')");
    $cats = $pdo->query('SELECT id FROM categories ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
    $account = $pdo->prepare("INSERT INTO users(role_id,full_name,email,password_hash) SELECT id,'Persona 100%_!','read@eventify.test',? FROM roles WHERE code='USUARIO'");
    $account->execute([password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT)]);
    $person = (int)$pdo->lastInsertId();
    $insert = $pdo->prepare("INSERT INTO events(category_id,slug,name,description,image_path,starts_at,location,capacity,price,status) VALUES (?,?,?,'Fixture','/assets/icon.svg',DATE_ADD(UTC_TIMESTAMP(),INTERVAL ? DAY),'San José',?,999,'PUBLICADO')");
    $ids=[];
    foreach ([[0,'read-a','Evento 100%_!',30,100],[2,'read-b','Segundo',30,2],[1,'read-c','Tercero',30,100],[0,'read-past','Pasado',-30,100],[0,'read-cancel','Cancelado',30,100],[0,'read-draft','Borrador',30,100]] as [$cat,$slug,$name,$days,$capacity]) { $insert->execute([$cats[$cat],$slug,$name,$days,$capacity]); $ids[]=(int)$pdo->lastInsertId(); }
    $pdo->exec("UPDATE events SET status='CANCELADO' WHERE slug='read-cancel'");
    $pdo->exec("UPDATE events SET status='BORRADOR' WHERE slug='read-draft'");
    $reserve = $pdo->prepare('INSERT INTO reservations(user_id,event_id,code,quantity,unit_price,total,status,cancelled_at) VALUES (?,?,?,?,?,?,?,?)');
    $ticket = $pdo->prepare('INSERT INTO tickets(reservation_id,code) VALUES (?,?)');
    $add = static function(int $event,int $qty,string $price,string $total,string $state='CONFIRMADA') use($reserve,$ticket,$pdo,$person):int { $reserve->execute([$person,$event,bin2hex(random_bytes(16)),$qty,$price,$total,$state,$state==='CANCELADA'?'2026-01-01 00:00:00':null]); $id=(int)$pdo->lastInsertId(); $ticket->execute([$id,bin2hex(random_bytes(32))]); return $id; };
    $first=$add($ids[0],10,'100.00','1000.00'); $add($ids[1],1,'200.00','200.00'); $add($ids[2],10,'100.00','1000.00');
    $add($ids[0],90,'100.00','9000.00','CANCELADA');
    foreach (array_slice($ids,3) as $id) { $add($id,99,'500.00','49500.00'); }
    $d=$read->dashboard();
    $assert((int)$d['total_events']===6 && (int)$d['active_events']===3 && (int)$d['active_reservations']===3, 'Conjunto activo excluye borrador/cancelado/pasado');
    $assert((int)$d['reserved_entries']===21 && (int)$d['total_capacity']===202 && $d['occupation']==='10,40', 'Ocupación ponderada por capacidad, canceladas excluidas');
    $assert($d['potential_revenue']==='2200.00', 'Importes históricos exactos independientes del precio actual');
    $assert((int)$d['top_event_id']===$ids[0] && (int)$d['top_event_ties']===2 && (int)$d['top_category_id']===(int)$cats[0] && (int)$d['top_category_ties']===2, 'Rankings y empates deterministas');
    $f = Eventify\Domain\AdminReservationFilter::fromQuery(['event'=>'100%_!','user'=>'100%_!','state'=>'CONFIRMADA']);
    $r=$read->reservations($f); $assert(count($r['rows'])===1 && (int)$r['rows'][0]['id']===$first,'Filtros combinados con comodines literales');
    $assert(count($read->reservations(Eventify\Domain\AdminReservationFilter::fromQuery(['user'=>'read@eventify.test']))['rows'])===7,'Filtro por correo y consulta de todos los estados');
    $detail=$read->reservation($first); $assert($detail['unit_price']==='100.00' && $detail['total']==='1000.00' && strlen($detail['ticket_code'])===64,'Detalle histórico con ticket');
    $assert($read->reservation(999999999)===null,'Reserva inexistente');
    for($i=0;$i<52;++$i) { $add($ids[0],1,'100.00','100.00','CANCELADA'); }
    $a=$read->reservations(Eventify\Domain\AdminReservationFilter::fromQuery(['event'=>'100%_!','state'=>'CANCELADA']));
    $b=$read->reservations(Eventify\Domain\AdminReservationFilter::fromQuery(['event'=>'100%_!','state'=>'CANCELADA','page'=>'2']));
    $assert(count($a['rows'])===50 && $a['hasMore'] && count($b['rows'])===3 && !$b['hasMore'] && array_intersect(array_column($a['rows'],'id'),array_column($b['rows'],'id'))===[], 'Paginación sin duplicados');
} finally { $pdo->rollBack(); }
$assert((int)$read->dashboard()['total_events']===0, 'Fixtures revertidas antes de demostración');
