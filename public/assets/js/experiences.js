'use strict';
jQuery(($) => {
    const $results = $('[data-experiences-results]');
    if (!$results.length) return;
    const $feedback = $('[data-experiences-feedback]');
    const $refresh = $('[data-experiences-refresh]');
    let busy = false;
    const setBusy = (value) => {
        busy = value;
        $refresh.prop('disabled', value);
        $results.find('button').prop('disabled', value);
        if (value) $results.attr('aria-busy', 'true');
        else $results.removeAttr('aria-busy');
    };
    const update = (cancelled = false) => $.ajax({url: '/api/experiencias', dataType: 'json', timeout: 8000})
        .done((data) => {
            $results.html(data.html);
            $feedback.text(cancelled ? 'Reserva cancelada. Sus experiencias están actualizadas.' : data.message);
        })
        .fail((xhr) => $feedback.text(xhr.responseJSON?.message || (cancelled
            ? 'Reserva cancelada, pero no pudimos actualizar la lista. Pulse Actualizar mis experiencias.'
            : 'No pudimos actualizar la lista. Sus experiencias anteriores permanecen disponibles.')));
    $refresh.on('click', () => {
        if (busy) return;
        setBusy(true);
        $feedback.text('Actualizando sus experiencias…');
        update().always(() => setBusy(false));
    });
    $results.on('submit', '[data-cancel-reservation]', (event) => {
        event.preventDefault();
        if (busy) return;
        const $form = $(event.currentTarget);
        const values = $form.serializeArray().filter((item) => item.name !== 'csrf');
        // El token corresponde a la sesión actual de la página; no confiar en un formulario viejo del fragmento.
        values.push({name: 'csrf', value: $results.attr('data-csrf')});
        setBusy(true);
        $feedback.text('Cancelando su reserva…');
        $.ajax({url: $form.attr('action'), method: 'POST', data: $.param(values), dataType: 'json', timeout: 8000})
            .done(() => update(true).always(() => setBusy(false)))
            .fail((xhr) => {
                $feedback.text(xhr.responseJSON?.message || 'No pudimos confirmar el resultado. Actualice sus experiencias antes de intentarlo otra vez.');
                setBusy(false);
            });
    });
});
