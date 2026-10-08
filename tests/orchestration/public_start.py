"""Prueba el entrypoint real con comandos simulados y rutas Apache temporales."""
import os
import pathlib
import subprocess
import tempfile

ROOT = pathlib.Path(__file__).resolve().parents[2]
source = (ROOT / 'docker/public-entrypoint.sh').read_text()
checks = 0
for case, port, failure, success in [
    ('default', None, '', True), ('port', '8088', '', True),
    ('zero', '0', '', False), ('large', '65536', '', False),
    ('injection', '80;echo-private', '', False), ('preflight', None, 'preflight', False),
    ('apache', None, 'apache', False), ('migration', None, 'migration', False),
]:
    with tempfile.TemporaryDirectory(prefix='eventify-public-start-') as directory:
        path = pathlib.Path(directory)
        apache = path / 'apache'
        (apache / 'sites-available').mkdir(parents=True)
        (apache / 'sites-available/000-default.conf').write_text((ROOT / 'docker/apache.conf').read_text())
        app = path / 'app'
        app.mkdir()
        commands = path / 'commands'
        commands.mkdir()
        log = path / 'calls'
        mock = '''#!/usr/bin/env python3
import os,sys,pathlib
name=pathlib.Path(sys.argv[0]).name
step=('preflight' if 'public-preflight.php' in sys.argv[-1] else 'migration') if name=='php' else ('apache' if name=='apache2ctl' else name)
with open(os.environ['TEST_LOG'],'a') as f:f.write(step+'\\n')
sys.exit(1 if os.environ['TEST_FAILURE']==step else 0)
'''
        for name in ['php', 'apache2ctl', 'chown', 'docker-php-entrypoint']:
            file = commands / name
            file.write_text(mock)
            file.chmod(0o755)
        script = path / 'entry.sh'
        script.write_text(source.replace('/var/www/html', str(app)).replace('/etc/apache2', str(apache)))
        environment = dict(os.environ, PATH=str(commands)+os.pathsep+os.environ['PATH'], TEST_LOG=str(log), TEST_FAILURE=failure)
        environment.pop('PORT', None)
        if port is not None:
            environment['PORT'] = port
        result = subprocess.run(['bash', str(script), 'apache2-foreground'], env=environment, capture_output=True)
        calls = log.read_text().splitlines() if log.exists() else []
        assert (result.returncode == 0) == success, case
        checks += 1
        assert ('docker-php-entrypoint' in calls) == success, case
        checks += 1
        if success:
            assert calls == ['preflight', 'chown', 'apache', 'migration', 'docker-php-entrypoint'], case
            assert (apache / 'ports.conf').read_text() == 'Listen '+(port or '10000')+'\n', case
            assert '<VirtualHost *:'+(port or '10000')+'>' in (apache / 'sites-available/000-default.conf').read_text(), case
            checks += 3
        elif failure:
            assert calls[-1] == failure, case
            checks += 1
print(f'Arranque público: {checks} comprobaciones simuladas correctas.')
