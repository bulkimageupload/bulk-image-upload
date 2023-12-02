<?php
/**
 * Removing option which was added to options during activation.
 *
 * @package bulk-image-upload
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
