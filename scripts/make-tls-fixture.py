"""Certificados ficticios para el ensayo aislado; nunca certificados públicos reales."""
import pathlib
import subprocess
import sys

if len(sys.argv) != 2:
    raise SystemExit('Uso: make-tls-fixture.py <directorio-nuevo>')
root = pathlib.Path(sys.argv[1]).resolve()
root.mkdir(mode=0o700, parents=False, exist_ok=False)

def openssl(*args):
    result = subprocess.run(['openssl', *args], cwd=root, capture_output=True)
    if result.returncode:
        raise SystemExit('No se pudieron preparar certificados ficticios; detalles omitidos.')

for prefix in ['ca', 'wrong-ca']:
    openssl('req', '-x509', '-newkey', 'rsa:2048', '-nodes', '-days', '2',
            '-keyout', prefix+'-key.pem', '-out', prefix+'.pem',
            '-subj', '/CN=Eventify fixture '+prefix, '-addext', 'basicConstraints=critical,CA:TRUE')
openssl('req', '-newkey', 'rsa:2048', '-nodes', '-keyout', 'server-key.pem',
        '-out', 'server.csr', '-subj', '/CN=db')
(root/'server.ext').write_text('subjectAltName=DNS:db\nbasicConstraints=critical,CA:FALSE\nkeyUsage=critical,digitalSignature,keyEncipherment\nextendedKeyUsage=serverAuth\n')
openssl('x509', '-req', '-in', 'server.csr', '-CA', 'ca.pem', '-CAkey', 'ca-key.pem',
        '-CAcreateserial', '-out', 'server.pem', '-days', '2', '-extfile', 'server.ext')
for file in root.iterdir():
    file.chmod(0o600 if 'key' in file.name else 0o644)
openssl('verify', '-CAfile', 'ca.pem', '-verify_hostname', 'db', 'server.pem')
for args in [('verify','-CAfile','wrong-ca.pem','server.pem'),
             ('verify','-CAfile','ca.pem','-verify_hostname','wrong-host','server.pem')]:
    if subprocess.run(['openssl',*args],cwd=root,capture_output=True).returncode == 0:
        raise SystemExit('Certificado negativo aceptado; fixture rechazada.')
print('Certificados ficticios: CA/nombre válidos y dos casos negativos verificados.')
