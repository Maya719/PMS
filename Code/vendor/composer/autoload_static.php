<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit60d4bb3edfefa7761be580ad4dbbf3fa
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit60d4bb3edfefa7761be580ad4dbbf3fa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit60d4bb3edfefa7761be580ad4dbbf3fa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit60d4bb3edfefa7761be580ad4dbbf3fa::$classMap;

        }, null, ClassLoader::class);
    }
}