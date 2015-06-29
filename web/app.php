<?php
define('APPKERNEL_DEBUG', true);
//define('APPKERNEL_DEBUG', false);

define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once __DIR__.'/../var/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

\Profiler::enable();

$kernel = new AppKernel('prod', APPKERNEL_DEBUG);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
