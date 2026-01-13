jQuery(document).ready(function($) {
    $(document).on('click', '.es-bookmark-button', function() {
        var button = $(this);
        var postId = button.data('post-id');

        $.ajax({
            url: esen_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'esen_add_remove_bookmark',
                post_id: postId,
            },
            success: function(response) {
                if (response.success) {
                    button.html(response.data.icon);
                    var newTitle = response.data.action === 'added' ? 'Удалить из закладок' : 'Добавить в закладки';
                    button.attr('title', newTitle);
                } else if (response.data.message === 'not_logged_in') {
                    window.location.href = "#login";
                } else {
                    alert(response.data.message);
                }
            },
            error: function() {
                alert('Произошла ошибка с запросом. Попробуйте снова.');
            }
        });
    });
});