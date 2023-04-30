<?php
/**
 * Removing option which was added to options during activation.
 *
 * @package bulk-image-upload-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'bulk_image_upload_security_key' );
