<?php
/**
 * This view is being displayed in admin when user sees image matching results.
 *
 * @package bulk-image-upload-for-woocommerce
 */

?>

<div class="biu-container">
	<div class="biu-container-inner">
		<h1><?php esc_html_e( 'Bulk Image Upload', 'bulk-image-upload' ); ?></h1>
		<hr>
		<h2><?php esc_html_e('Matching Results', 'bulk-image-upload'); ?></h2>

		<?php if (count($args['matching_results']['matched_images']) > 0) { ?>
			<div class="notice notice-success biu-notice">
				Total <?php echo esc_html(count($args['matching_results']['matched_images'])); ?> images successfully matched.
				<br>
				<div class="biu-mt-10">
					<a class="button button-primary">
						Start Upload
					</a>
				</div>
			</div>

			<table class="widefat fixed biu-mt-20 striped">
				<thead style="background-color: #68de7c">
				<th>Image Preview</th>
				<th>Image Name</th>
				<th>Image Size</th>
				<th>Position</th>
				<th>Variant</th>
				<th>SKU</th>
				</thead>
				<?php foreach ($args['matching_results']['matched_images'] as $bulk_image_upload_matched_image) { ?>
					<tr style="text-align: left;">
						<td>
							<img height="150" referrerPolicy="no-referrer" src="<?php echo esc_html($bulk_image_upload_matched_image['thumbnail']); ?>">
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['image_name']); ?>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['image_size']); ?>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['position']); ?>
						</td>
						<td>
							<a target="_blank" href="<?php echo esc_html($bulk_image_upload_matched_image['variant_url']); ?>">
								<?php echo esc_html($bulk_image_upload_matched_image['variant_name']); ?>
							</a>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_matched_image['sku']); ?>
						</td>
					</tr>
				<?php } ?>
			</table>
		<?php } ?>



		<?php if (count($args['matching_results']['non_matched_images']) > 0) { ?>
			<div class="notice notice-error biu-notice biu-mt-20">
				Total <?php echo esc_html(count($args['matching_results']['non_matched_images'])); ?> images couldn't be matched.
				<br>
				<div class="biu-mt-10">
					<a href="/" id="try-again" class="button button-primary">
						Restart Matching
						<img style="margin-top: 10px; display: none" id="loading-try-again" width="10"
							 src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'loading.gif' ); ?>"/>
					</a>
				</div>
			</div>
			<table class="widefat fixed biu-mt-20 striped">
				<thead style="background-color: #ff8085">
					<th>Image Preview</th>
					<th>Image Name</th>
					<th>Image Size</th>
					<th>Expected SKU</th>
				</thead>
				<?php foreach ($args['matching_results']['non_matched_images'] as $bulk_image_upload_non_matched_image) { ?>
					<tr style="text-align: left;">
						<td>
							<img width="150" referrerPolicy="no-referrer" src="<?php echo esc_html($bulk_image_upload_non_matched_image['thumbnail']); ?>">
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_non_matched_image['image_name']); ?>
						</td>
						<td>
							<?php echo esc_html($bulk_image_upload_non_matched_image['image_size']); ?>
						</td>
						<td>
							<?php echo esc_html(strtoupper($bulk_image_upload_non_matched_image['expected_sku'])); ?>
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
			location.reload();
		});
	});
</script>
