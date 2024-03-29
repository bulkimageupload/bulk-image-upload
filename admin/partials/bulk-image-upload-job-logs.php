<?php
/**
 * This view is being displayed in admin when user wants to see details of upload job.
 *
 * @package bulk-image-upload
 */

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

?>

<div class="biu-container">
	<div class="biu-container-inner">

		<h1><?php esc_html_e( 'Bulk Image Upload', 'bulk-image-upload' ); ?></h1>

		<hr>

		<h2>
			<?php echo esc_html( $args['job']['name'] ); ?>
			<div class="biu-badge biu-mt-20" style="background-color: <?php echo esc_html( Bulk_Image_Upload_Status_Color::get_color_by_status( $args['job']['status'] ) ); ?>">
				<?php echo esc_html( $args['job']['status']  ); ?>
			</div>
		</h2>

		<?php if (empty($args['job']['uploadLogs'])) { ?>
			<?php esc_html_e( 'Upload job is waiting in queue. It automatically will be processed.', 'bulk-image-upload' ); ?>
		<?php } else { ?>
			<div style="background-color: #dfe0e1; text-align: left; padding: 5px">
			<?php foreach ($args['job']['uploadLogs'] as $bulk_image_upload_log) { ?>
				<?php 
				echo wp_kses($bulk_image_upload_log, array(
					'a' => array(
						'href' => array(),
						'title' => array(),
						'target' => array()
					),
					'b' => array()
				)); 
				?>
				<br>
			<?php } ?>
			</div>
		<?php } ?>

		<div class="biu-mt-20">
			<a class="button button-primary"
			   href="<?php echo esc_url( get_admin_url( null, 'admin.php?page=bulk-image-upload' ) ); ?>">
				Go to Dashboard
			</a>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				<?php if ('pending' === $args['job']['status'] || 'processing' === $args['job']['status']) { ?>
					jQuery('head').append('<meta http-equiv="refresh" content="5"/>');
				<?php } ?>
			});
		</script>
	</div>
</div>
