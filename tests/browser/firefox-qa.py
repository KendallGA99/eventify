#!/usr/bin/env python3
"""Recorrido Firefox con cuentas de evaluación y sin escrituras de negocio."""
import argparse
import json
from pathlib import Path
from urllib.parse import urlparse

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.firefox.service import Service
from selenium.webdriver.support.ui import Select, WebDriverWait

parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument('--base', required=True)
parser.add_argument('--driver', required=True)
parser.add_argument('--accounts', required=True)
parser.add_argument('--output', required=True)
args = parser.parse_args()
base = args.base.rstrip('/')
if urlparse(base).hostname not in {'localhost', '127.0.0.1'}:
    parser.error('Este recorrido utiliza exclusivamente una instalación local de evaluación.')
out = Path(args.output).resolve()
out.mkdir(parents=True, exist_ok=True)
accounts = json.loads(Path(args.accounts).read_text())
opts = Options()
opts.add_argument('-headless')
opts.enable_bidi = True
opts.binary_location = '/usr/bin/firefox'
driver = webdriver.Firefox(options=opts, service=Service(args.driver, log_output='/dev/null'))
wait = WebDriverWait(driver, 15)
checks = []
measurements = []
step = 'arranque'


def check(condition, description):
    if not condition:
        result['failed_check'] = description
        raise AssertionError(description)
    checks.append(description)


def css(selector):
    return driver.find_element(By.CSS_SELECTOR, selector)


def go(path):
    driver.get(base + path)
    wait.until(lambda d: d.execute_script('return document.readyState') == 'complete')
    wait.until(lambda d: css('main').text.strip() != '')


def catalog_ready(query=None):
    wait.until(lambda d: css('[data-catalog-results]').get_attribute('aria-busy') != 'true'
               and css('[data-catalog-feedback]').text != 'Buscando eventos…'
               and (query is None or query in d.current_url))


def fit(label):
    for width in (390, 768, 1280):
        driver.browsing_context.set_viewport(context=driver.current_window_handle, viewport={'width': width, 'height': 900})
        data = driver.execute_script('return {width: innerWidth, content: document.documentElement.scrollWidth}')
        measurements.append({'page': label, **data})
        check(data['width'] == width and data['content'] <= data['width'], label + ': sin desbordamiento a ' + str(data['width']) + 'px')
        wait.until(lambda d: d.execute_script("return document.getAnimations().every(a => a.playState !== 'running')"))
        driver.save_screenshot(str(out / (label + '-' + str(width) + '.png')))
    driver.save_screenshot(str(out / (label + '.png')))


def login(role):
    account = next(a for a in accounts if a['role'] == role)
    go('/ingresar')
    css('#email').send_keys(account['email'])
    css('#password').send_keys(account['password'])
    css('form button[type=submit]').click()
    wait.until(lambda d: urlparse(d.current_url).path == '/')
    check('Mi cuenta' in css('nav').text, role + ': ingreso al inicio con navegación autenticada')


def logout():
    go('/cuenta')
    css('form[action="/salir"] button').click()
    wait.until(lambda d: 'Ingresar' in css('nav').text)
    check('Mi cuenta' not in css('nav').text, 'Cerrar sesión retira navegación autenticada')


result = {'browser': driver.capabilities['browserName'], 'version': driver.capabilities['browserVersion'],
          'mode': 'headless, perfil temporal generado por geckodriver', 'application': 'dev.43',
          'base': base, 'checks': checks, 'measurements': measurements,
          'limits': ['Fecha seleccionada/borrada mediante valor y evento change; no acredita entrada con teclas o selección del calendario nativo.',
                     'No crea reservas ni modifica perfiles, categorías o eventos.',
                     'No acredita instalación Windows ni ensayo del estudiante.']}
