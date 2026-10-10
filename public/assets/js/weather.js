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
                $message.text(data.status === 'available' ? '' : data.message);
                $panel.find('[data-weather-source], [data-weather-source-join]').prop('hidden', false);
                $panel.find('[data-weather-checked]').prop('hidden', true).empty();
                $panel.find('[data-weather-description]').text('Pronóstico orientativo; puede cambiar. Resumen adaptado por Eventify.');
                if (data.forecast) {
                    const forecast = data.forecast;
                    const number = new Intl.NumberFormat('es-CR', {maximumFractionDigits: 1});
                    const date = new Intl.DateTimeFormat('es-CR', {timeZone: 'America/Costa_Rica', dateStyle: 'short', timeStyle: 'short'});
                    const isBackup = forecast.source === 'met_norway';
                    const $temperature = $('<p>').addClass('weather-temperature').appendTo($values);
                    $('<strong>').text(`${number.format(forecast.minimum)} – ${number.format(forecast.maximum)} °C`).appendTo($temperature);
                    $('<span>').text(isBackup ? 'Rango de temperaturas previsto' : 'Temperatura mínima y máxima').appendTo($temperature);
                    if (typeof forecast.rain_probability === 'number') {
                        $('<p>').addClass('weather-rain').text(`Lluvia: hasta ${number.format(forecast.rain_probability)} %`).appendTo($values);
                    }
                    const description = isBackup
                        ? 'Rango de muestras pronosticadas en distintos momentos del día; no equivale a los extremos diarios.'
                        : 'Mínima y máxima del día. La lluvia indica la probabilidad máxima diaria de precipitación.';
                    $panel.find('[data-weather-description]').text(`${description} Pronóstico orientativo; puede cambiar. Resumen adaptado por Eventify.`);
                    $panel.find('[data-weather-checked]').text(`Consultado: ${date.format(new Date(data.checked_at))} (Costa Rica).`).prop('hidden', false);
                    $panel.find('[data-weather-source="open_meteo"]').prop('hidden', isBackup);
                    $panel.find('[data-weather-source="met_norway"]').prop('hidden', !isBackup);
                    $panel.find('[data-weather-source-join]').prop('hidden', true);
                }
                $button.prop('hidden', !['available', 'stale', 'unavailable', 'busy'].includes(data.status));
            })
            .fail(() => {
                $message.text('No pudimos consultar el pronóstico. Puede continuar con su reserva.');
                $values.empty();
                $panel.find('[data-weather-checked]').prop('hidden', true).empty();
                $panel.find('[data-weather-source], [data-weather-source-join]').prop('hidden', false);
                $panel.find('[data-weather-description]').text('Pronóstico orientativo; puede cambiar. Resumen adaptado por Eventify.');
                $button.prop('hidden', false);
            })
            .always(() => { $button.prop('disabled', false); $panel.removeAttr('aria-busy'); });
    };
    $button.on('click', load);
    load();
});
