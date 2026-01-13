<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "es-site" div.
 *
 * @package Esenin
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>	
	<script>
  (function() {
    // 1. Пытаемся достать сохраненную тему (подставь свой ключ из localStorage)
    const savedTheme = localStorage.getItem('theme'); 
    // 2. Проверяем системную тему, если юзер еще ничего не выбрал
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    // 3. Логика определения цвета
    let bgColor = '#ffffff'; // Дефолт для светлой
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
      bgColor = '#121212'; // Твой цвет для тёмной
      document.documentElement.classList.add('dark'); // Добавляем класс сразу
    }
    // 4. Гвоздь программы: принудительно красим html еще до рендеринга
    document.documentElement.style.backgroundColor = bgColor;
  })();
</script>

</head>
<body <?php body_class(); ?> <?php esn_site_scheme(); ?>>
	

<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>
<?php
/**
 * The esn_site_before hook.
 *
 * @since 1.0.0
 */
do_action( 'esn_site_before' );
?>
<div id="page" class="es-site">

<div id="loading-bar"></div>

	<?php
	/**
	 * The esn_site_start hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_site_start' );
	?>
	<div class="es-site-inner">
		<?php
		/**
		 * The esn_header_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_header_before' );
		?>
		<?php get_template_part( 'template-parts/header' ); ?>
	  <div class="layout d-block d-lg-grid">
	    <?php get_template_part( 'sidebar-left' ); 
		/**
		 * The esn_header_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_header_after' );
		?>	 	
		<main id="main" class="es-site-primary ">			
			<?php
			/**
			 * The esn_site_content_before hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_site_content_before' );
			?>
			<div <?php esn_site_content_class(); ?>>
				<?php
				/**
				 * The esn_site_content_start hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'esn_site_content_start' );
				?> 
				<div class="es-container">				
					<?php
					/**
					 * The esn_main_content_before hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'esn_main_content_before' );
					?>
					<div id="content" class="es-main-content">
						<?php
						/**
						 * The esn_main_content_start hook.
						 *
						 * @since 1.0.0
						 */
						do_action( 'esn_main_content_start' ); ?>			
						