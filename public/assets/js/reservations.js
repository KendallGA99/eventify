'use strict';
document.querySelectorAll('form[data-async]').forEach((form) => {
    let submitting = false;
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
        if (submitting) return;
        if (!form.reportValidity()) return;
        const button = form.querySelector('button[type="submit"]');
        const feedback = form.querySelector('[data-feedback]');
        submitting = true;
        button.disabled = true;
        form.setAttribute('aria-busy', 'true');
        feedback.textContent = 'Procesando su solicitud…';
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 15000);
        let uncertain = false;
        let navigating = false;
        try {
            const response = await fetch(form.action, {method: 'POST', body: new URLSearchParams(new FormData(form)), headers: {'Accept': 'application/json'}, credentials: 'same-origin', signal: controller.signal});
            const data = await response.json();
            if (response.status >= 500) throw new Error('Resultado de reserva incierto');
            feedback.textContent = data.message || 'No fue posible completar la solicitud.';
            if (response.ok) {
                navigating = true;
                if (data.ticket_url) window.location.assign(data.ticket_url);
                else window.location.reload();
            }
        } catch {
            // Abortar la espera no deshace una transacción ya confirmada en el servidor.
            uncertain = true;
            feedback.textContent = 'No pudimos confirmar el resultado. Consulte sus experiencias antes de intentarlo de nuevo.';
        } finally {
            clearTimeout(timeout);
            submitting = uncertain || navigating;
            button.disabled = submitting;
            form.removeAttribute('aria-busy');
        }
    });
});
