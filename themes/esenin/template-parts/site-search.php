<?php
/**
 * The template part for displaying site section.
 *
 * @package Esenin
 */
?>
<div class="es-search searh-header">		
  <div class="es-search__form-container">
   
   <form role="search" method="get" class="es-search__form es-form-box" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="es-search__group es-form-group d-flex align-items-center">
		<input id="s" autocomplete="off" class="es-search__input" type="search" value="" name="s" placeholder="<?php echo esc_attr__( 'Поиск', 'esenin' ); ?>">

		<button class="es-search__submit" aria-label="Search" type="submit">
			<?php echo esc_html__( 'Найти', 'esenin' ); ?>
		</button>
		
		 <span class="es-search__close" role="button" aria-label="<?php echo esc_attr__( 'Close search button', 'esenin' ); ?>">
			<i class="es-icon es-icon-x"></i>
	     </span>
	</div>
	
    </form>
	   
   <div class="result-search">
			<div class="preloader"><img src="<?php echo get_bloginfo('template_directory'); ?>/assets/img/search/loader.gif" class="loader" /></div>
			<div class="result-search-list"></div>
	</div>
   
  </div>
</div>
