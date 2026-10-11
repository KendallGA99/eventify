#!/usr/bin/env python3
"""Exportar exclusivamente una referencia Git a un paquete privado de desarrollo."""
import argparse
import hashlib
import json
import pathlib
import subprocess
import sys
import zipfile


def git(root, *args):
    return subprocess.check_output(['git', '-C', str(root), *args], stderr=subprocess.DEVNULL)


def build(root, reference, output):
    commit = git(root, 'rev-parse', '--verify', '--end-of-options', reference + '^{commit}').decode().strip()
    if git(root, 'status', '--porcelain', '--untracked-files=no').strip():
        raise ValueError('Hay cambios versionados sin commit; guárdelos antes de exportar.')
    entries = git(root, 'ls-tree', '-r', '-z', commit).split(b'\0')
    names = []
    for entry in filter(None, entries):
        metadata, encoded = entry.split(b'\t', 1)
        mode, kind, _ = metadata.split()
        name = encoded.decode('utf-8')
        path = pathlib.PurePosixPath(name)
        parts = tuple(part.lower() for part in path.parts)
        forbidden = {'.git', 'vendor', 'node_modules', 'backups', 'tmp', 'test-results', 'playwright-report'}
        if (mode not in (b'100644', b'100755') or kind != b'blob'
                or path.is_absolute() or '..' in path.parts
                or any(part in forbidden for part in parts)
                or (parts[0] == 'storage' and name != 'storage/.gitkeep')
                or (path.name.lower().startswith('.env') and path.name != '.env.example')
                or path.suffix.lower() in {'.pem', '.key', '.p12', '.pfx', '.log', '.sql', '.gz'}):
            raise ValueError('Referencia contiene archivos excluidos, enlaces o módulos externos. Revisar Git.')
        names.append(name)
    for required in ['README.md', 'VERSION', '.env.example', 'Dockerfile', 'docker-compose.yml', 'composer.lock', 'docs/INSTALACION.md']:
        if required not in names:
            raise ValueError('Referencia incompleta para un paquete ejecutable.')
    output.mkdir(parents=True, exist_ok=True, mode=0o700)
    directory = output / ('eventify-desarrollo-' + commit[:12])
    directory.mkdir(mode=0o700)  # No sobrescribir un paquete previo.
    archive = directory / 'eventify-source.zip'
    subprocess.run(['git', '-C', str(root), 'archive', '--format=zip', '--prefix=Eventify/',
                    '--output=' + str(archive), commit], check=True, stderr=subprocess.DEVNULL)
    archive.chmod(0o600)
    files = []
    with zipfile.ZipFile(archive) as package:
        actual = sorted(info.filename.removeprefix('Eventify/') for info in package.infolist() if not info.is_dir())
        if actual != sorted(names) or package.testzip() is not None:
            raise ValueError('El ZIP no coincide con los archivos de la referencia Git.')
        for info in package.infolist():
            if not info.is_dir():
                files.append({'path': info.filename, 'bytes': info.file_size,
                              'sha256': hashlib.sha256(package.read(info)).hexdigest()})
        version = package.read('Eventify/VERSION').decode().strip()
    digest = hashlib.sha256(archive.read_bytes()).hexdigest()
    manifest = {'commit': commit, 'reference': reference, 'version': version,
                'status': 'DESARROLLO: no certifica entrega final ni Windows',
                'archive': archive.name, 'sha256': digest, 'files': files}
    for name, content in {
        'manifest.json': json.dumps(manifest, ensure_ascii=False, indent=2) + '\n',
        'SHA256SUMS.txt': digest + '  ' + archive.name + '\n',
        'LEAME-ENTREGA.txt': (
            'EVENTIFY — paquete de desarrollo\nVersión: ' + version + '\nCommit: ' + commit + '\n\n'
            'Extraiga eventify-source.zip y abra Eventify/docs/INSTALACION.md.\n'
            'Linux: bash scripts/start-linux.sh desde Eventify.\n'
            'Windows: siga la guía PowerShell, Docker Desktop y contenedores Linux.\n'
            'Docker/Compose e Internet son necesarios para la construcción inicial.\n'
            'No necesita Python ni Git para ejecutar la aplicación extraída.\n'
            'El arranque genera claves únicas; la demostración es una operación separada del manual.\n'
            'Este ZIP contiene código y migraciones, no datos de MySQL ni claves personales.\n'
            'No restaura reservas existentes; transferencia de datos se gestiona por separado.\n'
            'Revise docs/ESTADO.md y docs/MATRIZ.md: criterios finales y evidencias; instalación Windows y defensa pendientes. La API pública tiene evidencia de respuesta útil.\n'
            'Huella y manifest permiten comprobar integridad; no certifican autoría ni funcionamiento.\n'
        ),
    }.items():
        target = directory / name
        target.write_text(content, encoding='utf-8')
        target.chmod(0o600)
    return directory


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--ref', required=True, help='Etiqueta o commit concreto que se exportará.')
    parser.add_argument('--output-dir', default='backups/entrega')
    args = parser.parse_args()
    root = pathlib.Path(__file__).resolve().parent.parent
    output = pathlib.Path(args.output_dir).resolve()
    try:
        directory = build(root, args.ref, output)
        print('Paquete de desarrollo creado: ' + str(directory))
    except (ValueError, OSError, subprocess.SubprocessError, zipfile.BadZipFile, UnicodeError) as error:
        # Errores Git/IO podrían incluir contenido sensible: no imprimir su detalle.
        if isinstance(error, ValueError):
            print(str(error), file=sys.stderr)
        else:
            print('No se pudo exportar; revisar referencia, permisos o paquete previo. No se sobrescribió una entrega anterior.', file=sys.stderr)
        return 1
    return 0


if __name__ == '__main__':
    sys.exit(main())
