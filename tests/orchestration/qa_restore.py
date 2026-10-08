"""Fallos de orquestación sin Docker/MySQL: no certifica restauración de datos."""
import json
import os
from pathlib import Path
import shutil
import subprocess
import tempfile

ROOT = Path(__file__).resolve().parents[2]
FAKE_DOCKER = r'''#!/usr/bin/env python3
import json, os, sys
from pathlib import Path
args = sys.argv[1:]
with open(os.environ['EVENTIFY_TEST_TRACE'], 'a') as output:
    output.write(json.dumps(args) + '\n')
scenario = os.environ['EVENTIFY_TEST_SCENARIO']
project = args[args.index('-p') + 1] if '-p' in args else ''
if 'mysqldump' in ' '.join(args):
    if scenario == 'dump_fail': sys.exit(1)
    print('CREATE TABLE fictional (id INT);')
elif args[-1:] == ['empty']:
    if scenario == 'nonempty': sys.exit(1)
elif args[-1:] == ['snapshot']:
    print(json.dumps({'fingerprint': 'different' if scenario == 'mismatch' and project.startswith('eventify-restore-') else 'same'}))
elif '--wait' in args and args[-1:] == ['db'] and scenario == 'destination_fail':
    sys.exit(1)
elif 'tar' in args and '-cf' in args:
    print('fictional archive')
'''

checks = 0
def check(condition, message):
    global checks
    assert condition, message
    checks += 1

for scenario in ['success', 'dump_fail', 'nonempty', 'mismatch', 'destination_fail']:
    with tempfile.TemporaryDirectory(prefix='eventify-restore-script-') as folder:
        root = Path(folder)
        (root / 'scripts').mkdir()
        (root / 'storage/qa').mkdir(parents=True)
        (root / 'fake-bin').mkdir()
        for name in ['common-linux.sh', 'test-restore-qa-linux.sh']:
            shutil.copy2(ROOT / 'scripts' / name, root / 'scripts' / name)
        (root / 'docker-compose.yml').write_text('fixture only\n')
        (root / 'storage/qa/qa.env').write_text('fixture only\n')
        docker = root / 'fake-bin/docker'
        docker.write_text(FAKE_DOCKER)
        docker.chmod(0o700)
        trace = root / 'trace.jsonl'
        env = {**os.environ, 'PATH': str(root / 'fake-bin') + os.pathsep + os.environ['PATH'],
               'EVENTIFY_TEST_TRACE': str(trace), 'EVENTIFY_TEST_SCENARIO': scenario}
        result = subprocess.run(['bash', str(root / 'scripts/test-restore-qa-linux.sh')],
                                env=env, capture_output=True, text=True, timeout=20)
        calls = [json.loads(line) for line in trace.read_text().splitlines()]
        jobs = list((root / 'storage').glob('restore-*'))
        check(len(jobs) == 1, f'{scenario}: carpeta privada propia')
        status = (jobs[0] / 'status.txt').read_text()
        check(('exit_code=0' in status) == (scenario == 'success'), f'{scenario}: resultado real registrado')
        check((result.returncode == 0) == (scenario == 'success'), f'{scenario}: fallo no encubierto')
        source_restart = [args for args in calls if 'eventify-qa' in args and 'up' in args and args[-1:] == ['web']]
        check(bool(source_restart), f'{scenario}: origen reiniciado tras detenerlo')
        check(not any('down' in args or '--volumes' in args or '-v' in args for args in calls), f'{scenario}: no borrar volúmenes')
        imports = [args for args in calls if '--binary-mode=1' in ' '.join(args)]
        if scenario in ['dump_fail', 'nonempty', 'destination_fail']:
            check(not imports, f'{scenario}: no importar si respaldo/destino fallan')
        else:
            check(len(imports) == 1 and 'eventify_restore' in imports[0][-1], f'{scenario}: importación solo destino explícito')
        check((jobs[0].stat().st_mode & 0o777) == 0o700, f'{scenario}: directorio de respaldo privado')
        check((jobs[0] / 'restore.env').stat().st_mode & 0o777 == 0o600, f'{scenario}: claves privadas')

print(f'Orquestación de restauración: {checks} comprobaciones correctas (Docker simulado; MySQL real pendiente).')
