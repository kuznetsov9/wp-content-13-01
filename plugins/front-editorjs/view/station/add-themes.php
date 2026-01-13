<?php
defined('ABSPATH') || exit; 
?>

<?php
if ( ! function_exists( 'esn_custom_category_dropdown' ) ) {
function esn_custom_category_dropdown( $args = array() ) {
    $defaults = array(
        'selected' => 0,
        'name'     => 'post_category',
        'id'       => 'custom-categories', 
        'class'    => 'esn-custom-select', 
        'show_empty' => 1,
        'hierarchical' => 1,
    );
    $args = wp_parse_args( $args, $defaults );

    $categories = get_categories( array(
        'hide_empty' => ! $args['show_empty'],
        'hierarchical' => $args['hierarchical'],
    ) );

    $name     = esc_attr( $args['name'] );
    $id       = esc_attr( $args['id'] );
    $class    = esc_attr( $args['class'] );
    $selected = absint( $args['selected'] );
	$default_category_id = get_option('default_category');


    echo "<div class='{$class}' id='{$id}'>";
	$data_value = ($selected == 0) && !empty($categories) && $default_category_id ? $selected : $default_category_id;
    echo "<div class='esn-custom-select-selected' data-value='{$data_value}'>";
    echo "<span class='esn-custom-select-selected-text'>";
    if ($selected > 0) {
        $selected_category = get_category($selected);
        if ($selected_category) {
            $esn_category_logo = get_term_meta( $selected_category->term_id, 'esn_category_logo', true );
            $logo_html = '';
            if ( ! empty( $esn_category_logo ) ) {
                $logo_html = esn_get_retina_image(
                    $esn_category_logo,
                    array(
                        'alt'   => esc_attr( $selected_category->name ),
                        'title' => esc_attr( $selected_category->name ),
                        'style' => 'margin-right: 6px; margin-left: -6px; box-shadow: none;',
						'class' => 'esn-category-logo',
                    )
                );
            }
            echo $logo_html . esc_html( $selected_category->name );
        } else {
            echo esc_html__( 'Выберите тему', 'front-editorjs' );			
        }
    } else {
        echo esc_html__( 'Нет доступных тем', 'front-editorjs' );
    }
    echo "</span>";
    echo "</div>";
    echo "<ul class='esn-custom-select-options'>";
	echo "<div class='esn-custom-select-scroll'>";
    esn_walk_category_dropdown( $categories, 0, $selected );
	echo "</div>";
    echo "</ul>";
    echo "<input type='hidden' name='{$name}' value='{$data_value}' id='{$name}'>";	
    echo "</div>";
}
}

if ( ! function_exists( 'esn_walk_category_dropdown' ) ) {
function esn_walk_category_dropdown( $categories, $parent_id = 0, $selected = 0, $depth = 0 ) {
    foreach ( $categories as $category ) {
        if ( $category->category_parent == $parent_id ) {
            $indent = str_repeat( '&nbsp;&nbsp;&nbsp;', $depth );
			$is_selected = ($selected == $category->term_id) ? 'selected' : '';
            echo "<li class='esn-custom-select-option {$is_selected}' data-value='" . esc_attr( $category->term_id ) . "'>";
			$esn_category_logo = get_term_meta( $category->term_id, 'esn_category_logo', true );
            $logo_html = '';
            if ( ! empty( $esn_category_logo ) ) {
                $logo_html = esn_get_retina_image(
                    $esn_category_logo,
                    array(
                        'alt'   => esc_attr( $category->name ),
                        'title' => esc_attr( $category->name ),
						'class' => 'esn-category-logo',
                    )
                );
            }
            
            
            echo "<span class='esn-category-name'>{$indent}{$logo_html}" . esc_html( $category->name ) . "</span>";
            echo "</li>";
            esn_walk_category_dropdown( $categories, $category->term_id, $selected, $depth + 1 );
        }
    }
}
}
?>

<?php
esn_custom_category_dropdown(array(
    'selected' => $post_edit['post_cat'],
    'name'     => 'post_category',
    'id'       => 'custom-categories',
    'class'    => 'esn-custom-select', 
));
?>
<style>
.esn-custom-select-options {
	display: none; 
}
.esn-custom-select-option .esn-category-logo, 
.esn-custom-select-selected .esn-category-logo {
	width: 28px; 
	height: 28px; 
	border-radius: 50%;
	box-shadow: 0 1px 3px 0 rgb(0 0 0 / .2), 0 1px 2px -1px rgb(0 0 0 / .2);
}
</style>