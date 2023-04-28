<?php

/**
 * Plugin Name: Bulk Image Upload for WooCommerce
 * Plugin URI: https://bulkimageupload.com
 * Description: This extension allows customers to bulk upload product images to their products in WooCommerce using Google Drive.
 * Version: 1.0.0
 * Author: Bulk Image Upload
 * Author URI: https://bulkimageupload.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least: 3.0.0
 * WC tested up to: 5.7.1
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

function bulk_image_upload_register_menu_page()
{
    add_menu_page(
        'Bulk Image Upload',
        'Bulk Image Upload',
        'manage_options',
        'bulk-image-upload',
        'bulk_image_upload_render_plugin_page',
        'dashicons-upload'
    );

    add_submenu_page(
        null,
        'Create New Upload',
        esc_html('Create New Upload'),
        'manage_options',
        'bulk-image-upload-create-new-upload',
        'bulk_image_upload_render_create_new_upload_page'
    );
}

add_action('admin_menu', 'bulk_image_upload_register_menu_page');

function bulk_image_upload_render_create_new_upload_page()
{
    load_template(plugin_dir_path(__FILE__) . 'includes/templates/create-new-upload.php', true, [
        'folders' => array(
            'test1',
            'test2',
            'test3',
        ),
    ]);
}

function bulk_image_upload_render_plugin_page()
{
    $domain = get_site_url();
    $key = get_option('bulk_image_upload_security_key');

    //Connect here to service and get the information about existing connection status.

    $last_uploads = [
        [
            'ID' => 4900,
            'Upload Job' => 'test1-05-03-2022-04-28',
            'Status' => 'finished',
            'Total' => 'uploaded',
            'Created' => '2022-03-05 04:28:46',
        ],
        [
            'ID' => 4901,
            'Upload Job' => 'test2-05-03-2022-04-28',
            'Status' => 'finished',
            'Total' => 'uploaded',
            'Created' => '2022-03-05 05:28:46',
        ]
    ];

    load_template(plugin_dir_path(__FILE__) . 'includes/templates/dashboard.php', true, [
        'domain' => $domain,
        'key' => $key,
        'is_connected_to_service' => true,
        'is_connected_to_drive' => true,
        'is_upload_created' => true,
        'last_uploads' => $last_uploads,
    ]);
}

function register_styles()
{
    $css_version = '1';
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style('style', $plugin_url . '/assets/css/style.css?version=' . $css_version);
}

add_action('admin_print_styles', 'register_styles');

// Register the activation hook to run code when the plugin is activated
register_activation_hook(__FILE__, 'bulk_image_upload_activation_hook');

function bulk_image_upload_activation_hook()
{
    // Generate a random key
    $key = bin2hex(random_bytes(128));

    // Save the key to the options table
    update_option('bulk_image_upload_security_key', $key);
}