try:
    step = 'catálogo, filtros y teclado'
    go('/eventos')
    search = css('#catalog-search')
    search.click()
    search.send_keys(Keys.TAB)
    check(driver.switch_to.active_element.get_attribute('id') == 'catalog-category', 'Tab búsqueda → categoría')
    focus = driver.execute_script('return getComputedStyle(document.activeElement).outlineStyle')
    check(focus not in {'none', 'hidden'}, 'Foco de teclado visible en categoría')
    driver.switch_to.active_element.send_keys(Keys.SHIFT, Keys.TAB)
    check(driver.switch_to.active_element.get_attribute('id') == 'catalog-search', 'Shift+Tab categoría → búsqueda')
    search.send_keys('Encuentro')
    catalog_ready('q=Encuentro')
    check('Encuentro de tecnología' in css('[data-catalog-results]').text, 'Búsqueda devuelve encuentro esperado')
    category = Select(css('#catalog-category'))
    technology = next(o for o in category.options if 'Tecnolog' in o.text or 'Tecnolog' in o.get_attribute('textContent'))
    category.select_by_value(technology.get_attribute('value'))
    catalog_ready('category=' + technology.get_attribute('value'))
    check('Encuentro de tecnología' in css('[data-catalog-results]').text, 'Nombre y categoría combinados')
    css('[data-catalog-clear]').click()
    catalog_ready('q=&category=')
    check(css('#catalog-search').get_attribute('value') == '' and Select(css('#catalog-category')).first_selected_option.get_attribute('value') == '', 'Limpiar restaura nombre y categoría')
    date = css('#catalog-from')
    # Firefox headless no introduce segmentos de fecha mediante send_keys.
    # Probar el cambio de valor/evento por separado, sin atribuirlo al calendario nativo.
    driver.execute_script("arguments[0].value = '2026-10-12'; arguments[0].dispatchEvent(new Event('change', {bubbles:true}));", date)
    check(date.get_attribute('value') == '2026-10-12', 'Fecha programática en Firefox: valor del control y filtrado')
    catalog_ready('from=2026-10-12')
    driver.execute_script("arguments[0].value = ''; arguments[0].dispatchEvent(new Event('change', {bubbles:true}));", date)
    catalog_ready('from=&')
    check(date.get_attribute('value') == '', 'Fecha borrada y catálogo actualizado')
    css('#catalog-search').send_keys('sin-resultados-qa-7926')
    catalog_ready('q=sin-resultados-qa-7926')
    check('No encontramos eventos' in css('[data-catalog-results]').text, 'Estado vacío del catálogo')
    css('[data-catalog-clear]').click()
    catalog_ready('q=&category=')
    fit('catalogo')

    step = 'contraseña e ingreso'
    go('/ingresar')
    css('#password').send_keys('Texto ficticio QA')
    css('[data-password-toggle]').click()
    check(css('#password').get_attribute('type') == 'text', 'Ojo muestra contraseña')
    css('[data-password-toggle]').click()
    check(css('#password').get_attribute('type') == 'password', 'Ojo oculta contraseña')
    login('USUARIO')
    step = 'perfil, historial y ticket'
    go('/cuenta')
    check(css('#full_name').get_attribute('value') != '', 'Perfil muestra nombre actual')
    check(css('#account-email').get_attribute('readonly') is not None, 'Correo del perfil es solo lectura')
    check(not driver.find_elements(By.CSS_SELECTOR, 'a[href="/admin"]'), 'Perfil de usuario sin acceso administrativo')
    fit('perfil')
    go('/experiencias')
    card = next(c for c in driver.find_elements(By.CSS_SELECTOR, '.event-card') if 'Encuentro de tecnología' in c.text)
    check('CANCELADA' in card.text and '2 entradas' in card.text, 'Historial cancelado conserva dos entradas')
    ticket_path = urlparse(card.find_element(By.CSS_SELECTOR, 'a[href^="/tickets/"]').get_attribute('href')).path
    card.find_element(By.CSS_SELECTOR, 'a[href^="/tickets/"]').click()
    wait.until(lambda d: d.find_elements(By.CSS_SELECTOR, '.ticket-qr'))
    wait.until(lambda d: d.execute_script('return document.querySelector(".ticket-qr").complete && document.querySelector(".ticket-qr").naturalWidth > 0'))
    check('ya no está vigente' in css('main').text, 'Ticket cancelado conserva QR y aviso de invalidez')
    fit('ticket')
    go('/admin')
    check('Esta sección es para administradores' in ' '.join(css('main').text.split()), 'Usuario rechazado al abrir administración por URL')
    fit('acceso-restringido')
    logout()
    go(ticket_path)
    check(urlparse(driver.current_url).path == '/ingresar', 'Ticket requiere ingreso después de cerrar sesión')
    step = 'administración'
    login('ADMINISTRADOR')
    for path, label in [('/admin', 'administracion'), ('/admin/categorias', 'categorias'), ('/admin/eventos', 'eventos-admin'), ('/admin/reservas', 'reservas-admin'), ('/admin/usuarios', 'usuarios-admin')]:
        go(path)
        check(urlparse(driver.current_url).path == path and css('main').text != '', label + ': pantalla accesible')
        check(css('link[rel=stylesheet]').get_attribute('href').endswith('/assets/css/app.css'), label + ': estilos presentes')
        fit(label)
    step = 'confirmación administrativa'
    candidate_link = css('a[href$="/administrador"]')
    candidate_link.click()
    wait.until(lambda d: d.find_elements(By.CSS_SELECTOR, '#admin-password'))
    check(css('#admin-password').get_attribute('value') == '', 'Confirmación no precarga contraseña')
    css('[data-password-toggle]').click()
    check(css('#admin-password').get_attribute('type') == 'text', 'Ojo administrativo muestra el campo')
    css('[data-password-toggle]').click()
    check(not css('input[name=confirm]').is_selected(), 'Autorización exige confirmación sin premarcar')
    fit('confirmacion-admin')
    logout()
    result['status'] = 'passed'
except Exception as error:
    result.update(status='failed', failed_step=step, error_type=type(error).__name__)
    # No incluir texto de excepciones: puede contener valores de formularios.
    driver.save_screenshot(str(out / 'fallo.png'))
    print('Fallo QA en:', step, type(error).__name__, result.get('failed_check', ''))
finally:
    driver.quit()
    (out / 'result.json').write_text(json.dumps(result, ensure_ascii=False, indent=2) + '\n')
print('Firefox QA:', result['status'], len(checks), 'comprobaciones.')
raise SystemExit(0 if result['status'] == 'passed' else 1)
