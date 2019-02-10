<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$request = Request::createFromGlobals();
$containerBuilder = new ContainerBuilder();


$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
    . 'app' . DIRECTORY_SEPARATOR . 'config'));
$loader->load('services.yaml');

Framework\Registry::addContainer($containerBuilder);
$app = new Kernel($containerBuilder);
$response = $app->handle($request);
$response->send();
