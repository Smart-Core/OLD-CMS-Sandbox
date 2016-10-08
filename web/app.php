<?php
define('APPKERNEL_DEBUG', true);
//define('APPKERNEL_DEBUG', false);

define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());
define('DIR_WEB', getcwd());

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

\Profiler::enable();

$kernel = new AppKernel('prod', APPKERNEL_DEBUG);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
