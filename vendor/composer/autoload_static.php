<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3a2f3b60e4976ba68093ccf03eae0fe0
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lcw\\Activitylog\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lcw\\Activitylog\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3a2f3b60e4976ba68093ccf03eae0fe0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3a2f3b60e4976ba68093ccf03eae0fe0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3a2f3b60e4976ba68093ccf03eae0fe0::$classMap;

        }, null, ClassLoader::class);
    }
}
