<?php
/**
 * This class provides functionality to help with folder structure.
 *
 * @package bulk-image-upload-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * All the folder related functionalities.
 */
class Bulk_Image_Upload_Folder {

	/**
	 * This method provides path to images folder.
	 *
	 * @return string
	 */
	public static function get_images_url() {
		return trailingslashit( plugin_dir_url( __FILE__ ) ) . '../admin/assets/images/';
	}
}
