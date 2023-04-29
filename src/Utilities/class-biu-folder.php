<?php

class Folder
{
    public static function getImagesUrl()
    {
        return trailingslashit(plugin_dir_url(__FILE__)) . '../../assets/images/';
    }
}