<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ErrorTemplate {

	public static function showErrorTemplate( $errorMessage ) {
		load_template(
			plugin_dir_path( __FILE__ ) . '../admin/partials/bulk-image-upload-error.php',
			true,
			array(
				'error' => __( $errorMessage, 'bulk_image_upload' ),
			)
		);
		exit;
	}
}
