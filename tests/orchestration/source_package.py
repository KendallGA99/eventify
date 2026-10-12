#!/usr/bin/env python3
"""Ensayo de exportación sobre repositorios temporales propios, sin Docker."""
import hashlib
import importlib.util
import json
import pathlib
import subprocess
import tempfile
import zipfile

root = pathlib.Path(__file__).resolve().parents[2]
spec = importlib.util.spec_from_file_location('eventify_package', root / 'scripts/package-source.py')
module = importlib.util.module_from_spec(spec)
spec.loader.exec_module(module)
checks = 0


def expect(ok):
    global checks
    assert ok, 'Falló una comprobación de empaquetado.'
    checks += 1


def commit(repo):
    subprocess.run(['git', '-C', str(repo), 'add', '.'], check=True, stdout=subprocess.DEVNULL)
    subprocess.run(['git', '-C', str(repo), 'commit', '-qm', 'Fixture de empaquetado'], check=True)


with tempfile.TemporaryDirectory(prefix='eventify paquete ensayo ') as temporary:
    base = pathlib.Path(temporary)
    repo = base / 'repositorio con espacios'
    repo.mkdir()
    subprocess.run(['git', 'init', '-q', str(repo)], check=True)
    for key, value in [('user.name', 'Prueba ficticia'), ('user.email', 'fixture@example.invalid')]:
        subprocess.run(['git', '-C', str(repo), 'config', key, value], check=True)
    for name in ['README.md', 'VERSION', '.env.example', 'Dockerfile', 'docker-compose.yml', 'composer.lock', 'docs/DESPLIEGUE.md']:
        target = repo / name
        target.parent.mkdir(exist_ok=True)
        target.write_text('0.0.0-dev.fixture\n')
    commit(repo)
    revision = module.git(repo, 'rev-parse', 'HEAD').decode().strip()
    (repo / '.env').write_text('fixture local no versionado')
    package = module.build(repo, revision, base / 'entrega')
    manifest = json.loads((package / 'manifest.json').read_text())
    expect(manifest['commit'] == revision and 'DESARROLLO' in manifest['status'])
    expect(manifest['sha256'] == hashlib.sha256((package / 'eventify-source.zip').read_bytes()).hexdigest())
    with zipfile.ZipFile(package / 'eventify-source.zip') as archive:
        expect('Eventify/.env.example' in archive.namelist() and 'Eventify/.git/config' not in archive.namelist() and 'Eventify/.env' not in archive.namelist())
        expect(all(hashlib.sha256(archive.read(item['path'])).hexdigest() == item['sha256'] for item in manifest['files']))
    expect((package.stat().st_mode & 0o777) == 0o700 and ((package / 'eventify-source.zip').stat().st_mode & 0o777) == 0o600)
    try:
        module.build(repo, revision, base / 'entrega')
        rejected = False
    except FileExistsError:
        rejected = True
    expect(rejected)
    second = module.build(repo, revision, base / 'otra entrega')
    expect((second / 'eventify-source.zip').read_bytes() == (package / 'eventify-source.zip').read_bytes())
    (repo / '.env').unlink()
    (repo / 'README.md').write_text('cambio sin commit')
    try:
        module.build(repo, revision, base / 'dirty')
        rejected = False
    except ValueError:
        rejected = True
    expect(rejected and not (base / 'dirty').exists())
    subprocess.run(['git', '-C', str(repo), 'restore', 'README.md'], check=True)
    for index, name in enumerate(['.env', 'storage/credentials.json', 'certificado.pem', 'datos.sql', 'enlace']):
        target = repo / name
        target.parent.mkdir(exist_ok=True)
        if name == 'enlace':
            target.symlink_to('README.md')
        else:
            target.write_text('fixture ficticio')
        commit(repo)
        try:
            module.build(repo, 'HEAD', base / ('reject-' + str(index)))
            rejected = False
        except ValueError:
            rejected = True
        expect(rejected and not (base / ('reject-' + str(index))).exists())
        target.unlink()
        commit(repo)
print(f'Empaquetado: {checks} comprobaciones correctas (repositorios ficticios).')
