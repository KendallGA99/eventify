"""Verificar selección explícita de proyecto cuando sudo elimina el entorno."""
import json
import os
from pathlib import Path
import shutil
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[2]
checks = 0
with tempfile.TemporaryDirectory(prefix='eventify-start-') as directory:
    root = Path(directory)
    (root / 'scripts').mkdir()
    for name in ['start-linux.sh', 'common-linux.sh']:
        shutil.copy(ROOT / 'scripts' / name, root / 'scripts' / name)
    (root / '.env').write_text('APP_PORT=8083\n')
    tools = root / 'tools'
    tools.mkdir()
    trace = root / 'trace.jsonl'
    docker = tools / 'docker'
    docker.write_text('''#!/usr/bin/env python3
import json,os,sys
if sys.argv[1:]==['info'] and not os.environ.get('FAKE_SUDO'): sys.exit(1)
with open(os.environ['TRACE'],'a') as f: f.write(json.dumps(sys.argv[1:])+'\\n')
''')
    docker.chmod(0o700)
    sudo = tools / 'sudo'
    sudo.write_text('''#!/usr/bin/env python3
import os,sys
os.environ.pop('COMPOSE_PROJECT_NAME',None)
os.environ['FAKE_SUDO']='1'
os.execvp(sys.argv[1],sys.argv[1:])
''')
    sudo.chmod(0o700)
    env = dict(os.environ, PATH=str(tools)+os.pathsep+os.environ['PATH'], TRACE=str(trace), COMPOSE_PROJECT_NAME='eventify-clean-environment')
    for args, expected in [([], 'eventify'), (['--project','eventify-clean-explicit'], 'eventify-clean-explicit')]:
        trace.write_text('')
        result = subprocess.run(['bash', str(root/'scripts/start-linux.sh'), *args], env=env, capture_output=True)
        assert result.returncode == 0
        checks += 1
        calls = [json.loads(line) for line in trace.read_text().splitlines()]
        mutations = [a for a in calls if 'up' in a or 'exec' in a]
        assert len(mutations) == 2
        checks += 1
        assert all(a[:3] == ['compose','-p',expected] for a in mutations)
        checks += 1
    for args in [['--project','../eventify'], ['--project'], ['--unknown','eventify']]:
        trace.write_text('')
        result = subprocess.run(['bash', str(root/'scripts/start-linux.sh'), *args], env=env, capture_output=True)
        assert result.returncode != 0 and trace.read_text() == ''
        checks += 1
print(f'Proyecto de arranque: {checks} comprobaciones correctas (sudo/Docker simulados).')
