<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9916016a800ace760310e785f0f59b8d
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Dealerdirect\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 55,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Dealerdirect\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 
        array (
            0 => __DIR__ . '/..' . '/dealerdirect/phpcodesniffer-composer-installer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9916016a800ace760310e785f0f59b8d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9916016a800ace760310e785f0f59b8d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9916016a800ace760310e785f0f59b8d::$classMap;

        }, null, ClassLoader::class);
    }
}