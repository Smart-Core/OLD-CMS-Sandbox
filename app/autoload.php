<?php
if (version_compare(PHP_VERSION, '5.4', '>=') && gc_enabled()) {
    // Disabling Zend Garbage Collection to prevent segfaults with PHP5.4+
    // https://bugs.php.net/bug.php?id=53976
    gc_disable();
}

/**
 * @var $loader \Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

// Кеширование автозагрузчика.
if (function_exists('apc_store') and ini_get('apc.enabled')) {
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

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
}

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
