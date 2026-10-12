"""Empaqueta el publicador junto a un bundle ya revisado, sin tocar su historial."""
import argparse
import hashlib
from pathlib import Path
import zipfile

ROOT = Path(__file__).resolve().parent.parent
BUNDLE_SHA = 'a8aa91dea9150214492488ee8c2cc3b7dff5e500f0d65c56e48a53bf39583ca4'


def build(bundle: Path, destination: Path) -> Path:
    if hashlib.sha256(bundle.read_bytes()).hexdigest() != BUNDLE_SHA:
        raise ValueError('El bundle no corresponde al historial revisado.')
    destination.mkdir(parents=True, exist_ok=False)
    files = {
        'Eventify-Historial.bundle': bundle.read_bytes(),
        'LEAME-GITHUB.txt': (ROOT / 'docs/GUIA-GITHUB-WINDOWS.txt').read_bytes(),
    }
    for name in ['PUBLICAR-EN-GITHUB.cmd', 'scripts/publish-github-windows.ps1',
                 'scripts/github-publish-functions.ps1']:
        content = (ROOT / name).read_text().replace('\r\n', '\n').replace('\n', '\r\n')
        files[name] = content.encode('utf-8')
    files['SHA256SUMS.txt'] = ''.join(
        f'{hashlib.sha256(data).hexdigest()}  {name}\n'
        for name, data in sorted(files.items())
    ).encode('utf-8')
    result = destination / 'Eventify-GitHub-Automatico-v1.zip'
    with zipfile.ZipFile(result, 'x', compression=zipfile.ZIP_DEFLATED) as archive:
        for name, data in files.items():
            archive.writestr(name, data)
    with zipfile.ZipFile(result) as archive:
        assert archive.testzip() is None
        assert set(archive.namelist()) == set(files)
        for name, data in files.items():
            assert archive.read(name) == data
    print(result)
    print('SHA256:', hashlib.sha256(result.read_bytes()).hexdigest())
    return result


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('bundle', type=Path)
    parser.add_argument('destination', type=Path)
    arguments = parser.parse_args()
    build(arguments.bundle, arguments.destination)
