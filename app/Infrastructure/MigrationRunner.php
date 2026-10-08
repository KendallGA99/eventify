<?php

declare(strict_types=1);

namespace Eventify\Infrastructure;

use PDO;
use RuntimeException;
use Throwable;

final class MigrationRunner
{
    public function __construct(private PDO $pdo, private string $directory)
    {
    }

    /** @return list<string> Versiones aplicadas en esta ejecución. */
    public function run(): array
    {
        if ($this->pdo->inTransaction()) {
            throw new RuntimeException('Las migraciones deben ejecutarse fuera de una transacción de negocio.');
        }
        $name = 'eventify:migrate:' . substr(hash('sha256', (string) $this->pdo->query('SELECT DATABASE()')->fetchColumn()), 0, 32);
        $lock = $this->pdo->prepare('SELECT GET_LOCK(?, 10)');
        $lock->execute([$name]);
        if ((int) $lock->fetchColumn() !== 1) {
            throw new RuntimeException('Otra migración está en ejecución. Vuelva a intentarlo después.');
        }
        try {
            return $this->apply();
        } finally {
            $release = $this->pdo->prepare('SELECT RELEASE_LOCK(?)');
            $release->execute([$name]);
        }
    }

    /** @return list<string> */
    private function apply(): array
    {
        $this->pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS schema_migrations (
    version VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin PRIMARY KEY,
    checksum CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    status ENUM('EN_PROCESO', 'APLICADA') NOT NULL,
    statements_applied INT UNSIGNED NOT NULL DEFAULT 0,
    started_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME NULL
) ENGINE=InnoDB
SQL);
        $files = glob($this->directory . '/*.php');
        if ($files === false || $files === []) {
            throw new RuntimeException('No se encontraron migraciones. Revise el paquete de instalación.');
        }
        sort($files, SORT_STRING);
        $versions = array_map(static fn (string $file): string => basename($file, '.php'), $files);
        foreach ($this->pdo->query('SELECT version, status FROM schema_migrations')->fetchAll() as $record) {
            if (!in_array($record['version'], $versions, true) || $record['status'] !== 'APLICADA') {
                throw new RuntimeException('El historial contiene una migración incompleta o ausente del paquete. Revise el respaldo y los archivos antes de continuar.');
            }
        }
        $applied = [];
        foreach ($files as $file) {
            $version = basename($file, '.php');
            if (!preg_match('/\A[0-9]{3}_[a-z0-9_]+\z/', $version)) {
                throw new RuntimeException('Nombre de migración no válido.');
            }
            $checksum = hash_file('sha256', $file);
            $query = $this->pdo->prepare('SELECT checksum, status FROM schema_migrations WHERE version = ?');
            $query->execute([$version]);
            $existing = $query->fetch();
            if ($existing !== false) {
                if ($existing['status'] !== 'APLICADA') {
                    throw new RuntimeException('Hay una migración incompleta: ' . $version . '. Revisar progreso y respaldar; no reinicializar datos.');
                }
                if (!hash_equals($existing['checksum'], (string) $checksum)) {
                    throw new RuntimeException('Una migración aplicada cambió: ' . $version . '. Crear una nueva migración; no editar historial.');
                }
                continue;
            }
            $statements = require $file;
            if (!is_array($statements) || $statements === [] || count(array_filter($statements, 'is_string')) !== count($statements)) {
                throw new RuntimeException('Formato de migración no válido: ' . $version);
            }
            $insert = $this->pdo->prepare('INSERT INTO schema_migrations (version, checksum, status) VALUES (?, ?, ?)');
            $insert->execute([$version, $checksum, 'EN_PROCESO']);
            try {
                foreach ($statements as $index => $sql) {
                    $this->pdo->exec($sql);
                    $progress = $this->pdo->prepare('UPDATE schema_migrations SET statements_applied = ? WHERE version = ?');
                    $progress->execute([$index + 1, $version]);
                }
                $complete = $this->pdo->prepare("UPDATE schema_migrations SET status = 'APLICADA', completed_at = UTC_TIMESTAMP() WHERE version = ?");
                $complete->execute([$version]);
            } catch (Throwable $exception) {
                throw new RuntimeException('Falló la migración ' . $version . '. Se conservó su progreso; revisar antes de continuar, sin borrar datos.', 0, $exception);
            }
            $applied[] = $version;
        }
        return $applied;
    }
}
