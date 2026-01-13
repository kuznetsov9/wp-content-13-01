<?php
/**
* Автоматическое конвертирование загруженных изображений в формат WebP
*
* Дополнительная конфигурация:
* - По умолчанию исходный файл изображения удаляется после конвертации в WebP.
* Если вы предпочитаете сохранить исходный файл изображения, просто закомментируйте или удалите строку: "@unlink( $file_path );"
* Это позволит сохранить исходный загруженный файл изображения вместе с версией WebP.
*
*
* @package Esenin
*/

add_filter( 'wp_handle_upload', 'esn_handle_upload_convert_to_webp' );

function esn_handle_upload_convert_to_webp( $upload ) {
    if ( $upload['type'] == 'image/jpeg' || $upload['type'] == 'image/png' || $upload['type'] == 'image/gif' ) {
        $file_path = $upload['file'];

        // Проверьте, доступен ли ImageMagick или GD.
        if ( extension_loaded( 'imagick' ) || extension_loaded( 'gd' ) ) {
            $image_editor = wp_get_image_editor( $file_path );
            if ( ! is_wp_error( $image_editor ) ) {
                $file_info = pathinfo( $file_path );
                $dirname   = $file_info['dirname'];
                $filename  = $file_info['filename'];

                // Создайте новый путь к файлу для изображения WebP.
                $new_file_path = $dirname . '/' . $filename . '.webp';

                // Попытайтесь сохранить изображение в формате WebP.
                $saved_image = $image_editor->save( $new_file_path, 'image/webp' );
                if ( ! is_wp_error( $saved_image ) && file_exists( $saved_image['path'] ) ) {
                    // Отлично: замените загруженное изображение изображением WebP.
                    $upload['file'] = $saved_image['path'];
                    $upload['url']  = str_replace( basename( $upload['url'] ), basename( $saved_image['path'] ), $upload['url'] );
                    $upload['type'] = 'image/webp';

                    // При желании удалите исходное изображение.
                    @unlink( $file_path );
                }
            }
        }
    }

    return $upload;
}