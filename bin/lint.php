<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$failed = false;
foreach (['app', 'public', 'config', 'bin', 'tests', 'database'] as $directory) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/' . $directory));
    foreach ($iterator as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        passthru(escapeshellarg(PHP_BINARY) . ' -n -l ' . escapeshellarg($file->getPathname()), $code);
        $failed = $failed || $code !== 0;
    }
}
exit($failed ? 1 : 0);
