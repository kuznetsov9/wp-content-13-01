<?php
/**
 * Editor setting and Form 
 * 
 */

if (!defined('ABSPATH')) exit;

$post_edit['id'] = -1; 
$post_edit['author'] = get_current_user_id();
$post_edit['post_cat'] = 1;
$post_edit['post_title'] = "";
$post_edit['post_content'] = "";


if(isset($_GET['fred_id'])&&isset($_GET['fred'])&&$_GET['fred']=='edit'){
	$current_post_id = $_GET['fred_id'];
}

if (isset($fred_post_save_res['id'])){
	$current_post_id = $fred_post_save_res['id'];
}

$base_page = get_option('fred_page_shortcode');

if(isset($current_post_id)&&$current_post_id!=$base_page){

	$tmp_current_user = $post_edit['author'];

	$post_edit['id'] = $current_post_id;
	$tmp_post = get_post( $post_edit['id'], 'ARRAY_A' );
	$post_edit['author'] = $tmp_post['post_author'];

	if(current_user_can('edit_others_posts')||$post_edit['author']==$tmp_current_user){

		$tmp_cat_array = get_the_category($post_edit['id']);
				  
                $post_edit['post_cat'] = $tmp_cat_array[0]->term_id;
        				
		$post_edit['post_title'] = $tmp_post['post_title'];
		$post_edit['post_content'] = $tmp_post['post_content'];

	} else {
		$post_edit['id'] = -1; 
		$post_edit['author'] = get_current_user_id();	
	}	
}
?>

<div id="new-post-editor">
 <form action="" method="post" id="create-post">	
		<?php wp_nonce_field(); ?>
		<input type="hidden" name="fred_post_save" value="post_save">
		<input type="hidden" name="post_id" value="<?php echo $post_edit['id'];?>">
		<input type="hidden" name="post_author" value="<?php echo $post_edit['author'];?>">

					<?php 
					// Add Themes (categories)
					require (FRONT_EDITORJS_PLUGIN_DIR.'/view/station/add-themes.php'); ?>

	<div class='post-editor post-main'>
		<input type="text" class="editor editor-post editor-post-input editor-post-input-title" name="post_title" placeholder="<?php _e( 'Заголовок', 'front-editorjs' ); ?>" value="<?php echo $post_edit['post_title'];?>" autocomplete="off" required>		 
		<textarea name="post_content" id="cont" cols="30" rows="10" autocomplete="off"><?php echo esc_html($post_edit['post_content']);?></textarea>
	
	</div>

<div class="fred-tabs">
        
        <div class="fred-tab-panels">

                <div id="editor"></div>
 					
					<!-- Чекбокс для отключения обсуждений -->
					<div class="comments-toggle esn-checkbox">
                       <input type="checkbox" name="comments_disable" id="comments_disable" value="1" <?php checked('1', get_post_meta($post_edit['id'], '_comments_disable', true)); ?> />
                         <label for="comments_disable"><span><?php _e( 'Отключить обсуждения', 'front-editorjs' ); ?></span></label>
                    </div>
					
					<!-- Чекбокс для контента NSFW -->
                    <div class="nsfw-toggle esn-checkbox">
                       <input type="checkbox" name="nsfw_content" id="nsfw_content" value="1" <?php checked('1', get_post_meta($post_edit['id'], '_nsfw_content', true)); ?> />
                          <label for="nsfw_content"><span><?php _e( 'Контент для взрослых (NSFW)', 'front-editorjs' ); ?></span></label>
                    </div>
					
					<!-- Чекбокс для скрытого поста -->
                    <div class="hidden-post-toggle esn-checkbox">
                       <input type="checkbox" name="hidden_post" id="hidden_post" value="1" <?php checked('1', get_post_meta($post_edit['id'], '_hidden_post', true)); ?> />
                         <label for="hidden_post"><span><?php _e('Скрытый пост (для подписчиков)', 'front-editorjs'); ?></span></label>
                    </div>
            </div>

      </div>
    		

 </form>

  <div class="editor-footer-block">
  <?php if (isset($fred_post_save_res['message'])) {
    echo '<div id="fred-notice" class="fred-notice-success">' . esc_html($fred_post_save_res['message']) . '</div>'; 
    ?>
    <script type="text/javascript">
        setTimeout(function() {
            var notice = document.getElementById('fred-notice');
            if (notice) {
                notice.style.display = 'none';
            }
        }, 5000);
    </script>
    <?php } ?>
  
    <div class="editor-button editor-submit-button">
	  <input type="submit" form="create-post" class="post-submit post-editor-button" value="<?php _e( 'Опубликовать', 'front-editorjs' ); ?>">
    </div>
  </div>
  
</div>

