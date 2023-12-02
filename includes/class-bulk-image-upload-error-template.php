<?php
/**
 * The class to help with error display
 *
 * @package bulk-image-upload
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * This class helps to display errors to user.
 */
class Bulk_Image_Upload_Error_Template {

	/**
	 * The function loading error template and passing error variable to show to end user.
	 *
	 * @param string $error_message The message to show to the user.
	 *
	 * @return void
	 */
	public static function show_error_template( $error_message ) {
		load_template(
			plugin_dir_path( __FILE__ ) . '../admin/partials/bulk-image-upload-error.php',
			true,
			array(
				'error' => $error_message,
			)
		);
		exit;
	}
}
