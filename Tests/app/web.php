<?php
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/autoload.php';

$kernel = new AppKernel('test', false);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();