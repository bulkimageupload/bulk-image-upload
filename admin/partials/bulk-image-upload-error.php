<?php
/**
 * This view being shown to display errors.
 *
 * @package bulk-image-upload-for-woocommerce
 */

?>

<div class="biu-container">
	<div class="biu-container-inner">

		<h1><?php esc_html_e( 'Bulk Image Upload', 'bulk-image-upload' ); ?></h1>

		<hr>

		<h2 style="color: #b32d2e"><?php esc_html_e( 'Something Went Wrong', 'bulk-image-upload' ); ?></h2>

		<div class="notice notice-error biu-notice">
			<?php echo esc_html( $args['error'] ); ?>
		</div>

		<div class="biu-mt-20">
			<a class="button button-primary"
			   href="<?php echo esc_url( get_admin_url( null, 'admin.php?page=bulk-image-upload' ) ); ?>">
				Go to Dashboard
			</a>
		</div>

	</div>
</div>
