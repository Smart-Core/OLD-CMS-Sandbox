<?php
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());
define('DIR_WEB', getcwd());
define('APPKERNEL_DEBUG', true);
//define('APPKERNEL_DEBUG', false);

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('prod', APPKERNEL_DEBUG);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
