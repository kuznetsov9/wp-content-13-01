<?php
/**
 * Plugin Name: Related Linking
 * Description: Блок похожей статьи в тексте для шаблона Esenin
 * Plugin URI: https://devster.ru
 * Version: 1.0
 * Author: Александр Хлиманков
 * Author URI: https://t.me/khlimankov
 * Text Domain: related-linking
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function esenin_get_related_post( $post_id ) {
    $post = get_post( $post_id );
    $title = $post->post_title;
    $categories = wp_get_post_categories( $post_id );

    $keywords = explode( ' ', $title );

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 1,
        'post__not_in' => array( $post_id ),
        'category__in' => $categories,
        'title_search' => $keywords,
        'orderby' => 'relevance',
        'ignore_sticky_posts' => 1,
    );

    add_filter( 'posts_where', 'esenin_title_search', 10, 2 );

    $related_posts = new WP_Query( $args );

    remove_filter( 'posts_where', 'esenin_title_search', 10, 2 );

    if ( $related_posts->have_posts() ) {
        $found_common_word = false;
        foreach ( $keywords as $keyword ) {
            foreach ( explode( ' ', $related_posts->posts[0]->post_title ) as $related_keyword ) {
                if ( strtolower( $keyword ) == strtolower( $related_keyword ) ) {
                    $found_common_word = true;
                    break 2; 
                }
            }
        }

        if ( $found_common_word ) {
            return $related_posts->posts[0];
        } else {
            return null;
        }
    } else {
        return null;
    }
}

function esenin_title_search( $where, $query ) {
    global $wpdb;
    if ( ! empty( $query->query_vars['title_search'] ) ) {
        $keywords = $query->query_vars['title_search'];
        $where .= ' AND (';
        $i = 0;
        foreach ( $keywords as $keyword ) {
            if ( $i > 0 ) {
                $where .= ' OR ';
            }
            $keyword = $wpdb->esc_like( $keyword );
            $where .= "$wpdb->posts.post_title LIKE '%$keyword%'";
            $i++;
        }
        $where .= ')';
    }
    return $where;
}

function esenin_display_related_post( $content ) {
    if ( is_singular( 'post' ) ) {
        $post_id = get_the_ID();
        $related_post = esenin_get_related_post( $post_id );

        if ( $related_post ) {
            $related_post_title = get_the_title( $related_post->ID );
            $related_post_permalink = get_permalink( $related_post->ID );
            $related_post_image = get_the_post_thumbnail_url( $related_post->ID, 'thumbnail' );

            $author_id = get_post_field( 'post_author', $related_post->ID );
            $author_name = get_the_author_meta( 'display_name', $author_id );
            $author_avatar = get_avatar( $author_id, 32 ); 

            // Related post block HTML
            $related_block = '<div class="esenin-related-block d-flex align-items-start">';
            $related_block .= '<a href="' . esc_url( $related_post_permalink ) . '" class="esenin-related-link d-flex w-100">';

            if ( $related_post_image ) {
                $related_block .= '<div class="col-8 position-relative">';
            } else {
                $related_block .= '<div class="col position-relative">';
            }

            // Title and author section
            $related_block .= '<div class="esenin-related-title" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2;">';
            $related_block .= esc_html( $related_post_title );
            $related_block .= '</div>';
            $related_block .= '<div class="esenin-related-author position-absolute" style="bottom: 0;">';
            $related_block .= $author_avatar . '<span>' . esc_html( $author_name ) . '</span>';
            $related_block .= '</div>';
            $related_block .= '</div>'; // Close the title and author section

            if ( $related_post_image ) {
                $related_block .= '<div class="esenin-related-image col-auto text-end">';
                $related_block .= '<img src="' . esc_url( $related_post_image ) . '" alt="' . esc_attr( $related_post_title ) . '" class="img-fluid">';
                $related_block .= '</div>';
            }

            $related_block .= '</a>'; // Close the link
            $related_block .= '</div>'; // Close the block

            $paragraphs = explode( '</p>', $content );
            if ( count( $paragraphs ) > 4 ) {
                $content = implode('</p>', array_slice($paragraphs, 0, 4)) . '</p>' . $related_block;
                for ( $i = 4; $i < count( $paragraphs ); $i++ ) {
                    $content .= $paragraphs[ $i ] . '</p>';
                }
            } else {
                $content .= $related_block;
            }
        }
    }
    return $content;
}

add_filter( 'the_content', 'esenin_display_related_post' );

// Enqueue styles
function esenin_enqueue_styles() {
    wp_enqueue_style( 'esenin-related-styles', plugin_dir_url( __FILE__ ) . 'style.css' ); 
}

add_action( 'wp_enqueue_scripts', 'esenin_enqueue_styles' );