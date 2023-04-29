<?php

class ErrorTemplate
{
    public static function showErrorTemplate($errorMessage)
    {
        load_template(plugin_dir_path(__FILE__) . '../../includes/templates/error.php', true, [
            'error' => __($errorMessage, 'bulk_image_upload')
        ]);
        exit;
    }
}