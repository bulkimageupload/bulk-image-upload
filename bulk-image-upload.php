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

	add_submenu_page(
		null,
		'Matching Results',
		'Matching Results',
		'manage_woocommerce',
		'bulk-image-upload-matching-results',
		'bulk_image_upload_render_matching_results'
	);

	add_submenu_page(
		null,
		'Remove Google Drive Connection',
		'Remove Google Drive Connection',
		'manage_woocommerce',
		'bulk-image-upload-remove-google-drive-connection',
		'bulk_image_upload_remove_google_drive_connection'
	);

	add_submenu_page(
		null,
		'Send Upload Request',
		'Send Upload Request',
		'manage_woocommerce',
		'bulk-image-upload-send-upload-request',
		'bulk_image_upload_send_upload_request'
	);
}

/**
 * The function sending request to the Bulk Image Upload service to start upload job
 *
 * @return void
 */
function bulk_image_upload_send_upload_request() {
	if ( ! bulk_image_upload_is_woocommerce_plugin_active() ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'WooCommerce plugin needs to be installed.' );
	}

	if ( empty( $_GET['matching_hash'] ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'matching_hash is mandatory parameter.' );
	}

	$matching_hash = sanitize_text_field($_GET['matching_hash']);

	$domain = get_site_url();
	$key    = get_option( 'bulk_image_upload_security_key' );

	$upload_request_endpoint_url = 'https://bulkimageupload.com/woo-commerce/upload?domain=' . urlencode( $domain ) . '&key=' . urlencode( $key ) . '&hash=' . urlencode( $matching_hash );

	$response = wp_remote_request( $upload_request_endpoint_url, [
		'method' => 'POST'
	]);

	if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Error while connecting to Bulk Image Upload service, please try again.' );
	}

	$dashboard_url = get_admin_url( null, 'admin.php?page=bulk-image-upload' );
	wp_redirect( $dashboard_url );
	exit;
}

/**
 * The function sending request to the Bulk Image Upload service to remove Google Drive connection.
 *
 * @return void
 */
function bulk_image_upload_remove_google_drive_connection() {
	if ( ! bulk_image_upload_is_woocommerce_plugin_active() ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'WooCommerce plugin needs to be installed.' );
	}

	$domain = get_site_url();
	$key    = get_option( 'bulk_image_upload_security_key' );

	$remove_google_drive_connection_endpoint_url = 'https://bulkimageupload.com/google-drive?domain=' . urlencode( $domain ) . '&key=' . urlencode( $key );

	$response = wp_remote_request( $remove_google_drive_connection_endpoint_url, [
		'method' => 'DELETE'
	] );

	if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Error while connecting to Bulk Image Upload service, please try again.' );
	}

	$dashboard_url = get_admin_url( null, 'admin.php?page=bulk-image-upload' );
	wp_redirect( $dashboard_url );
	exit;
}

/**
 * This is backend logic of displaying image matching results to the user.
 * It is fetching matching results from service and displaying to the user.
 *
 * @return void
 */
