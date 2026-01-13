<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=106236871', 'ym');

    ym(106236871, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/106236871" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?php
/**
 * The template for displaying the header layout
 *
 * @package Esenin
 */
?>


	

<div class="es-header-before"></div>
<header class="es-header es-header-stretch">


	<div class="es-container">
		<div class="es-header__inner">
			<div class="es-header__col es-col-left">
			  <div class="d-block d-lg-none">
				<?php esn_component( 'header_offcanvas_toggle', true, array( 'mobile' => true ) ); ?>
			  </div>
				<?php esn_component( 'header_logo' ); ?>
			</div>

			<?php if ( get_theme_mod( 'header_navigation_menu', false ) ) { ?>
				<div class="es-header__col es-col-center">
					<?php esn_component( 'header_nav_menu' ); ?>
				</div>
			<?php } ?>							
			<?php
			if (
				get_theme_mod( 'header_search_button', true ) ||
				get_theme_mod( 'color_scheme_toggle', true ) ||
				get_theme_mod( 'header_custom_button', false )
			) {
				?>
				<div class="es-header__col es-col-right">				
				   <?php 
                    esn_component( 'header_search_toggle' );  
                    ?>					 
                    <div class="d-none d-md-flex">
                    <?php 
                    esn_component( 'header_scheme_toggle' );
					esn_component( 'header_custom_button' ); 
                    ?>
                    </div>				
				<?php if( current_user_can('moderator') || current_user_can('editor') || current_user_can('administrator') || current_user_can('author') || current_user_can('contributor') || current_user_can('subscriber') ) {  ?>
				   				  				  			  
					    <?php echo do_shortcode('[esenin_notifications_bell]'); ?>
									
				<?php if (is_plugin_active('front-editorjs/front_editorjs.php')) { ?>	
				<div title="<?php esc_attr_e( 'Добавить пост', 'esenin' ); ?>" class="btn-addpost d-none d-md-flex">
	                <a href="/editor"> 
					  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                         <g>
	                   <path d="M480,224H288V32c0-17.673-14.327-32-32-32s-32,14.327-32,32v192H32c-17.673,0-32,14.327-32,32s14.327,32,32,32h192v192   c0,17.673,14.327,32,32,32s32-14.327,32-32V288h192c17.673,0,32-14.327,32-32S497.673,224,480,224z"/>
                        </g>
                      </svg>
					</a>
				 </div>
				 <?php } ?>
				 <?php } ?>																		                 				 	              
				 <div class="userblock">
	                <?php header_user_block(); ?>
				 </div>				
                </div>
			<?php } ?>								  				  		
		</div>       
		<?php esn_site_nav_mobile(); ?>
        <?php esn_site_search(); ?> 
	</div>	 
</header>
