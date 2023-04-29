<div class="biu-container">
    <div class="biu-container-inner">

        <h1><?php esc_html_e('Bulk Image Upload', 'bulk_image_upload'); ?></h1>

        <hr>

        <h2><?php esc_html_e('Error', 'bulk_image_upload'); ?></h2>

        <div class="biu-description">
            <div class="notice notice-error">
                <?php echo esc_html($args['error']); ?>
            </div>
        </div>

        <a class="button button-primary" href="<?php echo get_admin_url(null, 'admin.php?page=bulk-image-upload') ?>">
            Go to Dashboard
        </a>

    </div>
</div>