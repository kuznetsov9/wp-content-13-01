<?php
/**
 * Template name: Мои подписки
 *
 * Page with my subscriptions
 *
 * @package Esenin
 */

global $user_ID;
global $current_user, $wp_roles;
wp_get_current_user();
 
if( !$user_ID ) {
	header('location:' . site_url() . '/#login');
	exit;
} else {
	$userdata = get_user_by( 'id', $user_ID );
}

global $wpdb;
$user_id = get_current_user_id();
$subscriber_id = get_current_user_id();
$subscribed_categories = $wpdb->get_col($wpdb->prepare("
    SELECT category_id FROM {$wpdb->prefix}category_subscriptions
    WHERE user_id = %d
", $user_id));

$table_name = $wpdb->prefix . 'user_subscriptions';
$subscribed_users = $wpdb->get_results($wpdb->prepare(
    "SELECT user_id FROM $table_name WHERE subscriber_id = %d",
    $subscriber_id
));

get_header();
?>

<div id="primary" class="my-subscriptions edit-profile es-content-area">
 <div class="es-page-mw">
	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>
	
	
	
	
	
<ul class="nav nav-underline" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <div class="nav-link active" id="pills-sub-users-tab" data-bs-toggle="pill" data-bs-target="#pills-sub-users" type="button" role="tab" aria-controls="pills-sub-users" aria-selected="false"><?php echo esc_attr__( 'Пользователи', 'esenin' ); ?></div>
  </li>
   <li class="nav-item" role="presentation">
    <div class="nav-link" id="pills-sub-themes-tab" data-bs-toggle="pill" data-bs-target="#pills-sub-themes" type="button" role="tab" aria-controls="pills-sub-themes" aria-selected="false"><?php echo esc_attr__( 'Темы', 'esenin' ); ?></div>
  </li>
</ul>
<div class="tab-body pb-3">
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-sub-users" role="tabpanel" aria-labelledby="pills-sub-users-tab">
  
  
  <?php
if ($subscribed_users) {
    $users_data = array();
    foreach ($subscribed_users as $subscribed_user) {
        $user_info = get_userdata($subscribed_user->user_id);
		
		
        if ($user_info) {
            $users_data[] = array(
                'ID' => $user_info->ID,
                'user_email' => $user_info->user_email,
                'display_name' => $user_info->display_name,
            );
        }
    }

   
    if (!empty($users_data)) { ?>
      <div class="es-page-themes-list"> 
      <div class="es-themes-list__wrapper p-0">
       <?php foreach ($users_data as $user) { 
	    $num_posts_user = count_user_posts( $user['ID']);
	    $author_id = $user['ID']; 
	   ?>
          
		  
		 <div class="d-flex align-items-center justify-content-between m-0 es-theme-item">
                    <div class=" d-flex align-items-center">
							
								
									<div class="es-theme-item__logo me-2 flex-shrink-0">
									<a href="<?php echo get_author_posts_url($user['ID']); ?>">
									<?php echo get_avatar( $user['ID'], 100, '', '', array('class' => 'rounded-circle') ); ?> 
									</a>
									</div>
								
							
														
							<div class="flex-grow-1">
                               <a href="<?php echo get_author_posts_url($user['ID']); ?>" class="es-theme-item_name">
				                  <?php echo $user['display_name']; ?>
			                   </a>
				                <span class="es-theme-item_count gap-3 fw-normal d-none d-md-flex">
				                  <?php echo num_decline( $num_posts_user, 'статья,статьи,статей', 'esenin' ); ?>
								  <?php echo do_shortcode('[subscribers_count user_id="' . $author_id . '"]'); ?>
				                </span>
								
		                    </div>
							
					</div>
                  <div class="es-theme-item_right d-flex align-items-center">
                      <div class="theme-subscribe">					  
					  <?php echo do_shortcode('[subscription_button user_id="' . $author_id . '"]'); ?>
					  </div>
                  </div>
         </div>	
       
	   
	   
	   
	   <?php } ?>
    </div>
    </div>	

  
  <?php } ?>
  <?php } else { ?>
   <div class="pb-3 fs-6 d-flex justify-content-center align-items-center">
     <?php echo esc_html__( 'Подписок на пользователей нет..', 'esenin' ); ?>
   </div>
  <?php } ?> 
  
  
  
  </div>
  
  
  <div class="tab-pane fade" id="pills-sub-themes" role="tabpanel" aria-labelledby="pills-sub-themes-tab">
   
<?php
if (!empty($subscribed_categories)) {
    $categories = get_categories(array(
        'include' => $subscribed_categories,
        'hide_empty' => false,
    ));
if ($categories) { ?>   
  <div class="es-page-themes-list"> 
   <div class="es-themes-list__wrapper p-0">
   <?php  foreach ($categories as $category) { 
      $esn_category_logo = get_term_meta( $category->term_id, 'esn_category_logo', true );
	  $categoty_id = $category->term_id;
   ?>  
         <div class="d-flex align-items-center justify-content-between m-0 es-theme-item">
                    <div class=" d-flex align-items-center">
							<?php if ( $esn_category_logo ) { ?>
								
									<div class="es-theme-item__logo me-2 flex-shrink-0">
									<a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>">
									<?php
									esn_get_retina_image(
										$esn_category_logo,
										array(
											'alt'   => esc_attr( $category->name ),
											'title' => esc_attr( $category->name ),
										)
									);
									?>
									</a>
									</div>
								
							<?php } ?>
														
							<div class="flex-grow-1">
                               <a href="<?php echo get_category_link($category->term_id); ?>" class="es-theme-item_name">
				                  <?php echo $category->cat_name; ?>
			                   </a>
				                <span class="es-theme-item_count gap-3 d-none d-md-flex">
				                  <?php echo num_decline( (int) $category->count, 'статья,статьи,статей', 'esenin' ); ?>
								  <?php echo do_shortcode('[cat_subscribe_count category_id="' . $categoty_id . '"]'); ?>
				                </span>
								
		                    </div>
							
					</div>
                  <div class="es-theme-item_right d-flex align-items-center">
                      <div class="theme-subscribe">					  
					  <?php echo do_shortcode('[cat_subscribe category_id="' . $categoty_id . '"]'); ?>
					  </div>
                  </div>
              </div>		
      <?php } ?>
    </div>
    </div>	

  
  <?php } ?>
  <?php } else { ?>
   <div class="pb-3 fs-6 d-flex justify-content-center align-items-center">
    <?php echo esc_html__( 'Подписок на темы нет..', 'esenin' ); ?>
   </div>
  <?php } ?> 
  </div>

</div>
</div>

<script>
// Tabs save
$(document).ready(function(){
const pillsTab = document.querySelector('#pills-tab');
const pills = pillsTab.querySelectorAll('div[data-bs-toggle="pill"]');

pills.forEach(pill => {
  pill.addEventListener('shown.bs.tab', (event) => {
    const { target } = event;
    const { id: targetId } = target;
    
    savePillId(targetId);
  });
});

const savePillId = (selector) => {
  localStorage.setItem('activePillId', selector);
};

const getPillId = () => {
  const activePillId = localStorage.getItem('activePillId');
  
  // if local storage item is null, show default tab
  if (!activePillId) return;
  
  // call 'show' function
  const someTabTriggerEl = document.querySelector(`#${activePillId}`)
  const tab = new bootstrap.Tab(someTabTriggerEl);

  tab.show();
};

// get pill id on load
getPillId();
  });

</script>
	
	
	
	
	



	<?php
	/**
	 * The esn_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_after' );
	?>
 </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>