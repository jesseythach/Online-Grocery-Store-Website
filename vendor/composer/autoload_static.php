<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite9b78ff37e92c7146308e9090a87f40d
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInite9b78ff37e92c7146308e9090a87f40d::$classMap;

        }, null, ClassLoader::class);
    }
}
