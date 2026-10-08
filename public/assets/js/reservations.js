'use strict';
document.querySelectorAll('form[data-async]').forEach((form) => {
    const quantity = form.querySelector('[name="quantity"]');
    const total = form.querySelector('[data-total]');
    if (quantity && total) {
        const updateTotal = () => {
            if (!/^[1-9][0-9]*$/.test(quantity.value)) { total.textContent = 'Seleccione una cantidad válida'; return; }
            const cents = BigInt(form.dataset.price.replace('.', '')) * BigInt(quantity.value);
            const whole = (cents / 100n).toLocaleString('es-CR');
            total.textContent = `₡${whole},${(cents % 100n).toString().padStart(2, '0')}`;
        };
        quantity.addEventListener('input', updateTotal);
        updateTotal();
    }
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!form.reportValidity()) return;
        const button = form.querySelector('button[type="submit"]');
        const feedback = form.querySelector('[data-feedback]');
        button.disabled = true;
        form.setAttribute('aria-busy', 'true');
        feedback.textContent = 'Procesando su solicitud…';
        try {
            const response = await fetch(form.action, {method: 'POST', body: new URLSearchParams(new FormData(form)), headers: {'Accept': 'application/json'}, credentials: 'same-origin'});
            const data = await response.json();
            feedback.textContent = data.message || 'No fue posible completar la solicitud.';
            if (response.ok) {
                if (data.ticket_url) window.location.assign(data.ticket_url);
                else window.location.reload();
            }
        } catch {
            feedback.textContent = 'No pudimos confirmar el resultado. Consulte sus experiencias antes de intentarlo de nuevo.';
        } finally {
            button.disabled = false;
            form.removeAttribute('aria-busy');
        }
    });
});
