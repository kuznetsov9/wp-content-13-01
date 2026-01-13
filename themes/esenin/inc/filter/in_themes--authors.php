<div class="esn-select in_themes--authors">
    <form method="get" id="order">
        <select name="sort" onchange='this.form.submit()' id="in_themes--authors">
            <option value="new" <?= (isset($_GET['sort']) && $_GET['sort'] == 'new') ? ' selected="selected"' : ''; ?>><?= esc_html__('Свежее', 'esenin'); ?></option>
            <option value="popular" <?= (isset($_GET['sort']) && $_GET['sort'] == 'week') ? ' selected="selected"' : ''; ?>><?= esc_html__('Топ недели', 'esenin'); ?></option>
            <option value="month" <?= (isset($_GET['sort']) && $_GET['sort'] == 'month') ? ' selected="selected"' : ''; ?>><?= esc_html__('Топ месяца', 'esenin'); ?></option>
            <option value="year" <?= (isset($_GET['sort']) && $_GET['sort'] == 'year') ? ' selected="selected"' : ''; ?>><?= esc_html__('Топ года', 'esenin'); ?></option>
			<option value="year" <?= (isset($_GET['sort']) && $_GET['sort'] == 'popular') ? ' selected="selected"' : ''; ?>><?= esc_html__('Лучшее', 'esenin'); ?></option>
        </select>
        <input type="hidden" name="paged" value="1">
    </form>
    <script>
        new SlimSelect({
            select: '#in_themes--authors',
            settings: {
                showSearch: false
            },
        });
    </script>
</div>
