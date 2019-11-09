<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require __DIR__ . '/../vendor/autoload.php';


$app = \Slim\Factory\AppFactory::create();

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write(date('Y-m-d H:i:s'));
    return $response;
});
$app->get('/currencies', \app\controllers\currency\Index::class);
$app->get('/users', \app\controllers\user\Index::class);
$app->post('/users', \app\controllers\user\Create::class);
$app->get('/wallets/{id}', \app\controllers\wallet\View::class);
$app->post('/wallet-transactions', \app\controllers\walletTransaction\Create::class);
$app->addMiddleware(new \app\middlewares\HeaderJsonMiddleware());

$app->run();