function bulk_image_upload_render_matching_results() {
	if ( ! bulk_image_upload_is_woocommerce_plugin_active() ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'WooCommerce plugin needs to be installed.' );
	}

	if ( empty( $_GET['folder_id'] ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'folder_id is mandatory parameter.' );
	}

	if ( empty( $_GET['matching_method'] ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'matching_method is mandatory parameter.' );
	}

	if ( empty( $_GET['folder_name'] ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'folder_name is mandatory parameter.' );
	}

	$folder_id       = sanitize_text_field($_GET['folder_id']);
	$folder_name     = sanitize_text_field($_GET['folder_name']);
	$matching_method = sanitize_text_field($_GET['matching_method']);
	$domain          = get_site_url();
	$key             = get_option( 'bulk_image_upload_security_key' );

	if ( empty( $key ) ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Security Key not found. Please reactivate the app to fix the issue.' );
	}

	$matching_endpoint_url = 'https://bulkimageupload.com/woo-commerce/match-images?domain=' . urlencode( $domain ) . '&key=' . urlencode( $key ) . '&folderKey=' . urlencode( $folder_id ) . '&matchingMethod=' . urlencode( $matching_method ) . '&folderName=' . urlencode( $folder_name );

	$response = wp_remote_get( $matching_endpoint_url, ['timeout' => 3600] );

	if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Error while connecting to Bulk Image Upload service, please try again.' );
	}

	$body             = wp_remote_retrieve_body( $response );
	$matching_results = json_decode( $body, true );

	/*
	$matching_results = [
		'hash' => uniqid(),
		'matched_images' => [
			[
				'thumbnailUrl' => 'https://botmake.io/storage/24c9e15e52afc47c225b757e7bee1f9d/users/7d2ca05e3c2770f4ce2a8456d2ee639c.jpg?v=1684389620',
				'name' => 'deneme.jpg',
				'size' => '5KB',
				'position' => '1',
				'productUrl' => 'https://botmake.io/storage/24c9e15e52afc47c225b757e7bee1f9d/users/7d2ca05e3c2770f4ce2a8456d2ee639c.jpg?v=1684389620',
				'productName' => 'deneme / red',
				'sku' => 'deneme',
			],
			[
				'thumbnailUrl' => 'https://botmake.io/storage/24c9e15e52afc47c225b757e7bee1f9d/users/7d2ca05e3c2770f4ce2a8456d2ee639c.jpg?v=1684389620',
				'name' => 'deneme.jpg',
				'size' => '5KB',
				'position' => '1',
				'productUrl' => 'https://botmake.io/storage/24c9e15e52afc47c225b757e7bee1f9d/users/7d2ca05e3c2770f4ce2a8456d2ee639c.jpg?v=1684389620',
				'productName' => 'deneme / red',
				'sku' => 'deneme',
			]
		],
		'non_matched_images' => [
			[
				'thumbnailUrl' => 'https://botmake.io/storage/24c9e15e52afc47c225b757e7bee1f9d/users/7d2ca05e3c2770f4ce2a8456d2ee639c.jpg?v=1684389620',
				'name' => 'olmadi.jpg',
				'size' => '8kb',
				'expectedSku' => 'olmadi',
			]
		]
	];
	*/

	//echo "<pre>";var_dump($matching_results);exit;

	load_template(
		plugin_dir_path( __FILE__ ) . 'admin/partials/bulk-image-upload-matching-results.php',
		true,
		array(
			'matching_results' => $matching_results,
		)
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

	$response = wp_remote_get( 'https://bulkimageupload.com/google-drive/folders?domain=' . urlencode( $domain ) . '&key=' . urlencode( $key ) );

	if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Error while connecting to Bulk Image Upload service, please try again.' );
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	load_template(
		plugin_dir_path( __FILE__ ) . 'admin/partials/bulk-image-upload-create-new-upload.php',
		true,
		array(
			'folders' => $data['folders'],
			'matching_methods' => $data['matchingMethods']
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

	$matching_hash = sanitize_text_field($_GET['job_id']);

	$domain = get_site_url();
	$key    = get_option( 'bulk_image_upload_security_key' );

	$upload_job_logs_endpoint_url = 'https://bulkimageupload.com/api/matchings/' . $matching_hash . '?domain=' . urlencode( $domain ) . '&key=' . urlencode( $key );

	$response = wp_remote_request( $upload_job_logs_endpoint_url );

	if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
		Bulk_Image_Upload_Error_Template::show_error_template( 'Error while connecting to Bulk Image Upload service, please try again.' );
	}

	$body       = wp_remote_retrieve_body( $response );
	$body_array = json_decode( $body, true );

	echo '<pre>';
var_dump($body_array);
exit;

	load_template(
		plugin_dir_path( __FILE__ ) . 'admin/partials/bulk-image-upload-job-logs.php',
		true,
		array(
			'job' => $body_array
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

	$shop_data = array(
		'domain'      => $domain,
		'key'         => $key,
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
	$css_version = '2';
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
