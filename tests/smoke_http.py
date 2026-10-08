"""Comprobaciones HTTP de B01; no crea ni modifica datos de negocio."""

import argparse
import json
import urllib.error
import urllib.request

parser = argparse.ArgumentParser()
parser.add_argument("--base", default="http://localhost:8080")
parser.add_argument("--expect-database-unavailable", action="store_true")
args = parser.parse_args()
checks = 0


def request(path, expected, method="GET"):
    global checks
    req = urllib.request.Request(args.base.rstrip("/") + path, method=method)
    try:
        response = urllib.request.urlopen(req, timeout=5)
    except urllib.error.HTTPError as error:
        response = error
    with response:
        status, headers, body = response.status, response.headers, response.read().decode("utf-8")
    assert status == expected, f"{method} {path}: esperado {expected}, observado {status}"
    assert "SQLSTATE" not in body and "Stack trace" not in body
    checks += 1
    print(f"{method} {path}: {status}")
    return headers, body


headers, body = request("/", 200)
assert "Estamos preparando Eventify" in body
assert headers.get("X-Content-Type-Options") == "nosniff"
assert "frame-ancestors 'none'" in headers.get("Content-Security-Policy", "")
request("/assets/css/app.css", 200)
request("/pagina-inexistente", 404)
for path in ["/.env", "/app/bootstrap.php", "/config/README.md", "/docker-compose.yml"]:
    request(path, 404)
headers, body = request("/salud", 503 if args.expect_database_unavailable else 200)
assert json.loads(body) == {"status": "unavailable" if args.expect_database_unavailable else "ok"}
assert headers.get("Cache-Control") == "no-store"
headers, _ = request("/salud", 405, method="POST")
assert headers.get("Allow") == "GET, HEAD"
print(f"HTTP: {checks} comprobaciones correctas.")
