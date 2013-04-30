<?php
/**
 * @var $loader \Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

// Кеширование автозагрузчика.
$loader_prefix = 'sf2.';
if (function_exists('apc_store') and ini_get('apc.enabled')) {
    $loader = new \Symfony\Component\ClassLoader\ApcClassLoader($loader_prefix, $loader);
    $loader->register(true);
} else if (function_exists('wincache_ucache_set') and class_exists('\Symfony\Component\ClassLoader\WinCacheClassLoader')) {
    $loader = new \Symfony\Component\ClassLoader\WinCacheClassLoader($loader_prefix, $loader);
    $loader->register(true);
}

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
}

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
