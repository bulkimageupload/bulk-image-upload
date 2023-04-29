<div class="biu-container">
    <div class="biu-container-inner">

        <h1><?php esc_html_e('Bulk Image Upload', 'bulk_image_upload'); ?></h1>

        <hr>

        <?php if (array_key_exists('is_connected_to_service', $args) && $args['is_connected_to_service'] === false) { ?>

            <div class="biu-mt-20">
                <img style="width: 350px" src="<?php echo Folder::getImagesUrl().'going-up.svg'; ?>">
            </div>

            <div class="biu-description">
                <?php
                esc_html_e("Connect your store to Bulk Image Upload. We need permission to manage your products.", 'bulk_image_upload');
                ?>
            </div>

            <?php
            $domain = $args['domain'];
            $key = $args['key'];
            $url = 'https://bulkimageupload.com/register' . '?domain=' . urlencode($domain) . '&key=' . urlencode($key);
            echo '<a href="' . esc_url($url) . '" class="button button-primary ' . '">' . esc_html__('Connect', 'bulk_image_upload') . '</a>';
            ?>

        <?php } elseif (array_key_exists('is_connected_to_drive', $args) && $args['is_connected_to_drive'] === false) { ?>

            <div class="biu-description">
                <?php esc_html_e("Connect to Google Drive. We need permission to access product images.", 'bulk_image_upload'); ?>
            </div>

            <?php
            $url = urlencode(trailingslashit(get_home_url())) . '&returnUrl=' . urlencode(get_admin_url(null, 'admin.php?page=bulk-image-upload'));
            echo '<a href="' . esc_url($url) . '" class="button button-primary ' . '">' . esc_html__('Connect to Google Drive', 'bulk_image_upload') . '</a>';
            ?>

        <?php } else { ?>

            <?php if (array_key_exists('is_upload_created', $args) && $args['is_upload_created'] === false) { ?>
                <div class="biu-description">
                    <?php esc_html_e("Congrats! You are ready to create your first upload.", 'bulk_image_upload'); ?>
                </div>
            <?php } ?>

            <div class="biu-mt-10"></div>

            <?php
            $url = get_admin_url(null, 'admin.php?page=bulk-image-upload-create-new-upload');
            echo '<a href="' . $url . '" class="button button-primary' . '">' . esc_html__('Create New Upload', 'bulk_image_upload') . '</a>';
            ?>

            <?php if (!empty($args['uploads'])) { ?>
                <table class="widefat fixed biu-mt-20">
                    <thead style="background-color: #dcdcde">
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            Upload Job
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Created
                        </th>
                        <th>
                            Total
                        </th>
                        <th>
                            Uploaded
                        </th>
                        <th>
                            Details
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($args['uploads'] as $upload) { ?>
                        <tr style="text-align: left; background-color: <?php echo StatusColor::getColorByStatusName($upload['status']) ?>">
                            <td><?php echo esc_html($upload['id']) ?></td>
                            <td><?php echo esc_html($upload['upload_job']) ?></td>
                            <td><?php echo esc_html($upload['status']) ?></td>
                            <td><?php echo esc_html($upload['created']) ?></td>
                            <td><?php echo esc_html($upload['total']) ?></td>
                            <td><?php echo esc_html($upload['uploaded']) ?></td>
                            <td>
                                <a href="<?php echo get_admin_url(null, 'admin.php?page=bulk-image-upload-job-logs') . '&job_id=' . $upload['id'] ?>">
                                    Show Logs
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>

        <?php } ?>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                console.log('hello');
            });
        </script>
    </div>
</div>