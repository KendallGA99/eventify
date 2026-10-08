"""Permisos del ZIP bajo umask privado: código Apache legible, secretos privados."""
import json
import os
from pathlib import Path
import sys
import tempfile
import zipfile

ROOT = Path(__file__).resolve().parents[2]
script = (ROOT/'scripts/test-clean-linux.sh').read_text()
python = script.split("<<'PY'\n",1)[1].split('\nPY\n',1)[0]
with tempfile.TemporaryDirectory(prefix='eventify-extract-') as directory:
    root=Path(directory)
    package=root/'package'/'fixture'
    package.mkdir(parents=True)
    manifest={'archive':'source.zip','commit':'fictional','sha256':'fixture','files':[]}
    (package/'manifest.json').write_text(json.dumps(manifest))
    with zipfile.ZipFile(package/'source.zip','w') as archive:
        for name,mode in [('Eventify/public/index.php',0o100644),('Eventify/scripts/start-linux.sh',0o100755)]:
            info=zipfile.ZipInfo(name);info.external_attr=mode<<16
            archive.writestr(info,'fixture')
    previous=os.umask(0o077)
    original=sys.argv
    try:
        sys.argv=['extract',str(root)]
        exec(compile(python,'clean-extraction','exec'),{'__name__':'__main__'})
    finally:
        os.umask(previous);sys.argv=original
    copy=root/'Copia limpia con espacios'/'Eventify'
    assert (root.stat().st_mode & 0o777)==0o700
    assert (copy.stat().st_mode & 0o777)==0o755
    assert ((copy/'public').stat().st_mode & 0o777)==0o755
    assert ((copy/'public/index.php').stat().st_mode & 0o777)==0o644
    assert ((copy/'scripts/start-linux.sh').stat().st_mode & 0o777)==0o755
    assert ((copy/'.env').stat().st_mode & 0o777)==0o600
    assert 'APP_PORT=8083' in (copy/'.env').read_text()
print('Extracción limpia: 7 comprobaciones de permisos/configuración correctas; sin Docker.')
