<?php
/**
 * This view is being displayed in admin when user wants to see details of upload job.
 *
 * @package bulk-image-upload-for-woocommerce
 */

?>

<div class="biu-container">
	<div class="biu-container-inner">

		<h1><?php esc_html_e( 'Bulk Image Upload', 'bulk-image-upload' ); ?></h1>

		<hr>

		<h2><?php echo esc_html( $args['job']['hash'] ); ?></h2>

		<script type="text/javascript">
			jQuery(document).ready(function () {
                jQuery('head').append('<meta http-equiv="refresh" content="5"/>');
			});
		</script>
	</div>
</div>
