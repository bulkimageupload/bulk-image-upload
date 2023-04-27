<div class="biu-container">
    <div class="biu-container-inner">

        <h1><?php esc_html_e('Bulk Image Upload', 'bulk_image_upload'); ?></h1>

        <hr>

        <h2><?php esc_html_e('Choose Folder', 'bulk_image_upload'); ?></h2>

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
                <div class="biu-description">
                    <?php esc_html_e("Choose folder in which you have your product images.", 'bulk_image_upload'); ?>
                </div>

                <div class="biu-description">
                    <?php esc_html_e("If you want to connect different Google Drive account", 'bulk_image_upload'); ?>
                    <a href="#"><?php esc_html_e("click here", 'bulk_image_upload'); ?></a>
                </div>

                <select class="biu-folder-select">
                    <?php foreach ($args['folders'] as $folder) { ?>
                        <option value="<?php echo esc_html($folder) ?>">
                            <?php echo esc_html($folder) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        <?php } ?>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                console.log('hello');
            });
        </script>
    </div>
</div>