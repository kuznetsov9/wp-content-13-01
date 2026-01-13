jQuery(document).ready(function($) {
    // Делегируем на документ, чтобы работало и в основной ленте, и в подгруженных постах
    $(document).on('click', '.cs-subscribe-button', function() {
        var $clickedBtn = $(this);
        var $wrapper = $clickedBtn.closest('.cs-subscription-container');
        var categoryId = $wrapper.data('category-id');

        // 1. Защита на фронте: если кнопка уже "думает", игнорим новые клики
        if ($clickedBtn.data('loading') === true) return;

        // 2. Визуальный фидбек и блокировка
        $clickedBtn.data('loading', true).css('opacity', '0.6');

        $.ajax({
            type: 'POST',
            url: cs_ajax.ajax_url,
            data: {
                action: 'cs_handle_subscription',
                category_id: categoryId,
            },
            success: function(response) {
                if (response.success) {
                    var action = response.data.action; // 'subscribed' или 'unsubscribed'
                    var isSub = (action === 'subscribed');
                    
                    // Что будем вставлять
                    var newText = isSub ? 'Отписаться' : 'Подписаться';
                    var newClass = isSub ? 'unsubscribe' : 'subscribe';
                    var oldClass = isSub ? 'subscribe' : 'unsubscribe';
                    var countText = response.data.subscribers_count + ' подписчиков';

                    // 3. МАГИЯ РАСПРОСТРАНЕНИЯ:
                    // Ищем вообще ВСЕ контейнеры этой категории на странице
                    $('.cs-subscription-container[data-category-id="' + categoryId + '"]').each(function() {
                        var $currentWrapper = $(this);
                        var $btn = $currentWrapper.find('.cs-subscribe-button');
                        
                        // Обновляем текст и классы
                        $btn.text(newText).removeClass(oldClass).addClass(newClass);
                        
                        // Обновляем счетчик подписчиков в этой карточке, если он там есть
                        $currentWrapper.find('.cs-subscribers-count').text(countText);
                        
                        // Снимаем блокировку со всех кнопок этой категории
                        $btn.data('loading', false).css('opacity', '1');
                    });

                } else {
                    // Если сервер вернул ошибку (например, спам-фильтр)
                    alert(response.data);
                    $clickedBtn.data('loading', false).css('opacity', '1');
                }
            },
            error: function() {
                // Если сервак вообще прилег
                $clickedBtn.data('loading', false).css('opacity', '1');
            }
        });
    });
});