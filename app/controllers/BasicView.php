<?php
namespace app\controllers;

use app\containers\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BasicView
{
    /** @var string */
    public static $modelRepositoryClass;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$request->getAttribute('id')) {
            $response = $response->withStatus(422, 'Unprocessable Entity');
            $response->getBody()->write('id is required');

            return $response;
        }

        $repo = new static::$modelRepositoryClass(App::getInstance()->getDbConnection());
        $model = $repo->findOne($request->getAttribute('id'));
        if (!$model) {
            $response = $response->withStatus(404, 'Not Found');

            return $response;
        }
        $response->getBody()->write(\json_encode($model));

        return $response;
    }
}
