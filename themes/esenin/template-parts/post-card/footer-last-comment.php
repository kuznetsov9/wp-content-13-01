<?php
/**
 * @version 1.0
 */
 $comments_number = get_comments_number($post->ID);
 $comments = get_comments(array(
            'post_id' => $post->ID,
			'number'  => 1, 		
			'status' => 'approve',
		)); 
 	
?>

<?php if ($comments_number == 0) { ?>
    <div class="d-none">
	   <?php esc_html_e('Оставьте комментарий', 'esenin'); ?>
    </div>
<?php } else { ?>
   
	<?php foreach($comments as $comment){ 
	$is_deleted = get_comment_meta($comment->comment_ID, 'deleted', true); ?> 	
      <a href="<?php comment_link(); ?>" rel="nofollow">	
	    <div class="pc-footer-comments-block d-flex align-items-center">
	      <div class="flex-shrink-0 pc-footer-comment-avatar">
		   <?php echo get_avatar( $comment ); ?>
		  </div>		  		  
	      <?php if ($is_deleted) { ?>
                      <div class="flex-grow-1 ms-2 pc-footer-comment-content"><?php esc_html_e('Комментарий удалён..', 'esenin'); ?> 😒</div>
                    <?php } else { ?>
                      <div class="flex-grow-1 ms-2 pc-footer-comment-content"><?php echo esc_html($comment->comment_content); ?></div>
		  <?php } ?>
		</div>
	  </a>
    <?php } ?>
	
<?php } 

