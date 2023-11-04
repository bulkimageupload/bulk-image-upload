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
					<?php
					$bulk_image_upload_remove_google_drive_connection = get_admin_url( null, 'admin.php?page=bulk-image-upload-remove-google-drive-connection' );
					?>
					<a href="<?php echo esc_url($bulk_image_upload_remove_google_drive_connection); ?>"><?php esc_html_e( 'click here', 'bulk-image-upload' ); ?></a>
				</div>

                <input style="width: 90%; text-align: center" type="text" id="autocomplete-input" placeholder="Type folder name here...">
                <input type="hidden" id="selected-folder" name="selected_folder">
				<select id="folder-select" class="biu-select" style="display: none;">
					<?php foreach ( $args['folders'] as $bulk_image_upload_folder_key => $bulk_image_upload_folder ) { ?>
						<option value="<?php echo esc_html( $bulk_image_upload_folder_key ); ?>">
							<?php echo esc_html( $bulk_image_upload_folder['name'] ); ?>
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

				<select style="width: 90%; text-align: center" id="choose-matching-dropdown" class="biu-select">
					<?php foreach ($args['matching_methods'] as $bulk_image_upload_matching_method_key => $bulk_image_upload_matching_method) { ?>
					<option value="<?php echo esc_html($bulk_image_upload_matching_method_key); ?>">
						<?php echo esc_html($bulk_image_upload_matching_method); ?>
					</option>
					<?php } ?>
				</select>
			</div>

			<hr class="biu-mt-10">

			<h2><?php esc_html_e( 'Step 3: Would you like to replace the current images?', 'bulk-image-upload' ); ?></h2>

			<div class="notice notice-info biu-notice">
				<div class="biu-description">
					<?php esc_html_e( 'You can decide to delete current images and replace them with new images or add new images without removing current product images.', 'bulk-image-upload' ); ?>
				</div>

				<select style="width: 90%; text-align: center" id="choose-replacement-dropdown" class="biu-select">
					<?php foreach ($args['replacement_methods'] as $bulk_image_upload_replacement_method_key => $bulk_image_upload_replacement_method) { ?>
						<option value="<?php echo esc_html($bulk_image_upload_replacement_method_key); ?>">
							<?php echo esc_html($bulk_image_upload_replacement_method); ?>
						</option>
					<?php } ?>
				</select>
			</div>

			<hr class="biu-mt-10">

			<h2><?php esc_html_e( 'Step 4: See matching results', 'bulk-image-upload' ); ?></h2>

			<div class="notice notice-info biu-notice">
				<div class="biu-description">
					<?php
					esc_html_e( 'You will see how images matched to products before uploading images to products.', 'bulk-image-upload' );
					?>
				</div>

				<div class="biu-mt-10">
					<?php echo '<a id="matching-button" href="#" class="button button-primary disabled">' . esc_html__( 'Start Matching', 'bulk-image-upload' ) . '</a>'; ?>
					<img style="margin-top: 10px; display: none" id="loading-start-matching" width="10"
						 src="<?php echo esc_url( Bulk_Image_Upload_Folder::get_images_url() . 'loading.gif' ); ?>"/>
				</div>
			</div>

		<?php } ?>

		<script type="text/javascript">

			jQuery(document).ready(function () {

                jQuery('#autocomplete-input').autocomplete({
                    source: function(request, response) {
                        console.log(request.term);
                        var term = request.term.toLowerCase();
                        var options = [];
                        // Collect the options from the hidden select element
                        jQuery('#folder-select option').each(function() {
                            options.push({ label: jQuery(this).text().trim(), value: jQuery(this).val().trim() });
                        });

                        var matches = customFilter(options, term);
                        response(matches);
                    },
                    minLength: 1, // Auto-complete suggestions start immediately
                    select: function(event, ui) {
                        event.preventDefault();
                        // Set the selected option's value in the hidden input
                        jQuery('#selected-folder').val(ui.item.value);
                        jQuery('#autocomplete-input').val(ui.item.label);
                        jQuery("#matching-button").removeClass('disabled');
                    },
                });

				jQuery("#matching-button").click(function (e) {
					e.preventDefault();

                    if(jQuery("#matching-button").hasClass('disabled')){
                        alert("Please choose a folder");
                        return;
                    }

					jQuery("#matching-button").addClass('disabled');
					jQuery("#loading-start-matching").show();

					let folder_id = jQuery("#selected-folder").val();
					let matching_method = jQuery("#choose-matching-dropdown").val();
					let replacement_method = jQuery("#choose-replacement-dropdown").val();
					let folder_name = jQuery("#autocomplete-input").val();

					jQuery("#autocomplete-input").prop('disabled', 'disabled');
					jQuery("#choose-matching-dropdown").prop('disabled', 'disabled');
					jQuery("#choose-replacement-dropdown").prop('disabled', 'disabled');

					let url= "<?php echo esc_url(get_admin_url( null, 'admin.php?page=bulk-image-upload-matching-results' )); ?>&folder_id="+folder_id+"&matching_method="+matching_method+"&replacement_method="+replacement_method+"&folder_name="+encodeURIComponent(folder_name);
					window.location=url;
				});

                function customFilter(options, term) {
                    term = term.toLowerCase();
                    return options.filter(function(option) {
                        return option.label.toLowerCase().indexOf(term) !== -1;
                    });
                }
			});
		</script>
	</div>
</div>
