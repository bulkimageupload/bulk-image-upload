<?php
/**
 * Plugin Name: Bulk Image Upload for WooCommerce
 * Plugin URI: https://bulkimageupload.com
 * Description: This extension allows customers to bulk upload product images to their products in WooCommerce using Google Drive.
 * Version: 1.0.0
 * Author: Bulk Image Upload
 * Author URI: https://bulkimageupload.com
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * WC requires at least: 3.0.0
 * WC tested up to: 6.2
 * Requires PHP: 7.2
 * Text Domain: bulk-image-upload
 * Developer: Bulk Image Upload
 * Developer URI: https://bulkimageupload.com
 * Woo:
 *
 * @package bulk-image-upload-for-woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BULK_IMAGE_UPLOAD_VERSION', '1.0.0' );

require_once __DIR__ . '/includes/class-bulk-image-upload-status-color.php';
require_once __DIR__ . '/includes/class-bulk-image-upload-error-template.php';
require_once __DIR__ . '/includes/class-bulk-image-upload-folder.php';

// Register the activation hook to run code when the plugin is activated.
register_activation_hook( __FILE__, 'bulk_image_upload_activation_hook' );

add_action( 'admin_menu', 'bulk_image_upload_register_menu_page' );
add_action( 'admin_print_styles', 'bulk_image_upload_register_styles' );
add_action( 'admin_init', 'bulk_image_upload_redirect_to_onboarding_page' );
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'add_plugin_action_links' );

/**
 * Performs actions after activation of plugin.
 *
 * @return void
 */
function bulk_image_upload_activation_hook() {
	$key = get_option( 'bulk_image_upload_security_key' );

	if ( empty( $key ) ) {
		// Generate a random key.
		$key = bin2hex( random_bytes( 72 ) );

		// Save the key to the options table.
		update_option( 'bulk_image_upload_security_key', $key );
	}

	add_option( 'bulk_image_upload_do_activation_redirect', true );
}

/**
 * Registering all menu items here.
 *
 * @return void
 */
function bulk_image_upload_register_menu_page() {

	add_submenu_page(
		'woocommerce',
		'Bulk Image Upload',
		'Bulk Image Upload',
		'manage_woocommerce',
		'bulk-image-upload',
		'bulk_image_upload_render_plugin_page'
	);

	add_submenu_page(
		null,
		'Create New Upload',
		'Create New Upload',
		'manage_woocommerce',
		'bulk-image-upload-create-new-upload',
		'bulk_image_upload_render_create_new_upload_page'
	);

	add_submenu_page(
		null,
		'Job Logs',
		'Job Logs',
		'manage_woocommerce',
		'bulk-image-upload-job-logs',
		'bulk_image_upload_render_job_logs'
	);
}

/**
 * Backend logic of create new upload page.
 * Fetching folder information from service and displaying to user.
 *
 * @return void
 */
function bulk_image_upload_render_create_new_upload_page() {
	if ( ! bulk_image_upload_is_woocommerce_plugin_active() ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'WooCommerce plugin needs to be installed.' );
	}

	$domain = get_site_url();
	$key    = get_option( 'bulk_image_upload_security_key' );

	if ( empty( $key ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Security Key not found. Please reactivate the app to fix the issue.' );
	}

	$response = wp_remote_get( 'https://bulkimageupload.com/google-drive-list-folders?domain=' . urlencode( $domain ) . '&key=' . urlencode( $key ) );

	if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Error while connecting to Bulk Image Upload service, please try again.' );
	}

	$body      = wp_remote_retrieve_body( $response );
	$folders = json_decode( $body, true );

	load_template(
		plugin_dir_path( __FILE__ ) . 'admin/partials/bulk-image-upload-create-new-upload.php',
		true,
		array(
			'folders' => $folders,
		)
	);
}

/**
 * The function fetching job information from the service by job_id
 *
 * @return void
 */
