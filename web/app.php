<?php
define('APPKERNEL_DEBUG', true);
//define('APPKERNEL_DEBUG', false);

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
include_once __DIR__.'/../var/bootstrap.php.cache';
$loader = require __DIR__.'/../app/autoload.php';

\Profiler::enable();

$kernel = new AppKernel('prod', APPKERNEL_DEBUG);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
