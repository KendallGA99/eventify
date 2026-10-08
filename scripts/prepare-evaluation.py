"""Genera un plan privado; no conecta, publica ni crea usuarios en MySQL."""
import json
import os
import pathlib
import secrets
import sys

if len(sys.argv) != 2:
    raise SystemExit('Uso: prepare-evaluation.py <directorio-privado-nuevo>')
root = pathlib.Path(sys.argv[1]).resolve()
try:
    root.mkdir(mode=0o700, parents=False, exist_ok=False)
except OSError:
    raise SystemExit('El directorio privado debe ser nuevo y su padre debe existir; no se sobrescribe.')
accounts = []
for email, name, role in [
    ('admin-evaluacion@eventify.test', 'Administrador de evaluación', 'ADMINISTRADOR'),
    ('usuario-evaluacion@eventify.test', 'Usuario de evaluación', 'USUARIO'),
    ('otro-evaluacion@eventify.test', 'Otro usuario de evaluación', 'USUARIO'),
]:
    accounts.append(dict(email=email, full_name=name, role=role, password=secrets.token_hex(16)))
file = root / 'accounts.json'
# Crear privado desde el primer byte, independientemente del umask del host.
with os.fdopen(os.open(file, os.O_WRONLY | os.O_CREAT | os.O_EXCL, 0o600), 'w') as stream:
    json.dump(accounts, stream, ensure_ascii=False, indent=2)
print('Plan de tres cuentas preparado en directorio privado; no se creó ninguna cuenta ni se imprimen claves.')
