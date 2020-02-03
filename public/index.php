<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$modules = [
    ArcHive\Api\ApiModule::class
];

$app = new TurboPancake\App('config/config.php', $modules);
$container = $app->getContainer();

$app
    ->trough(Middlewares\Whoops::class)
    ->trough(TurboPancake\Middlewares\TralingSlashMiddleware::class)
    ->trough(TurboPancake\Middlewares\MethodDetectorMiddleware::class)
    ->trough(ArcHive\CsrfMiddleware::class)
    ->trough(TurboPancake\Middlewares\RouterMiddleware::class)
    ->trough(TurboPancake\Middlewares\DispatcherMiddleware::class)
    ->trough(TurboPancake\Middlewares\NotFoundMiddleware::class)
;

if (php_sapi_name() !== 'cli') {
    $response = $app->run(GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
