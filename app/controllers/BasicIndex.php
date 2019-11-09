<?php
namespace app\controllers;

use app\containers\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BasicIndex
{
    /** @var string */
    public static $modelRepositoryClass;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $repo = new static::$modelRepositoryClass(App::getInstance()->getDbConnection());
        $models = $repo->findAll();
        $response->getBody()->write(\json_encode($models));

        return $response;
    }
}
