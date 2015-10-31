<?php
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/autoload.php';

$kernel = new AppKernel('test', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
