#!/usr/bin/env python3
"""Seleccionar una imagen candidata únicamente en una configuración TLS efímera."""
import json
import re
import sys
from pathlib import Path

source, target, image = sys.argv[1:]
if not re.fullmatch(r'eventify-mysql-minimal-[a-z0-9]+-candidate:0\.1\.0-dev\.[0-9]+', image):
    raise SystemExit('Nombre de imagen candidata no permitido para este ensayo.')
config = json.loads(Path(source).read_text())
if not re.fullmatch(r'eventify-(?:tls|no-tls)-[a-f0-9]{12}', config.get('name', '')):
    raise SystemExit('La configuración no pertenece a un ensayo TLS aislado.')
database = config['services']['db']
if (config.get('volumes') or database.get('ports')
        or database.get('tmpfs') != ['/var/lib/mysql']
        or not config['networks']['isolated'].get('internal')):
    raise SystemExit('La configuración no cumple las condiciones de aislamiento.')
database.pop('build', None)
database['image'] = image
Path(target).write_text(json.dumps(config))
