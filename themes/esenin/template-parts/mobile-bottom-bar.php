<?php
/**
 * Mobile Bottom Fixed Bar Template — Light Version
 * РФПЛ.рф — Только нужный функционал
 */
?>
<div class="rfpl-mobile-bar">
    <div class="rfpl-bar-container">
        
        <a href="<?php echo home_url('/'); ?>" class="rfpl-bar-item <?php echo is_front_page() ? 'active' : ''; ?>">
            <div class="rfpl-icon">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" ><path stroke="none" d="M23.121,9.069,15.536,1.483a5.008,5.008,0,0,0-7.072,0L.879,9.069A2.978,2.978,0,0,0,0,11.19v9.817a3,3,0,0,0,3,3H21a3,3,0,0,0,3-3V11.19A2.978,2.978,0,0,0,23.121,9.069ZM15,22.007H9V18.073a3,3,0,0,1,6,0Zm7-1a1,1,0,0,1-1,1H17V18.073a5,5,0,0,0-10,0v3.934H3a1,1,0,0,1-1-1V11.19a1.008,1.008,0,0,1,.293-.707L9.878,2.9a3.008,3.008,0,0,1,4.244,0l7.585,7.586A1.008,1.008,0,0,1,22,11.19Z"/></svg>
            </div>
        </a>
		
		<a href="<?php echo home_url('/rpl-table/'); ?>" class="rfpl-bar-item <?php echo is_front_page() ? 'active' : ''; ?>">
            <div class="rfpl-icon">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
<path d="M15.091,16C21.661,15.964,24,12.484,24,9.5a3.5,3.5,0,0,0-2.764-3.419c.136-.387.254-.742.333-1.011a3.887,3.887,0,0,0-.626-3.458A3.979,3.979,0,0,0,17.729,0H6.271A3.979,3.979,0,0,0,3.057,1.612,3.887,3.887,0,0,0,2.431,5.07c.079.269.2.624.333,1.011A3.5,3.5,0,0,0,0,9.5c0,2.984,2.339,6.464,8.909,6.5A5.06,5.06,0,0,1,9,16.921V20a1.883,1.883,0,0,1-2,2H6a1,1,0,0,0,0,2H18a1,1,0,0,0,0-2h-.992A1.885,1.885,0,0,1,15,20V16.92A5.058,5.058,0,0,1,15.091,16ZM20.5,8A1.5,1.5,0,0,1,22,9.5c0,2.034-1.609,4.2-6.036,4.47a4.847,4.847,0,0,1,.762-.821A15.132,15.132,0,0,0,20.453,7.99C20.469,7.991,20.483,8,20.5,8ZM2,9.5A1.5,1.5,0,0,1,3.5,8c.017,0,.031-.009.047-.01a15.132,15.132,0,0,0,3.727,5.159,4.847,4.847,0,0,1,.762.821C3.609,13.7,2,11.534,2,9.5ZM10.513,22A4.08,4.08,0,0,0,11,20V16.921a6.93,6.93,0,0,0-2.431-5.295A15.338,15.338,0,0,1,4.349,4.5a1.9,1.9,0,0,1,.31-1.694A1.994,1.994,0,0,1,6.271,2H17.729a1.994,1.994,0,0,1,1.612.81,1.9,1.9,0,0,1,.31,1.694,15.338,15.338,0,0,1-4.22,7.122A6.928,6.928,0,0,0,13,16.92V20a4.08,4.08,0,0,0,.487,2Z"/></svg>
            </div>
        </a>
		
		<a href="/editor" class="rfpl-bar-item rfpl-add-post" >
            <div class="rfpl-icon">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" ><path stroke="none" d="m12 0a12 12 0 1 0 12 12 12.013 12.013 0 0 0 -12-12zm0 22a10 10 0 1 1 10-10 10.011 10.011 0 0 1 -10 10zm5-10a1 1 0 0 1 -1 1h-3v3a1 1 0 0 1 -2 0v-3h-3a1 1 0 0 1 0-2h3v-3a1 1 0 0 1 2 0v3h3a1 1 0 0 1 1 1z"/></svg>
            </div>
        </a>
<?php 
// Определяем, какой фильтр сейчас активен
$current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'week'; 
// Проверяем, на главной мы или в популярном
$is_popular_page = is_page_template('pages/popular-posts.php') || is_page('popular'); 
?>

<div class="rfpl-bar-item rfpl-has-popup" id="mobileSortToggle">
    <div class="rfpl-icon" onclick="this.parentElement.classList.toggle('is-open')">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" ><path stroke="none" d="M18.873,11.021H5.127a2.126,2.126,0,0,1-1.568-3.56L10.046.872a2.669,2.669,0,0,1,3.939.034l6.431,6.528a2.126,2.126,0,0,1-1.543,3.587ZM5.011,8.837a.115.115,0,0,0,0,.109.111.111,0,0,0,.114.075H18.873a.111.111,0,0,0,.114-.075.109.109,0,0,0-.022-.135L12.528,2.276A.7.7,0,0,0,12,2.021a.664.664,0,0,0-.5.221L5.01,8.838ZM12,24.011a2.667,2.667,0,0,1-1.985-.887L3.584,16.6a2.125,2.125,0,0,1,1.543-3.586H18.873a2.125,2.125,0,0,1,1.568,3.558l-6.487,6.589A2.641,2.641,0,0,1,12,24.011Zm-6.873-9a.125.125,0,0,0-.092.209l6.437,6.534a.7.7,0,0,0,.528.257.665.665,0,0,0,.5-.223l6.493-6.6h0a.112.112,0,0,0,0-.108.111.111,0,0,0-.114-.074Z"/>
        </div>
    <div class="rfpl-popup-menu">
        <div class="rfpl-popup-header">Сортировка постов</div>
		<a href="/my" class="rfpl-popup-link <?php echo is_page('my') ? 'is-active' : ''; ?>">Подписки</a>
        <a href="/" class="rfpl-popup-link <?php echo !$is_popular_page ? 'is-active' : ''; ?>">Свежее</a>
        <a href="/popular?sort=today" class="rfpl-popup-link <?php echo ($is_popular_page && $current_sort == 'today') ? 'is-active' : ''; ?>">Сутки</a>
        <a href="/popular?sort=week" class="rfpl-popup-link <?php echo ($is_popular_page && $current_sort == 'week') ? 'is-active' : ''; ?>">Неделя</a>
        <a href="/popular?sort=month" class="rfpl-popup-link <?php echo ($is_popular_page && $current_sort == 'month') ? 'is-active' : ''; ?>">Месяц</a>
        <a href="/popular?sort=year" class="rfpl-popup-link <?php echo ($is_popular_page && $current_sort == 'year') ? 'is-active' : ''; ?>">Год</a>
        <a href="/popular?sort=all_time" class="rfpl-popup-link <?php echo ($is_popular_page && $current_sort == 'all_time') ? 'is-active' : ''; ?>">Всё время</a>
    </div>
    <div class="rfpl-popup-overlay" onclick="document.getElementById('mobileSortToggle').classList.remove('is-open')"></div>
</div>
			

        <div class="rfpl-bar-item" onclick="document.querySelector('.es-header .es-header__offcanvas-toggle').click();">
            <div class="rfpl-icon">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" >
<rect y="11" width="24" height="2" rx="1"/><rect y="4" width="24" height="2" rx="1"/><rect y="18" width="24" height="2" rx="1"/>
</svg>
            </div>
        </div>


    </div>
</div>