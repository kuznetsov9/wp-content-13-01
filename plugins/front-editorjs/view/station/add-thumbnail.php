<?php
defined('ABSPATH') || exit; 
?>
<div class="box upload-image-post">
    <div class="js--image-preview">
        <?php if (has_post_thumbnail($post_edit['id'])): ?>
            <img src="<?php echo get_the_post_thumbnail_url($post_edit['id']); ?>" alt="thumbnail" />
            <span class="remove-thumbnail" title="<?php _e( 'Удалить', 'front-editorjs' ); ?>" data-attach-id="<?php echo get_post_thumbnail_id($post_edit['id']); ?>">×</span>
        <?php endif; ?>

        <div class="fred-add-image" <?php if (has_post_thumbnail($post_edit['id'])) echo 'style="display:none !important;"'; ?>>
            <div class="icon-add-image">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"></path><path d="m14 19.5 3-3 3 3"></path><path d="M17 22v-5.5"></path><circle cx="9" cy="9" r="2"></circle></svg>
            </div>
            <div style="margin-top:10px !important; color:#6b7280 !important; font-weight:600 !important; line-height: 1.2 !important;">
                <?php _e('Загрузить обложку', 'front-editorjs'); ?>
            </div>
        </div>
    </div>

    <input type="file" class="image-upload" accept="image/*" style="display: none !important;" /> 
    <input type="hidden" name="thumbnail_id" id="thumbnail_id" value="<?php echo get_post_thumbnail_id($post_edit['id']) ?: ''; ?>" />
</div>

<script>
// Весь JS остается без изменений, он работает с ID и классами, импортанты на него не влияют.
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('.image-upload');
    const imagePreview = document.querySelector('.js--image-preview');

    imagePreview.addEventListener('click', function(e) {
        if (!e.target.closest('.remove-thumbnail')) {
            fileInput.click();
        }
    });

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('action', 'handle_thumb_upload');

        imagePreview.style.opacity = '0.6';

        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            imagePreview.style.opacity = '1';
            if (data.success) {
                imagePreview.innerHTML = `
                    <img src="${data.fileUrl}" alt="thumbnail" />
                    <span class="remove-thumbnail" data-attach-id="${data.attachId}">×</span>
                `;
                document.getElementById('thumbnail_id').value = data.attachId;
            }
        });
    });

    imagePreview.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-thumbnail');
        if (!btn) return;
        e.stopPropagation();

        const formData = new FormData();
        formData.append('action', 'remove_thumbnail');
        formData.append('attachId', btn.dataset.attachId);

        fetch('<?php echo admin_url("admin-ajax.php"); ?>', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload(); 
            }
        });
    });
});
</script>