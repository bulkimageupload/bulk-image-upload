<div class="biu-container">
    <div class="biu-container-inner">

        <h1><?php esc_html_e('Bulk Image Upload', 'bulk_image_upload'); ?></h1>

        <hr>

        <h2><?php esc_html_e('Choose Folder in Google Drive', 'bulk_image_upload'); ?></h2>

        <?php if (empty($args['folders'])) { ?>
            <div class="notice notice-error">
                <?php esc_html_e("Folders not found! Please create folders on main level in your Google Drive account.", 'bulk_image_upload'); ?>
            </div>

            <div class="biu-description">
                <?php esc_html_e("If you want to connect different Google Drive account", 'bulk_image_upload'); ?>
                <a href="#"><?php esc_html_e("click here", 'bulk_image_upload'); ?></a>
            </div>

        <?php } else { ?>

            <div class="biu-description">
                <?php esc_html_e("Choose folder in which you have your product images.", 'bulk_image_upload'); ?>
            </div>

            <div class="biu-description">
                <?php esc_html_e("If you want to connect different Google Drive account", 'bulk_image_upload'); ?>
                <a href="#"><?php esc_html_e("click here", 'bulk_image_upload'); ?></a>
            </div>

            <select class="biu-folder-select">
                <option>Select Folder</option>
                <?php foreach ($args['folders'] as $folder) { ?>
                    <option value="<?php echo esc_html($folder) ?>">
                        <?php echo esc_html($folder) ?>
                    </option>
                <?php } ?>
            </select>

            <hr>

            <h2><?php esc_html_e('Change the type of matching', 'bulk_image_upload'); ?></h2>

            <div class="biu-description">
                <?php esc_html_e('We recommend to match images by exact SKU for the maximum efficiency.', 'bulk_image_upload'); ?>
            </div>

            <select class="biu-folder-select">
                <option
                    value="sku"><?php esc_html_e('Match by exact SKU (recommended)', 'bulk_image_upload'); ?></option>
                <option
                    value="sku_wildcard"><?php esc_html_e('Match if image contains SKU', 'bulk_image_upload'); ?></option>
                <option
                    value="sku_contains_image"><?php esc_html_e('Match if SKU contains image name', 'bulk_image_upload'); ?></option>
            </select>

            <hr>

            <h2><?php esc_html_e('See matching results', 'bulk_image_upload'); ?></h2>

            <div class="biu-description">
                <?php
                esc_html_e('You will see how images matched to products before uploading images to products.', 'bulk_image_upload');
                ?>
            </div>

            <div class="biu-mt-10">
                <?php echo '<a href="#" class="button button-primary disabled ' . '">' . esc_html__('Start Matching', 'bulk_image_upload') . '</a>'; ?>
            </div>

        <?php } ?>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                console.log('hello');
            });
        </script>
    </div>
</div>