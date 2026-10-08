'use strict';
// Prueba de comportamiento del cliente; Node solo se usa como herramienta de desarrollo.
const {readFileSync} = require('node:fs');
const {join} = require('node:path');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const source = readFileSync(join(__dirname, '../../public/assets/js/reservations.js'), 'utf8');
let checks = 0;
function check(condition, message) { assert.ok(condition, message); checks++; }
function page(transport, valid = true) {
    const button = {disabled: false};
    const feedback = {textContent: ''};
    const attributes = new Map();
    const timers = new Map();
    const navigation = [];
    let submit;
    let requests = 0;
    const form = {
        action: '/api/reservas', reportValidity: () => valid,
        querySelector: (selector) => selector === 'button[type="submit"]' ? button : selector === '[data-feedback]' ? feedback : null,
        addEventListener: (_, handler) => { submit = handler; },
        setAttribute: (key, value) => attributes.set(key, value),
        removeAttribute: (key) => attributes.delete(key),
    };
    vm.runInNewContext(source, {
        document: {querySelectorAll: () => [form]}, AbortController, URLSearchParams,
        FormData: class { *[Symbol.iterator]() { yield ['quantity', '1']; yield ['csrf', 'fictional-test-token']; } },
        fetch: (url, options) => { requests++; return transport(url, options); },
        setTimeout: (handler, milliseconds) => { assert.equal(milliseconds, 15000); timers.set(1, handler); return 1; },
        clearTimeout: (id) => timers.delete(id),
        window: {location: {assign: (url) => navigation.push(url), reload: () => navigation.push('reload')}},
    });
    return {button, feedback, attributes, timers, navigation, requests: () => requests,
        submit: () => submit({preventDefault() {}}), expire: () => [...timers.values()].forEach(handler => handler())};
}
const aborted = (signal) => new Promise((_, reject) => {
    if (signal.aborted) reject(new Error('abort'));
    else signal.addEventListener('abort', () => reject(new Error('abort')), {once: true});
});
(async () => {
    const success = page(async () => ({ok: true, status: 201, json: async () => ({message: 'Reserva confirmada.', ticket_url: '/tickets/fictional'})}));
    await success.submit();
    check(success.navigation[0] === '/tickets/fictional', 'Confirmación abre su ticket');
    check(success.timers.size === 0, 'Respuesta completa libera temporizador');
    await success.submit();
    check(success.requests() === 1, 'No repetir POST mientras navega al ticket');

    const pending = page((_, options) => aborted(options.signal));
    const first = pending.submit();
    await pending.submit();
    check(pending.requests() === 1 && pending.button.disabled, 'Doble submit no duplica petición pendiente');
    pending.expire(); await first;
    check(pending.feedback.textContent.includes('Consulte sus experiencias'), 'Timeout indica revisar historial');
    check(!pending.attributes.has('aria-busy') && pending.timers.size === 0, 'Timeout termina estado de carga');
    await pending.submit();
    check(pending.requests() === 1 && pending.button.disabled, 'No repetir resultado incierto por Enter');
    check(pending.navigation.length === 0, 'Timeout no inventa ticket ni cancela reserva');

    const body = page(async (_, options) => ({ok: true, status: 201, json: () => aborted(options.signal)}));
    const reading = body.submit(); await new Promise(setImmediate); body.expire(); await reading;
    check(body.feedback.textContent.includes('Consulte sus experiencias') && body.button.disabled, 'Límite incluye lectura del cuerpo tras cabeceras 201');

    const rejected = page(async () => ({ok: false, status: 422, json: async () => ({message: 'No hay espacios suficientes.'})}));
    await rejected.submit();
    check(rejected.feedback.textContent === 'No hay espacios suficientes.' && !rejected.button.disabled, 'Error de negocio permite corregir datos');
    check(rejected.navigation.length === 0 && rejected.timers.size === 0, '422 no navega ni mantiene espera');

    const failed = page(async () => ({ok: false, status: 500, json: async () => ({message: 'Error interno'})}));
    await failed.submit(); await failed.submit();
    check(failed.requests() === 1 && failed.feedback.textContent.includes('Consulte sus experiencias'), '500 no supone que la transacción no se confirmó');

    const malformed = page(async () => ({ok: true, status: 201, json: async () => { throw new Error('JSON inválido'); }}));
    await malformed.submit();
    check(malformed.button.disabled && malformed.navigation.length === 0, 'JSON inválido conserva resultado incierto');

    const invalid = page(() => { throw new Error('No debe enviar'); }, false);
    await invalid.submit();
    check(invalid.requests() === 0 && invalid.timers.size === 0, 'Formulario inválido no envía reserva');
    console.log(`Confirmación de reservas: ${checks} comprobaciones correctas.`);
})().catch(error => { console.error(error); process.exitCode = 1; });
