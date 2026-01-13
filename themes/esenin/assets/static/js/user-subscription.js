jQuery(document).ready(function ($) {
    $('.usp-button').on('click', function () {
        var button = $(this);
        var userId = button.data('user-id');

        $.ajax({
            url: usp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'usp_subscribe',
                user_id: userId
            },
            success: function (response) {
                if (response.success) {
                    var action = response.data.action;
                    button.text(action === 'subscribe' ? 'Отписаться' : 'Подписаться');
                    button.toggleClass('unsubscribe subscribe');
                } else {
                    alert(response.data);
                }
            }
        });
    });
});