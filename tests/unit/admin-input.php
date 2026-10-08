<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/app/bootstrap.php';
use Eventify\Domain\AdminInput;
use Eventify\Domain\BusinessError;
$checks = 0;
$assert = static function (bool $ok) use (&$checks): void { if (!$ok) { fwrite(STDERR, "Falló validación administrativa.\n"); exit(1); } ++$checks; };
$base = ['name' => 'Evento de prueba', 'slug' => 'evento-prueba', 'description' => "Descripción\ncon varias líneas", 'category_id' => '1', 'image_path' => '/assets/images/cultura.svg', 'starts_at' => '2028-02-29T12:30', 'location' => 'San José', 'capacity' => '50', 'price' => '0', 'status' => 'BORRADOR', 'latitude' => '9.9281', 'longitude' => '-84.0907'];
$valid = AdminInput::event($base);
$assert($valid['price'] === '0.00' && $valid['starts_at'] === '2028-02-29 18:30:00');
$assert(AdminInput::event([...$base, 'price' => '123,4'])['price'] === '123.40');
$assert(AdminInput::event([...$base, 'starts_at' => '2028-02-29T12:30:45'])['starts_at'] === '2028-02-29 18:30:45');
$assert(AdminInput::event([...$base, 'latitude' => '', 'longitude' => ''])['latitude'] === null);
foreach ([['name', []], ['name', ''], ['name', str_repeat('á', 181)], ['name', "linea\notra"], ['slug', '../algo'], ['slug', 'Evento'], ['description', "x\0"], ['capacity', '0'], ['capacity', '4294967296'], ['capacity', '1.5'], ['category_id', '0'], ['price', '-1'], ['price', '1e4'], ['price', '0.123'], ['price', '10000000000'], ['price', []], ['starts_at', '2027-02-29T12:30'], ['starts_at', '2026-01-01T24:00'], ['starts_at', '2026-01-01T12:00:60'], ['image_path', 'https://example.test/image.png'], ['image_path', '/assets/../../config/x'], ['status', 'CANCELADO'], ['status', 'FINALIZADO'], ['latitude', '91'], ['latitude', []], ['longitude', '-181'], ['longitude', 'https://127.0.0.1'], ['longitude', '']] as [$field, $value]) {
    $rejected = false; try { AdminInput::event([...$base, $field => $value]); } catch (BusinessError) { $rejected = true; } $assert($rejected);
}
$assert(AdminInput::category(['name' => ' Música ', 'slug' => 'musica', 'is_active' => '0']) === ['name' => 'Música', 'slug' => 'musica', 'is_active' => 0]);
foreach ([['name' => ''], ['name' => 'Nombre', 'slug' => 'x/x'], ['name' => 'Nombre', 'slug' => 'x', 'is_active' => []], ['name' => 'Nombre', 'slug' => 'x', 'is_active' => '2']] as $input) {
    $rejected = false; try { AdminInput::category($input); } catch (BusinessError) { $rejected = true; } $assert($rejected);
}
echo "Administración: $checks comprobaciones correctas.\n";
