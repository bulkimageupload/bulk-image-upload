<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Folder
{
    public static function getImagesUrl()
    {
        return trailingslashit(plugin_dir_url(__FILE__)) . '../admin/assets/images/';
    }
}