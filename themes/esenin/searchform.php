<?php
/**
 * The template for displaying search form.
 *
 * @package Esenin
 */

?>
<form role="search" method="get" class="es-search__form es-form-box" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="es-search__group es-form-group">
		<input required class="es-search__input" type="search" value="<?php the_search_query(); ?>" name="s" placeholder="<?php echo esc_attr__( 'Поиск', 'esenin' ); ?>" role="searchbox">

		<button class="es-search__submit" aria-label="Search" type="submit">
			<?php echo esc_html__( 'Найти', 'esenin' ); ?>
		</button>
	</div>
</form>
