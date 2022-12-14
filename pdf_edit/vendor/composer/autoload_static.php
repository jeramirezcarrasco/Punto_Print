<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita2a34f2b6f01f52b449606bf7ff360af
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'setasign\\Fpdi\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita2a34f2b6f01f52b449606bf7ff360af::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita2a34f2b6f01f52b449606bf7ff360af::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita2a34f2b6f01f52b449606bf7ff360af::$classMap;

        }, null, ClassLoader::class);
    }
}