function bulk_image_upload_render_job_logs() {
	if ( empty( $_GET['job_id'] ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Job ID is mandatory' );
	}

	$job_id = intval($_GET['job_id']);

	// Ask service information about job.

	load_template(
		plugin_dir_path( __FILE__ ) . 'admin/partials/bulk-image-upload-job-logs.php',
		true,
		array(
			'job' => array(
				'id'         => 4903,
				'upload_job' => 'test2-05-03-2022-04-28',
				'status'     => 'finished',
				'total'      => 15,
				'uploaded'   => 15,
				'created'    => '2022-03-05 05:28:46',
			),
		)
	);
}

/**
 * This is backend of dashboard page. All information being fetched from the service.
 *
 * @return void
 */
function bulk_image_upload_render_plugin_page() {
	if ( ! bulk_image_upload_is_woocommerce_plugin_active() ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'WooCommerce plugin needs to be installed.' );
	}

	$domain = get_site_url();
	$key    = get_option( 'bulk_image_upload_security_key' );

	if ( empty( $key ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Security Key not found. Please reactivate the app to fix the issue.' );
	}

	$response = wp_remote_get( 'https://bulkimageupload.com/woo-commerce/dashboard?domain=' . urlencode( $domain ) . '&key=' . urlencode( $key ) );

	if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Error while connecting to Bulk Image Upload service, please try again.' );
	}

	$body      = wp_remote_retrieve_body( $response );
	$body_json = json_decode( $body, true );

	// sorted by newest first.
	$last_uploads = array(
		array(
			'id'         => 4905,
			'upload_job' => 'test1-05-03-2022-04-28',
			'status'     => 'pending',
			'total'      => 15,
			'uploaded'   => 0,
			'created'    => '2022-03-05 04:28:46',
		),
		array(
			'id'         => 4904,
			'upload_job' => 'test1-05-03-2022-04-28',
			'status'     => 'running',
			'total'      => 15,
			'uploaded'   => 3,
			'created'    => '2022-03-05 04:28:46',
		),
		array(
			'id'         => 4903,
			'upload_job' => 'test2-05-03-2022-04-28',
			'status'     => 'finished',
			'total'      => 15,
			'uploaded'   => 15,
			'created'    => '2022-03-05 05:28:46',
		),
		array(
			'id'         => 4902,
			'upload_job' => 'test2-05-03-2022-04-28',
			'status'     => 'finished_with_errors',
			'total'      => 15,
			'uploaded'   => 14,
			'created'    => '2022-03-05 05:28:46',
		),
		array(
			'id'         => 4901,
			'upload_job' => 'test2-05-03-2022-04-28',
			'status'     => 'failed',
			'total'      => 15,
			'uploaded'   => 0,
			'created'    => '2022-03-05 05:28:46',
		),
	);

	$shop_data = array(
		'domain' => $domain,
		'key'    => $key,
	);

	$arguments = array_merge( $shop_data, $body_json );

	load_template(
		plugin_dir_path( __FILE__ ) . 'admin/partials/bulk-image-upload-dashboard.php',
		true,
		$arguments
	);
}

/**
 * This function helps to check if WooCommerce plugin installed and active.
 *
 * @return bool
 */
function bulk_image_upload_is_woocommerce_plugin_active() {
	// Test to see if WooCommerce is active (including network activated).
	$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

	if (
		in_array( $plugin_path, wp_get_active_and_valid_plugins() )
		|| in_array( $plugin_path, wp_get_active_network_plugins() )
	) {
		return true;
	}

	return false;
}

/**
 * This function is used to include css files into admin.
 *
 * @return void
 */
function bulk_image_upload_register_styles() {
	$css_version = '1';
	$plugin_url  = plugin_dir_url( __FILE__ );
	wp_enqueue_style( 'style', $plugin_url . '/admin/assets/css/style.css', array(), $css_version );
}

/**
 * This function helps with onboarding flow. It redirects user to initial page after activation of plugin.
 *
 * @return void
 */
function bulk_image_upload_redirect_to_onboarding_page() {
	if ( get_option( 'bulk_image_upload_do_activation_redirect', false ) ) {
		delete_option( 'bulk_image_upload_do_activation_redirect' );
		$bulk_image_upload_plugin_url = get_admin_url( null, 'admin.php?page=bulk-image-upload' );
		wp_redirect( $bulk_image_upload_plugin_url );
		exit;
	}
}

/**
 * This function is adding quick links which can be seen in installed plugins page.
 *
 * @param $links
 *
 * @return array
 */
function add_plugin_action_links( $links ) {

	$custom_links = array();

	$custom_links['get_started'] = sprintf(
		wp_kses(
		/* translators: %s Support url */
			__( '<a href="%s">Get Started</a>', 'bulk-image-upload' ),
			array( 'a' => array( 'href' => array() ) )
		),
		esc_url( get_admin_url( null, 'admin.php?page=bulk-image-upload' ) )
	);

	$custom_links['support'] = sprintf(
		wp_kses(
		/* translators: %s Support url */
			__( '<a href="%s">Support</a>', 'bulk-image-upload' ),
			array( 'a' => array( 'href' => array() ) )
		),
		esc_url( 'https://woocommerce.com/my-account/create-a-ticket/' )
	);

	return array_merge( $custom_links, $links );
}
