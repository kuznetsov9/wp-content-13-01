<?php
/**
 * Ajax search (posts) via WP REST API.
 * Оптимизировано: Debounce, REST Cache friendly, Single Query.
 * * @package Esenin
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 1. Регистрация маршрута поиска
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'esn/v1', '/search', array(
        'methods'             => 'GET',
        'callback'            => 'esn_rest_search_handler',
        'permission_callback' => '__return_true', // Поиск доступен всем
    ) );
} );

/**
 * 2. Обработчик поиска
 */
function esn_rest_search_handler( $request ) {
    $term = sanitize_text_field( $request->get_param( 'term' ) );

    if ( empty( $term ) || mb_strlen( $term ) < 3 ) {
        return new WP_Error( 'short_term', 'Слишком короткий запрос', array( 'status' => 400 ) );
    }

    $args = array(
        'post_type'      => array( 'post' ),
        's'              => $term,
        'posts_per_page' => 10,
        'post_status'    => 'publish',
        'order'          => 'DESC',
        'orderby'        => 'date',
        // Ускоряем поиск: не считаем общее кол-во страниц, нам нужны только первые 10
        'no_found_rows'  => true, 
    );

    $the_query = new WP_Query( $args );
    $html = '';

    if ( $the_query->have_posts() ) {
        ob_start();
        while ( $the_query->have_posts() ) {
            $the_query->the_post(); ?>
            <a class="result_item" href="<?php the_permalink(); ?>">        
                <div class="result_item_post_thumbnail">
                    <?php if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'thumbnail', array( 'class' => 'post_thumbnail' ) );
                    } else { ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/search/noimage.png" />
                    <?php } ?>
                </div>
                <div class="result_item_post_title">
                    <div class="es-text-clamp-2"><?php the_title(); ?></div>
                </div>
            </a>        
            <?php
        }
        $html = ob_get_clean();
    } else {
        $html = '<div class="not_found"><span>' . esc_html__( 'Ничего не найдено', 'esenin' ) . '</span></div>';
    }

    wp_reset_postdata();

    return array(
        'success' => true,
        'html'    => $html
    );
}

/**
 * 3. Фронтенд логика (Debounced Fetch)
 */
add_action( 'wp_footer', 'esn_ajax_search_rest_js' );
function esn_ajax_search_rest_js() {
    // Проверяем, чтобы данные темы были доступны (nonce берем из нашей общей структуры)
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.querySelector('.es-search__input');
        const list = document.querySelector('.result-search .result-search-list');
        const preloader = document.querySelector('.result-search .preloader');
        const wrapper = document.querySelector('.result-search');
        
        let timeout = null;

        if (!input) return;

        input.addEventListener('input', function() {
            const searchTerm = input.value.trim();

            // Очищаем предыдущий таймер (Debounce)
            clearTimeout(timeout);

            if (searchTerm.length < 4) {
                if (list) list.innerHTML = '';
                if (wrapper) wrapper.style.display = 'none';
                return;
            }

            // Устанавливаем задержку 400мс перед отправкой
            timeout = setTimeout(() => {
                if (preloader) preloader.style.display = 'block';
                if (wrapper) wrapper.style.display = 'block';

                // Используем GET вместо POST для возможности кеширования
                fetch(`/wp-json/esn/v1/search?term=${encodeURIComponent(searchTerm)}`)
                .then(res => res.json())
                .then(data => {
                    if (preloader) preloader.style.display = 'none';
                    if (data.html) {
                        list.innerHTML = data.html;
                        list.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Search error:', err);
                    if (preloader) preloader.style.display = 'none';
                });
            }, 400); 
        });

        // Показ при фокусе
        input.addEventListener('focus', () => {
            if (input.value.length > 3) {
                if (wrapper) wrapper.style.display = 'block';
            }
        });

        // Закрытие при клике мимо
        document.addEventListener('mouseup', function(e) {
            if (wrapper && !wrapper.contains(e.target) && !input.contains(e.target)) {
                wrapper.style.display = 'none';
            }
        });
    });
    </script>
    <?php
}