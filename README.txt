=== Bulk Image Upload for WooCommerce ===
Contributors: faridmovsumov
Tags: woocommerce, bulk upload, product images
Requires at least: 5.3
Tested up to: 6.2
Requires PHP: 7.2
Stable tag: 2.1.36
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

This extension allows customers to bulk upload product images to their products in WooCommerce using Google Drive.

== Description ==

You basically upload all your photos into the folder inside Google Drive™ and then specify this folder inside our app.
Your photo names should have the same names as your product or variant SKUs. Then app shows you the matching results and if you approve we start uploading automatically.

= Name your product photos using the product’s variant SKU =
If you want to assign multiple images to one variant, specify a position after underscore. Example: SKU.jpg SKU_2.jpg...

= Check matching results =
App will show you all the matching results immediately and you can see clearly if we couldn't match some images.

= Start automatic upload =
If everything looks good you can simply click on "Start Upload" and we will upload all images automatically. While upload in progress you will be able to clearly see all details in our user-friendly logs.

== Installation ==
1. Upload “bulk-image-upload-for-woocommerce” to the “/wp-content/plugins/” directory.
2. Activate the plugin through the “Plugins” menu in WordPress.
3. Click on “Bulk Image Upload” under the WooCommerce menu.
4. Allow Bulk Image Upload service to connect to WooCommerce Rest API by clicking the “Connect” button on the main page.
5. Connect your Google Drive account and give permission to read images. Select all asked permissions manually.
6. Click “Create Upload Job” to create your first upload.
7. In the next screen, choose a folder and matching method.
8. Click the “Start Matching” button to see the matching results.
9. If unsatisfied with matching results, you can fix SKU or image names and click the “Try Again” button. If you are happy with the matching, you can start the upload process by clicking the “Start Upload” button.
10. You will be redirected to the dashboard, where you can see an overview of all uploads and the details of uploads by clicking on the “Show Logs” button for the specific upload you are interested in.

== Screenshots ==

1. Connect to Bulk Image Upload
2. Bulk Image Upload dashboard.

== Upgrade Notice ==

= 1.0 =
* First release
