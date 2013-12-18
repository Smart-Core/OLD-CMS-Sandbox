<?php
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

define('APPKERNEL_DEBUG', true);
//define('APPKERNEL_DEBUG', false);
//\Profiler::enable();

use Symfony\Component\HttpFoundation\Request;

$kernel = new SmartCore\AppKernel('prod', APPKERNEL_DEBUG);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
\Profiler::end('Response');
$kernel->terminate($request, $response);

if ($response->headers->contains('content-type', 'text/html; charset=UTF-8') and !$response->isRedirection()) {
    \Profiler::render();
    //\Profiler::dump(get_included_files(), dirname(__DIR__), '\var\cache\\');
}
