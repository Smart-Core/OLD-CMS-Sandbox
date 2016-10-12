<?php

if (!defined('START_TIME')) {
    define('START_TIME', microtime(true));
}
if (!defined('START_MEMORY')) {
    define('START_MEMORY', memory_get_usage());
}

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

// Autodetect autoloader cacheing.
if (function_exists('apcu_fetch') and ini_get('apc.enabled')) {
    $loader = new \Symfony\Component\ClassLoader\ApcClassLoader(md5(__FILE__), $loader);
    $loader->register(true);
} else if (function_exists('wincache_ucache_set')) {
    $loader = new \Symfony\Component\ClassLoader\WinCacheClassLoader(md5(__FILE__), $loader);
    $loader->register(true);
} else if ((PHP_SAPI != 'cli' || (isset($_SERVER['DOCUMENT_ROOT']) && isset($_SERVER['REQUEST_URI'])))
    and function_exists('xcache_set') and (int) ini_get('xcache.var_size') > 0
) {
    $loader = new \Symfony\Component\ClassLoader\XcacheClassLoader(md5(__FILE__), $loader);
    $loader->register(true);
}

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
