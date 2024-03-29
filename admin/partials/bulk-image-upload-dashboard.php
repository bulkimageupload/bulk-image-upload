<?php
/**
 * This is main page of the plugin, based on steps of setup different view being shown.
 * User should be able to connect mandatory service and see the button to create new upload
 * Additionally last upload jobs created shown in this view.
 *
 * @package bulk-image-upload
 */

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

$bulk_image_upload_domain = $args['domain'];
$bulk_image_upload_key    = $args['key'];
?>

<div class="biu-container">
	<div class="biu-container-inner">

		<h1><?php esc_html_e( 'Bulk Image Upload', 'bulk-image-upload' ); ?></h1>

		<hr>

		<?php if (!empty($args['plan']['name']) && 'Free' === $args['plan']['name'] ) { ?>
			<div class="notice notice-warning biu-notice">
				<h3><?php esc_html_e('You are currently using a FREE plan.', 'bulk-image-upload'); ?></h3>
				<?php esc_html_e('You can use the FREE plan to upload up to 100 product images.', 'bulk-image-upload'); ?><br>
				<?php esc_html_e('For more usage, please upgrade your account.', 'bulk-image-upload'); ?><br><br>
				<?php echo '<a target="_blank" href="https://woo.bulkimageupload.com/product/bulk-product-image-upload-for-woocommerce/?utm_source=woo_plugin&utm_medium=upgrade_link&utm_campaign=' . urlencode(esc_url($bulk_image_upload_domain)) . '" class="button">' . esc_html__( 'Upgrade to Premium', 'bulk-image-upload' ) . '</a>'; ?>
			</div>
		<?php } ?>

		<?php if ( array_key_exists( 'is_connected_to_service', $args ) && false === $args['is_connected_to_service'] ) { ?>
			<div class="biu-mt-20">
				<img style="width: 350px"
					src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'going-up.svg' ); ?>">
			</div>

			<div class="biu-description">
				<?php
				esc_html_e( 'Connect your store to Bulk Image Upload. We need permission to manage your products.', 'bulk-image-upload' );
				?>
			</div>

			<?php

			$bulk_image_upload_date_format = get_option('date_format') . ' ' . get_option('time_format');
			if (empty($bulk_image_upload_date_format)) {
				$bulk_image_upload_date_format = 'd/m/Y H:i';
			}

			$bulk_image_upload_timezone = get_option('timezone_string');
			if (empty($bulk_image_upload_timezone)) {
				$bulk_image_upload_timezone = 'Europe/Amsterdam';
			}

			$bulk_image_upload_connection_url = 'https://bulkimageupload.com/woo-commerce/register?domain=' . urlencode( $bulk_image_upload_domain ) . '&key=' . urlencode( $bulk_image_upload_key ) . '&user_id=' . get_current_user_id() . '&date_format=' . urlencode($bulk_image_upload_date_format) . '&timezone=' . urldecode($bulk_image_upload_timezone);
			echo '<a href="' . esc_url( $bulk_image_upload_connection_url ) . '" class="button button-primary button-large">' . esc_html__( 'Connect', 'bulk-image-upload' ) . '</a>';
			?>

		<?php } elseif ( array_key_exists( 'is_connected_to_drive', $args ) && false === $args['is_connected_to_drive'] ) { ?>

			<div class="biu-mt-20">
				<img style="width: 350px"
					src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'edit-photo.svg' ); ?>">
			</div>

			<div class="biu-description">
				<?php esc_html_e( 'Connect with Google Drive™. We need permission to access product images.', 'bulk-image-upload' ); ?>
			</div>

			<?php
			$bulk_image_upload_connection_url = 'https://bulkimageupload.com/google-drive/woo-commerce?domain=' . urlencode( $bulk_image_upload_domain ) . '&key=' . urlencode( $bulk_image_upload_key );
			echo '<br><a style="font-size:18px;" href="' . esc_url( $bulk_image_upload_connection_url ) . '" class="">' . esc_html__( 'Connect', 'bulk-image-upload' ) . '</a> <span style="font-size:20px;" >with</span> <span><img alt="Google Drive Logo" style="display: inline-block;vertical-align: middle; margin-top: -5px" height="20" src="' . esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'drive.png' ) . '" /></span> <span style="font-size:20px">Google Drive</span>';
			?>

			<div style="padding: 50px" class="biu-mt-20">
				"Bulk Image Upload"'s use and transfer of information received from Google APIs to any other app will adhere to <a target="_blank" href="https://developers.google.com/terms/api-services-user-data-policy">Google API Services User Data Policy</a>, including the Limited Use requirements.
			</div>

		<?php } else { ?>

			<?php if ( array_key_exists( 'is_upload_created', $args ) && false === $args['is_upload_created'] ) { ?>

				<div class="biu-mt-20">
					<img style="width: 350px"
						src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'online-shopping.svg' ); ?>">
				</div>

				<div class="biu-description">
					<?php esc_html_e( 'Congrats! You are ready to create your first upload.', 'bulk-image-upload' ); ?>
				</div>
			<?php } ?>

			<?php if ( ! empty( $args['total_uploaded'] ) && $args['total_uploaded'] >= 100 ) { ?>
				<div class="notice notice-success biu-mt-20 biu-notice">
					<?php echo sprintf( esc_html( 'You have successfully uploaded %d images to your store!' ), esc_html( $args['total_uploaded'] ) ); ?>
				</div>
			<?php } ?>

			<div class="biu-mt-20"></div>

			<?php
			$bulk_image_upload_connection_url = get_admin_url( null, 'admin.php?page=bulk-image-upload-create-new-upload' );
			echo '<a id="create_new_button" href="' . esc_url( $bulk_image_upload_connection_url ) . '" class="button button-primary">' . esc_html__( 'Create New Upload', 'bulk-image-upload' ) . '</a>';
			?>

			<img style="margin-top: 10px; display: none" id="loading_create_new" width="10"
				src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'loading.gif' ); ?>"/>

			<?php if ( ! empty( $args['uploads'] ) ) { ?>
				<table class="widefat fixed biu-mt-20">
					<thead style="background-color: #dcdcde">
					<tr>
						<th>
							<?php esc_html_e( 'Job ID', 'bulk-image-upload' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Name', 'bulk-image-upload' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Status', 'bulk-image-upload' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Created', 'bulk-image-upload' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Total', 'bulk-image-upload' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Uploaded', 'bulk-image-upload' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Details', 'bulk-image-upload' ); ?>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ( $args['uploads'] as $bulk_image_upload_job ) { ?>
						<tr style="text-align: left;">
							<td><?php echo esc_html( $bulk_image_upload_job['id'] ); ?></td>
							<td><?php echo esc_html( $bulk_image_upload_job['name'] ); ?></td>
							<td>
								<div class="biu-badge" style="background-color: <?php echo esc_html( Bulk_Image_Upload_Status_Color::get_color_by_status( $bulk_image_upload_job['status'] ) ); ?>">
									<?php echo esc_html( $bulk_image_upload_job['status'] ); ?>
								</div>
							</td>
							<td><?php echo esc_html( $bulk_image_upload_job['createdAt'] ); ?></td>
							<td><?php echo esc_html( $bulk_image_upload_job['totalImageCount'] ); ?></td>
							<td><?php echo esc_html( $bulk_image_upload_job['uploadedImageCount'] ); ?></td>
							<td>
								<a href="<?php echo esc_url( get_admin_url( null, 'admin.php?page=bulk-image-upload-job-logs' ) . '&job_id=' . $bulk_image_upload_job['id'] ); ?>">
									<?php esc_html_e( 'Show Logs', 'bulk-image-upload' ); ?>
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
				jQuery("#create_new_button").click(function (e) {
					e.preventDefault();
					jQuery("#create_new_button").addClass("button-primary-disabled");
					jQuery("#loading_create_new").show();
					let url = jQuery(this).attr("href");
					window.location = url;
				});

				<?php if ($args['is_refresh_needed']) { ?>
					jQuery('head').append('<meta http-equiv="refresh" content="5"/>');
				<?php } ?>
			});
		</script>
	</div>
</div>
