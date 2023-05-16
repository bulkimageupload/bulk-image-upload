<?php
/**
 * This view being shown when user is creating new upload.
 *
 * @package bulk-image-upload-for-woocommerce
 */

?>

<div class="biu-container">
	<div class="biu-container-inner">

		<h1><?php esc_html_e( 'Bulk Image Upload', 'bulk-image-upload' ); ?></h1>

		<hr>

		<h2><?php esc_html_e( 'Step 1: Choose folder in Google Drive', 'bulk-image-upload' ); ?></h2>

		<?php if ( empty( $args['folders'] ) ) { ?>
			<div class="notice notice-error biu-notice">
				<?php esc_html_e( 'Folders not found! Please create folders on main level in your Google Drive account.', 'bulk-image-upload' ); ?>
			</div>

			<div class="biu-description">
				<?php esc_html_e( 'If you want to connect different Google Drive account', 'bulk-image-upload' ); ?>
				<a href="#"><?php esc_html_e( 'click here', 'bulk-image-upload' ); ?></a>
			</div>

		<?php } else { ?>

			<div class="notice notice-info biu-notice">
				<div class="biu-description">
					<?php esc_html_e( 'Choose folder in which you have your product images.', 'bulk-image-upload' ); ?>
				</div>

				<div class="biu-description">
					<?php esc_html_e( 'If you want to connect different Google Drive account', 'bulk-image-upload' ); ?>
					<a href="#"><?php esc_html_e( 'click here', 'bulk-image-upload' ); ?></a>
				</div>

				<select class="biu-folder-select">
					<option>Select Folder</option>
					<?php foreach ( $args['folders'] as $bulk_image_upload_folder_key => $bulk_image_upload_folder ) { ?>
						<option value="<?php echo esc_html( $bulk_image_upload_folder_key ); ?>">
							<?php echo esc_html( $bulk_image_upload_folder ); ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<hr class="biu-mt-10">

			<h2><?php esc_html_e( 'Step 2: Change the type of matching', 'bulk-image-upload' ); ?></h2>

			<div class="notice notice-info biu-notice">
				<div class="biu-description">
					<?php esc_html_e( 'We recommend to match images by exact SKU for the maximum efficiency.', 'bulk-image-upload' ); ?>
				</div>

				<select class="biu-folder-select">
					<?php foreach ($args['matching_methods'] as $bulk_image_upload_matching_method_key => $bulk_image_upload_matching_method) { ?>
					<option value="<?php echo esc_html($bulk_image_upload_matching_method_key); ?>">
						<?php echo esc_html($bulk_image_upload_matching_method); ?>
					</option>
					<?php } ?>
				</select>
			</div>

			<hr class="biu-mt-10">

			<h2><?php esc_html_e( 'Step 3: See matching results', 'bulk-image-upload' ); ?></h2>

			<div class="notice notice-info biu-notice">
				<div class="biu-description">
					<?php
					esc_html_e( 'You will see how images matched to products before uploading images to products.', 'bulk-image-upload' );
					?>
				</div>

				<div class="biu-mt-10">
					<?php echo '<a href="#" class="button button-primary disabled">' . esc_html__( 'Start Matching', 'bulk-image-upload' ) . '</a>'; ?>
				</div>
			</div>

		<?php } ?>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				console.log('hello');
			});
		</script>
	</div>
</div>
