'use strict';
jQuery(($) => {
    const $panel = $('[data-event-weather]');
    if (!$panel.length) return;
    const $message = $panel.find('[data-weather-message]');
    const $values = $panel.find('[data-weather-values]');
    const $button = $panel.find('[data-weather-retry]');
    const load = () => {
        $button.prop('disabled', true);
        $panel.attr('aria-busy', 'true');
        $message.text('Consultando el pronóstico para esta experiencia…');
        $.ajax({url: $panel.attr('data-event-weather'), dataType: 'json', timeout: 12000})
            .done((data) => {
                $values.empty();
                $message.text(data.message);
                if (data.forecast) {
                    const forecast = data.forecast;
                    const number = new Intl.NumberFormat('es-CR', {maximumFractionDigits: 1});
                    const date = new Intl.DateTimeFormat('es-CR', {timeZone: 'America/Costa_Rica', dateStyle: 'short', timeStyle: 'short'});
                    const label = forecast.source === 'met_norway' ? 'Temperaturas previstas para el día' : 'Temperatura diaria';
                    $('<p>').text(`${label}: ${number.format(forecast.minimum)} a ${number.format(forecast.maximum)} °C.`).appendTo($values);
                    if (typeof forecast.rain_probability === 'number') {
                        $('<p>').text(`Probabilidad máxima de precipitación del día: ${number.format(forecast.rain_probability)} %.`).appendTo($values);
                    }
                    if (forecast.source === 'met_norway') {
                        $('<p>').addClass('field-help').text('Datos de MET Norway. Rango de temperaturas pronosticadas en distintos momentos del día.').appendTo($values);
                    }
                    $('<p>').addClass('field-help').text(`Consultado: ${date.format(new Date(data.checked_at))} (Costa Rica). Pronóstico orientativo; puede cambiar.`).appendTo($values);
                }
                $button.prop('hidden', !['available', 'stale', 'unavailable', 'busy'].includes(data.status));
            })
            .fail(() => {
                $message.text('No pudimos consultar el pronóstico. Puede continuar con su reserva.');
                $values.empty();
                $button.prop('hidden', false);
            })
            .always(() => { $button.prop('disabled', false); $panel.removeAttr('aria-busy'); });
    };
    $button.on('click', load);
    load();
});
