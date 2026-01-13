jQuery(document).ready(function($) {
    var postIdToDelete = null;

    $('body').on('click', '.fred-delete-icon', function(e) {
        e.preventDefault();
        postIdToDelete = $(this).data('post-id');
        $('#fred-delete-popup').show();
    });

    $('body').on('click', '.fred-cancel-delete', function() {
        $('#fred-delete-popup').hide();
        postIdToDelete = null;
    });

    $('body').on('click', '.fred-confirm-delete', function() {
        if (postIdToDelete) {
            var $this = $(this);
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_my_post',
                    post_id: postIdToDelete
                },
                success: function(response) {
                    if (response.success) {
                        var postCard = $('#post-' + postIdToDelete);
                        postCard.fadeOut('fast', function() {
                            postCard.remove();
							
                            $('#fred-delete-popup').hide();

                            postIdToDelete = null;
                        });
                    } else {
                        console.error('Error deleting post:', response.data);

                        $('#fred-delete-popup').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown, jqXHR.responseText);

                    $('#fred-delete-popup').hide();
                }
            });
        }
    });
});