<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('bulk_image_upload_security_key');