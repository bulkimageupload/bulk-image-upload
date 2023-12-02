<?php
/**
 * This view is being displayed in admin when user sees image matching results.
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
		<h2><?php esc_html_e('Matching Results', 'bulk-image-upload'); ?></h2>

		<?php if ('waiting_for_upload' !== $args['matching_results']['status'] ) { ?>
			<div class="notice notice-info biu-notice">
				<?php esc_html_e('Matching is in progress...', 'bulk-image-upload'); ?>
				<img style="margin-top: 10px;" id="loading-try-again" width="10"
					 src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'loading.gif' ); ?>"/>
				<br><br>
				<?php esc_html_e('Matching may take up to 30 minutes based on number of images and products', 'bulk-image-upload'); ?><br><br>
				<?php esc_html_e("Please don't close this tab", 'bulk-image-upload'); ?>
			</div>
		<?php } ?>

		<?php if ('waiting_for_upload' === $args['matching_results']['status'] && isset($args['matching_results']['matchedImages']) && count($args['matching_results']['matchedImages']) === 0 && count($args['matching_results']['nonMatchedImages']) === 0) { ?>
			<div class="notice notice-error biu-notice">
				<?php esc_html_e('Images not found inside the folder', 'bulk-image-upload'); ?>
			</div>
		<?php } ?>

		<?php if (count($args['matching_results']['matchedImages']) > 0) { ?>
		<div class="notice notice-success biu-notice">
			Total <?php echo esc_html(count($args['matching_results']['matchedImages'])); ?> images successfully matched.
			<br>
			<div class="biu-mt-10">
				<a href="<?php echo esc_url(get_admin_url( null, 'admin.php?page=bulk-image-upload-send-upload-request' ) . '&matching_hash=' . $args['matching_results']['hash'] . '&matching_id=' . $args['matching_results']['id']); ?>" class="button button-primary">
					<?php esc_html_e( 'Start Upload', 'bulk-image-upload' ); ?>
				</a>
			</div>
		</div>
		<?php } ?>

		<?php if (count($args['matching_results']['nonMatchedImages']) > 0) { ?>
			<div class="notice notice-error biu-notice biu-mt-20">
				Total <?php echo esc_html(count($args['matching_results']['nonMatchedImages'])); ?> images couldn't be matched.
				<br>
				<div class="biu-mt-10">
					<a href="/" id="try-again" class="button button-primary">
						Restart Matching
						<img style="margin-top: 10px; display: none" id="loading-try-again" width="10"
							 src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'loading.gif' ); ?>"/>
					</a>
				</div>
			</div>
		<?php } ?>

		<?php if (count($args['matching_results']['matchedImages']) > 0) { ?>

			<h2><?php esc_html_e( 'Matched Images', 'bulk-image-upload' ); ?></h2>

			<table class="widefat fixed biu-mt-20 striped">
				<thead style="background-color: #68de7c">
				<th><?php esc_html_e( 'Image Preview', 'bulk-image-upload' ); ?></th>
				<th><?php esc_html_e( 'Image Name', 'bulk-image-upload' ); ?></th>
				<th><?php esc_html_e( 'Image Size', 'bulk-image-upload' ); ?></th>
				<th><?php esc_html_e( 'Position', 'bulk-image-upload' ); ?></th>
				<th><?php esc_html_e( 'Product', 'bulk-image-upload' ); ?></th>
				<th><?php esc_html_e( 'SKU', 'bulk-image-upload' ); ?></th>
				</thead>
				<?php foreach ($args['matching_results']['matchedImages'] as $bulk_image_upload_matched_image) { ?>
					<tr style="text-align: left;">
						<td>
							<img style="max-width: 150px" height="150" referrerPolicy="no-referrer" src="<?php echo esc_html($bulk_image_upload_matched_image['thumbnailUrl']); ?>">
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['name']); ?>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['humanSize']); ?>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['position']); ?>
						</td>
						<td>
							<a target="_blank" href="<?php echo esc_html($bulk_image_upload_matched_image['product']['adminUrl']); ?>">
								<?php echo esc_html($bulk_image_upload_matched_image['product']['name']); ?>
							</a>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['sku']); ?>
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>



		<?php if (count($args['matching_results']['nonMatchedImages']) > 0) { ?>

			<h2><?php esc_html_e( 'Non Matched Images', 'bulk-image-upload' ); ?></h2>

			<table class="widefat fixed biu-mt-20 striped">
				<thead style="background-color: #ff8085">
					<th><?php esc_html_e( 'Image Preview', 'bulk-image-upload' ); ?></th>
					<th><?php esc_html_e( 'Image Name', 'bulk-image-upload' ); ?></th>
					<th><?php esc_html_e( 'Image Size', 'bulk-image-upload' ); ?></th>
					<th><?php esc_html_e( 'Expected SKU', 'bulk-image-upload' ); ?></th>
				</thead>
				<?php foreach ($args['matching_results']['nonMatchedImages'] as $bulk_image_upload_non_matched_image) { ?>
					<tr style="text-align: left;">
						<td>
							<img style="max-width: 150px" width="150" referrerPolicy="no-referrer" src="<?php echo esc_html($bulk_image_upload_non_matched_image['thumbnailUrl']); ?>">
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_non_matched_image['name']); ?>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_non_matched_image['size']); ?>
						</td>
						<td>
							<?php echo esc_html(strtoupper($bulk_image_upload_non_matched_image['sku'])); ?>
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>

	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function () {

		jQuery("#try-again").click(function (e) {
			e.preventDefault();
			jQuery("#try-again").addClass("disabled");
			jQuery("#loading-try-again").show();

			var url=window.location.href,
				separator = (url.indexOf("?")===-1)?"?":"&",
				newParam=separator + "retry=true";
			newUrl=url.replace(newParam,"");
			newUrl+=newParam;
			window.location.href = newUrl;
		});

		<?php if ('waiting_for_upload' !== $args['matching_results']['status'] ) { ?>
			jQuery('head').append('<meta http-equiv="refresh" content="5"/>');
		<?php } else { ?>
			window.$crisp=[];window.CRISP_WEBSITE_ID="4ddb6546-61da-448f-b2d9-5ebe639a09d6";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
		<?php } ?>
	});
</script>
