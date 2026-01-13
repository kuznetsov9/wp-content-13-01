<?php
/**
 * The template for displaying comments
 * @package Esenin
 */

do_action( 'esn_comments_before' );

$comments_number = get_comments_number();
$head_comm_text  = ( $comments_number > 0 ) 
    ? num_decline( $comments_number, 'комментарий,комментария,комментариев', 'esenin' ) 
    : __( 'Начать дискуссию', 'esenin' );

$is_user_logged = is_user_logged_in();
?>

<div class="es-entry__comments" id="comments" data-post-id="<?php echo get_the_ID(); ?>">

    <div class="es-entry__comments-header">
        <div class="es-entry__comments-header-top">
            <span class="es-entry__comments_head"><?php echo esc_html( $head_comm_text ); ?></span>
            <?php if ( have_comments() ) : ?>
                <div class="comments-sorting-wrapper">
                    <div class="sort-trigger">
                        <span class="current-sort-label">Популярные</span>
                        <i class="es-icon es-icon-chevron-down"></i>
                    </div>
                    <ul class="sort-options">
                        <li data-sort="popular" class="active">Популярные</li>
                        <li data-sort="newest">Свежее</li>
                        <li data-sort="oldest">Хронология</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="es-entry__comments-inner">
        <?php
        // 1. Если залогинен — даем обычную кнопку
        if ( $is_user_logged ) {
            $submit_button = '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />' . esc_html__( 'Отправить', 'esenin' ) . '</button>';
        } else {
            // 2. Если гость — подсовываем ссылку, которая прикидывается кнопкой
            // Мы убираем %3$s (дефолтные классы WP), чтобы они не конфликтовали с твоими
            $submit_button = '<a href="#login" class="login-modal-open es-button esn-modal-open" style="cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">' . esc_html__( 'Отправить', 'esenin' ) . '</a>';
        }

        add_filter( 'option_comment_registration', '__return_zero' );

        comment_form(
            array(
                'title_reply'        => esc_html__( 'Оставить комментарий', 'esenin' ),
                'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title" style="display:none">',
                'title_reply_after'  => '</h2>',
                'submit_button'      => $submit_button, // Вот тут наша подмена
                'submit_field'       => '<p class="form-submit d-flex align-items-center justify-content-end gap-2">%1$s %2$s <button class="es-cancel-reply-button es-btn-dark" style="display:none;">' . esc_html__( 'Отменить', 'esenin' ) . '</button></p>',
            )
        );

        remove_filter( 'option_comment_registration', '__return_zero' );
        ?>

        <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
            <p class="no-comments"><?php esc_html_e( 'Комментарии закрыты.', 'esenin' ); ?></p>
        <?php endif; ?>

        <?php if ( have_comments() ) : ?>
            <ol class="comment-list">
                <?php
                wp_list_comments( array(
                    'callback'    => 'esn_comments_callback',
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 34,
                ) );
                ?>
            </ol>
            
            <?php 
            $total_pages = get_comment_pages_count();
            $btn_style = ( $total_pages > 1 ) ? '' : 'display:none;';
            ?>
            <div class="es-load-more-wrap" style="text-align:center; margin: 30px 0; <?php echo $btn_style; ?>">
                <button id="esn-load-more-btn" class="es-btn es-btn-outline" data-page="1">Загрузить еще</button>
                <span id="esn-loader" style="display:none; color:#888;">Загрузка...</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php do_action( 'esn_comments_after' ); ?>