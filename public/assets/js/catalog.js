'use strict';
jQuery(($) => {
    const $form = $('[data-catalog-filters]');
    if (!$form.length) return;
    const $results = $('[data-catalog-results]');
    const $feedback = $('[data-catalog-feedback]');
    let timer;
    let request;
    let sequence = 0;
    const invalidate = () => {
        clearTimeout(timer);
        sequence += 1;
        if (request) request.abort();
        $results.removeAttr('aria-busy');
        return sequence;
    };
    const load = (page, expectedSequence) => {
        if (expectedSequence !== sequence || !$form[0].reportValidity()) return;
        const values = $form.serializeArray();
        values.push({name: 'page', value: String(page)});
        $results.attr('aria-busy', 'true');
        $feedback.text('Buscando eventos…');
        request = $.ajax({url: '/api/eventos', data: $.param(values), dataType: 'json', timeout: 8000})
            .done((data) => {
                if (expectedSequence !== sequence) return;
                $results.html(data.html);
                $feedback.text(data.message);
                window.history.replaceState(null, '', `/eventos?${$.param(values)}`);
            })
            .fail((xhr, reason) => {
                if (expectedSequence !== sequence || reason === 'abort') return;
                $feedback.text(xhr.responseJSON?.message || 'No pudimos actualizar los eventos. Se conservan los resultados anteriores; vuelva a intentar.');
            })
            .always(() => {
                if (expectedSequence === sequence) $results.removeAttr('aria-busy');
            });
    };
    $form.on('submit', (event) => { event.preventDefault(); load(1, invalidate()); });
    $form.on('input', '[name="q"]', () => {
        const current = invalidate();
        // Invalidar al escribir evita que una respuesta vieja se muestre durante el debounce.
        timer = setTimeout(() => load(1, current), 300);
    });
    $form.on('change', 'select, input[type="date"]', () => load(1, invalidate()));
    $('[data-catalog-clear]').on('click', (event) => {
        event.preventDefault();
        $form.find('input').val('');
        $form.find('[name="category"]').val('');
        $form.find('[name="availability"]').val('all');
        $form.find('[name="state"]').val('upcoming');
        load(1, invalidate());
    });
    $results.on('click', '[data-catalog-page]', (event) => {
        event.preventDefault();
        const target = $(event.currentTarget);
        const page = Number(target.attr('data-catalog-page'));
        load(page, invalidate());
        $feedback.attr('tabindex', '-1').trigger('focus');
    });
});
