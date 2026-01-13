<div class="esn-select">
    <?php 
    // Определяем активную сортировку: если в GET пусто, то по дефолту 'week'
    $current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'week'; 
    ?>
    
    <form method="get" id="order">
        <select name="sort" onchange='this.form.submit()' id="in_popular_posts">
            <option value="today" <?php selected($current_sort, 'today'); ?>>
                <?php echo date('j') . ' ' . get_months_ru(date('n')); ?>
            </option>

            <option value="week" <?php selected($current_sort, 'week'); ?>>
                <?php esc_html_e('Неделя', 'esenin'); ?>
            </option>

            <option value="month" <?php selected($current_sort, 'month'); ?>>
                <?php esc_html_e('Месяц', 'esenin'); ?>
            </option>

            <option value="year" <?php selected($current_sort, 'year'); ?>>
                <?php esc_html_e('Год', 'esenin'); ?>
            </option>

            <option value="all_time" <?php selected($current_sort, 'all_time'); ?>>
                <?php esc_html_e('Всё время', 'esenin'); ?>
            </option>
        </select>

        <input type="hidden" name="paged" value="1">
    </form>
    
    <script>
        new SlimSelect({
            select: '#in_popular_posts',
            settings: {
                showSearch: false
            },
        });
    </script>
</div>