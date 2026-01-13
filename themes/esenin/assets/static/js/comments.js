jQuery(function($) {

    // --- РЕДАКТИРОВАНИЕ ---

    // 1. Клик "Редактировать"
    $(document).on('click', '.edit-comment-btn', function(e){
        e.preventDefault();
        const commentId = $(this).data('comment-id');
        
        // Скрываем другие открытые формы, чтобы не было бардака
        $('.edit-comment-form').not('#edit-comment-' + commentId).hide();
        
        // Показываем/скрываем текущую
        $('#edit-comment-' + commentId).toggle();
    });

    // 2. Ввод текста (появление кнопки Сохранить)
    $(document).on('input', '.edit-comment-text', function() {
        // Берем оригинал ИЗ САМОГО ПОЛЯ, а не из кнопки
        const originalContent = $(this).data('original-content'); 
        const currentContent = $(this).val();

        const saveBtn = $(this).closest('.edit-comment-form').find('.save-comment-btn');

        if (currentContent !== originalContent) {
            saveBtn.show();
        } else {
            saveBtn.hide();
        }
    });

    // 3. Клик "Сохранить"
    $(document).on('click', '.save-comment-btn', function(e){
        e.preventDefault();

        const commentId = $(this).data('comment-id');
        const textarea = $('#edit-comment-' + commentId + ' .edit-comment-text');
        const commentContent = textarea.val();
        const errorContainer = $('#edit-comment-' + commentId + ' .edit-comment-error');
        const editForm = $('#edit-comment-' + commentId);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_comment',
                comment_id: commentId,
                comment_content: commentContent
            },
            success: function(response) {
                if(response.success) {
                    // Обновляем текст на странице
                    $('#comment-' + commentId + ' .comment-text').html(commentContent);
                    
                    // Обновляем "оригинал" в data-атрибуте, чтобы при отмене возвращался уже новый текст
                    textarea.data('original-content', commentContent);
                    
                    editForm.hide();
                    errorContainer.hide();
                } else {
                    errorContainer.html(response.data.message).show();
                    setTimeout(function() { errorContainer.fadeOut(); }, 5000);
                }
            },
            error: function() {
                // Тихая ошибка или алерт, если сервер упал
                // alert('Ошибка соединения'); 
            }
        });
    });

    // 4. Клик "Отмена" (ИСПРАВЛЕНО)
    $(document).on('click', '.cancel-edit-btn', function(e){
        e.preventDefault();
        
        const form = $(this).closest('.edit-comment-form');
        const textarea = form.find('.edit-comment-text');
        const errorContainer = form.find('.edit-comment-error');
        const saveBtn = form.find('.save-comment-btn');

        // Скрываем ошибки и кнопку сохранения
        errorContainer.hide();
        saveBtn.hide();
        
        // Возвращаем текст из data-атрибута ТЕКСТАРЕИ, а не кнопки
        textarea.val(textarea.data('original-content'));
        
        // Скрываем форму
        form.hide();
    });


    // --- УДАЛЕНИЕ ---

    let deletedComments = [];

    $(document).on('click', '.delete-comment-btn', function(e){
        e.preventDefault();
        
        const commentId = $(this).data('comment-id');

        if(!confirm('Точно сносим этот коммент?')) return;

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_comment',
                comment_id: commentId
            },
            success: function(response) {
                if(response.success) {
                    $('#comment-' + commentId + ' .comment-text').html('Комментарий удалён.');
                    $('#div-' + commentId + ' .reply').remove();
                    deletedComments.push(commentId);
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    // Восстановление удаленных (только при обычной загрузке)
    $(window).on('load', function() {
        if(deletedComments.length > 0) {
            deletedComments.forEach(function(id) {
                $('#comment-' + id).addClass('deleted-comment');
                $('#comment-' + id + ' .comment-text').text('Комментарий удалён.');
                $('#div-' + id + ' .reply').remove();
            });
        }
    });


    // --- ОТВЕТЫ ---

    $(document).on('click', '.comment-reply-link', function(e) {
        e.preventDefault();

        const commentId = $(this).data('commentid');
        const replyForm = $('#respond');

        replyForm.hide();
        replyForm.insertAfter($('#div-' + commentId));
        replyForm.show();

        $('#reply-title').text('Ответить');
        $('#respond').find('button[type="submit"]').text('Добавить');
        $('.es-cancel-reply-button').show();

        $('html, body').animate({
            scrollTop: replyForm.offset().top - 100
        }, 500);
    });

    $(document).on('click', '.es-cancel-reply-button', function(e) {
        e.preventDefault();

        const replyForm = $('#respond');
        replyForm.hide();
        
        // Очистка формы
        replyForm.find('textarea').val(''); 
        replyForm.find('input[type="text"]').val(''); 
        replyForm.find('input[type="email"]').val(''); 

        // Возврат на место
        replyForm.appendTo('#comments'); 
        replyForm.show(); 

        $('#reply-title').text('Оставить комментарий');
        replyForm.find('button[type="submit"]').text('Отправить');
        $('.es-cancel-reply-button').hide();
    });

    // --- ХОВЕРЫ ---

    $(document).on('mouseenter', '.reply-arrow', function() {
        var parentId = $(this).data('parent-id');
        $('#div-' + parentId).addClass('es-parent-comm');
    });

    $(document).on('mouseleave', '.reply-arrow', function() {
        var parentId = $(this).data('parent-id');
        $('#div-' + parentId).removeClass('es-parent-comm');
    });

});