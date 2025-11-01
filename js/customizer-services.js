(function (api, $, data) {
    'use strict';

    if (!api || !$) {
        return;
    }

    data = data || {};

    function getMessage(template, serviceId) {
        if (!template) {
            return '';
        }

        if (-1 !== template.indexOf('%s')) {
            return template.replace('%s', serviceId);
        }

        return template;
    }

    function notify(message, type) {
        if (!api.notifications || !message) {
            if (message) {
                window.alert(message);
            }
            return;
        }

        var code = 'callamir-service-' + Date.now() + '-' + Math.random().toString(36).slice(2);
        api.notifications.add(code, new api.Notification(type || 'info', message));
    }

    $(document).on('click', '.callamir-delete-service', function (event) {
        event.preventDefault();

        var $button = $(this);
        var serviceId = parseInt($button.data('service-id'), 10);

        if (!serviceId) {
            return;
        }

        var confirmMessage = getMessage(data.confirmDelete, serviceId);
        if (confirmMessage && !window.confirm(confirmMessage)) {
            return;
        }

        $button.prop('disabled', true).addClass('is-busy');

        $.ajax({
            url: data.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'callamir_delete_service',
                service_id: serviceId,
                nonce: data.nonce
            }
        }).done(function (response) {
            if (response && response.success) {
                if (response.data && typeof response.data.new_count !== 'undefined' && api('callamir_services_count')) {
                    api('callamir_services_count').set(response.data.new_count);
                }

                if (api.previewer) {
                    api.previewer.refresh();
                }

                var successMessage = response.data && response.data.message ? response.data.message : data.success;
                if (successMessage) {
                    notify(successMessage, 'success');
                }
            } else {
                var errorMessage = data.error;
                if (response && response.data && response.data.message) {
                    errorMessage = response.data.message;
                }
                notify(errorMessage, 'error');
            }
        }).fail(function () {
            notify(data.error, 'error');
        }).always(function () {
            $button.prop('disabled', false).removeClass('is-busy');
        });
    });
})(window.wp && window.wp.customize, window.jQuery, window.callamirServiceManager);
