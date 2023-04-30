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
 * Requires PHP: 7.4
 * Text Domain: bulk-image-upload
 * Developer: Bulk Image Upload
 * Woo:
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('BULK_IMAGE_UPLOAD_VERSION', '1.0.0');

require_once __DIR__ . '/includes/class-bulk-image-upload-status-color.php';
require_once __DIR__ . '/includes/class-bulk-image-upload-error-template.php';
require_once __DIR__ . '/includes/class-bulk-image-upload-folder.php';

// Register the activation hook to run code when the plugin is activated
register_activation_hook(__FILE__, 'bulk_image_upload_activation_hook');

add_action('admin_menu', 'bulk_image_upload_register_menu_page');

add_action('admin_print_styles', 'register_styles');

function bulk_image_upload_activation_hook()
{
    $key = get_option('bulk_image_upload_security_key');

    if (empty($key)) {
        // Generate a random key
        $key = bin2hex(random_bytes(72));

        // Save the key to the options table
        update_option('bulk_image_upload_security_key', $key);
    }
}

function bulk_image_upload_register_menu_page()
{
    add_menu_page(
        'Bulk Image Upload',
        'Bulk Image Upload',
        'manage_woocommerce',
        'bulk-image-upload',
        'bulk_image_upload_render_plugin_page',
        'dashicons-upload'
    );

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

function bulk_image_upload_render_create_new_upload_page()
{
    if (!is_woocommerce_plugin_active()) {
        ErrorTemplate::showErrorTemplate('WooCommerce plugin needs to be installed.');
    }

    load_template(plugin_dir_path(__FILE__) . 'admin/partials/bulk-image-upload-create-new-upload.php', true, [
        'folders' => [
            'test1',
            'test2',
            'test3',
        ],
    ]);
}

function bulk_image_upload_render_job_logs()
{
    if (empty($_GET['job_id'])) {
        ErrorTemplate::showErrorTemplate('Job ID is mandatory');
    }

    $job_id = $_GET['job_id'];

    //Ask service information about job.

    load_template(plugin_dir_path(__FILE__) . 'admin/partials/bulk-image-upload-job-logs.php', true, [
        'job' => [
            'id' => 4903,
            'upload_job' => 'test2-05-03-2022-04-28',
            'status' => 'finished',
            'total' => 15,
            'uploaded' => 15,
            'created' => '2022-03-05 05:28:46',
        ],
    ]);
}

function bulk_image_upload_render_plugin_page()
{
    if (!is_woocommerce_plugin_active()) {
        ErrorTemplate::showErrorTemplate('WooCommerce plugin needs to be installed.');
    }

    $domain = get_site_url();
    $key = get_option('bulk_image_upload_security_key');

    if (empty($key)) {
        ErrorTemplate::showErrorTemplate('Security Key not found. Please reinstall the app to fix the issue.');
    }

    //Connect here to service and get the information about existing connection status.
    //https://developer.wordpress.org/apis/making-http-requests/


    //sorted by newest first.
    $last_uploads = [
        [
            'id' => 4905,
            'upload_job' => 'test1-05-03-2022-04-28',
            'status' => 'pending',
            'total' => 15,
            'uploaded' => 0,
            'created' => '2022-03-05 04:28:46',
        ],
        [
            'id' => 4904,
            'upload_job' => 'test1-05-03-2022-04-28',
            'status' => 'running',
            'total' => 15,
            'uploaded' => 3,
            'created' => '2022-03-05 04:28:46',
        ],
        [
            'id' => 4903,
            'upload_job' => 'test2-05-03-2022-04-28',
            'status' => 'finished',
            'total' => 15,
            'uploaded' => 15,
            'created' => '2022-03-05 05:28:46',
        ],
        [
            'id' => 4902,
            'upload_job' => 'test2-05-03-2022-04-28',
            'status' => 'finished_with_errors',
            'total' => 15,
            'uploaded' => 14,
            'created' => '2022-03-05 05:28:46',
        ],
        [
            'id' => 4901,
            'upload_job' => 'test2-05-03-2022-04-28',
            'status' => 'failed',
            'total' => 15,
            'uploaded' => 0,
            'created' => '2022-03-05 05:28:46',
        ]
    ];

    load_template(plugin_dir_path(__FILE__) . 'admin/partials/bulk-image-upload-dashboard.php', true, [
        'domain' => $domain,
        'key' => $key,
        'is_connected_to_service' => true,
        'is_connected_to_drive' => true,
        'is_upload_created' => false,
        'uploads' => $last_uploads,
        'total_uploaded' => 546,
    ]);
}

function is_woocommerce_plugin_active()
{
    // Test to see if WooCommerce is active (including network activated).
    $plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';

    if (
        in_array($plugin_path, wp_get_active_and_valid_plugins())
        || in_array($plugin_path, wp_get_active_network_plugins())
    ) {
        return true;
    }

    return false;
}

function register_styles()
{
    $css_version = '1';
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style('style', $plugin_url . '/admin/assets/css/style.css?version=' . $css_version);
}