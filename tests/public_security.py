"""Comprueba controles HTTPS con cuentas ficticias de evaluación.

No crea reservas, eventos ni usuarios. Los POST de negocio llevan un CSRF
inválido y deben rechazarse antes de escribir; solo login/logout cambian sesión.
"""

import argparse
import http.cookiejar
import json
import re
import urllib.error
import urllib.parse
import urllib.request
from pathlib import Path


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        # Las credenciales se envían únicamente al origen indicado, sin redirecciones.
        return None


class Client:
    def __init__(self, base):
        self.base = base
        self.cookies = http.cookiejar.CookieJar()
        self.opener = urllib.request.build_opener(
            urllib.request.HTTPCookieProcessor(self.cookies), NoRedirect()
        )

    def request(self, path, expected, data=None, cookie=None):
        headers = {"User-Agent": "Eventify-Evaluation/0.1"}
        if cookie:
            headers["Cookie"] = cookie
        payload = None if data is None else urllib.parse.urlencode(data).encode()
        request = urllib.request.Request(self.base + path, payload, headers)
        try:
            response = self.opener.open(request, timeout=75)
        except urllib.error.HTTPError as error:
            response = error
        with response:
            body = response.read(2_000_000).decode("utf-8")
            check(response.status == expected, f"Estado HTTP {expected}")
            check("SQLSTATE" not in body and "Stack trace" not in body, "Error sin detalles internos")
            return response.headers, body

    def session(self):
        values = [c.value for c in self.cookies if c.name == "eventify_session"]
        assert len(values) == 1
        return values[0]

    def login(self, account):
        _, form = self.request("/ingresar", 200)
        before = self.session()
        headers, _ = self.request("/ingresar", 303, {
            "csrf": token(form), "email": account["email"], "password": account["password"]
        })
        check(headers.get("Location") == "/cuenta", "Login redirige a cuenta")
        check(self.session() != before, "Login regenera sesión")
        headers, page = self.request("/cuenta", 200)
        check(account["email"] in page, "Cuenta autenticada correcta")
        check(headers.get("Cache-Control") == "no-store", "Cuenta sin caché")
        return token(page)


checks = 0


def check(condition, label):
    global checks
    if not condition:
        raise RuntimeError(label)
    checks += 1


def token(html):
    match = re.search(r'name="csrf" value="([a-f0-9]{64})"', html)
    check(match is not None, "CSRF presente")
    return match.group(1)


def run(base, accounts):
    users = [a for a in accounts if a["role"] == "USUARIO"]
    assert len(users) == 2 and all(a["email"].endswith("@eventify.test") for a in accounts)
    anonymous = Client(base)
    headers, form = anonymous.request("/ingresar", 200)
    cookie = headers.get("Set-Cookie", "").lower()
    for attribute in ["secure", "httponly", "samesite=lax", "path=/"]:
        check(attribute in cookie, "Cookie protegida: " + attribute)
    check(headers.get("X-Content-Type-Options") == "nosniff", "No detectar MIME")
    check(headers.get("X-Frame-Options") == "DENY", "Sin iframe")
    check("frame-ancestors 'none'" in headers.get("Content-Security-Policy", ""), "CSP activa")
    anonymous.request("/ingresar", 403, {"email": users[0]["email"], "password": users[0]["password"]})
    anonymous.request("/api/experiencias", 401)
    anonymous.request("/salir", 405)
    anonymous.request("/api/reservas", 405)
    for path in ["/.env", "/storage/private-runtime/mysql-ca.pem", "/app/bootstrap.php", "/database/migrations/001_initial_schema.php"]:
        anonymous.request(path, 404)

    owner, other = Client(base), Client(base)
    owner_token = owner.login(users[0])
    other_token = other.login(users[1])
    _, history = owner.request("/experiencias", 200)
    ticket = re.search(r'href="(/tickets/[a-f0-9]{64})"', history)
    check(ticket is not None, "Ticket existente para probar propiedad")
    for suffix in ["", "/qr.svg"]:
        other.request(ticket.group(1) + suffix, 404)
    _, other_history = other.request("/api/experiencias", 200)
    check(ticket.group(1) not in other_history, "Historial no muestra tickets ajenos")
    other.request("/admin", 403)
    other.request("/admin/eventos/1/editar", 403)
    owner.request("/api/reservas", 403, {"csrf": other_token, "event_id": "1", "quantity": "1"})
    owner.request("/api/reservas/1/cancelar", 403, {"csrf": "invalid"})
    owner.request("/salir", 403, {"csrf": other_token})
    owner.request("/cuenta", 200)

    old_session = owner.session()
    owner.request("/salir", 303, {"csrf": owner_token})
    headers, _ = owner.request("/cuenta", 303)
    check(headers.get("Location") == "/ingresar", "Logout elimina acceso")
    replay = Client(base)
    replay.request("/cuenta", 303, cookie="eventify_session=" + old_session)
    check(replay.session() != old_session, "Sesión destruida no se puede reutilizar")
    other.request("/salir", 303, {"csrf": other_token})


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--base", required=True, help="Origen HTTPS de evaluación autorizado")
    parser.add_argument("--accounts", required=True, type=Path, help="JSON privado de cuentas ficticias")
    args = parser.parse_args()
    origin = urllib.parse.urlsplit(args.base)
    if origin.scheme != "https" or not origin.hostname or origin.username or origin.password or origin.path not in ["", "/"] or origin.query or origin.fragment:
        parser.error("Indique solamente un origen HTTPS, sin credenciales ni parámetros.")
    try:
        run(args.base.rstrip("/"), json.loads(args.accounts.read_text()))
    except Exception as error:
        # No imprimir respuestas, tokens, cookies, credenciales ni trazas.
        print(f"Verificación pública incompleta después de {checks} controles ({type(error).__name__}).")
        raise SystemExit(1)
    print(f"Seguridad HTTPS pública: {checks} comprobaciones correctas. Sin escrituras de negocio.")
