<?php
namespace app\controllers\user;

use app\containers\App;
use app\controllers\BasicIndex;
use app\repositories\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Index extends BasicIndex
{
    public static $modelRepositoryClass = UserRepository::class;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $repo = new static::$modelRepositoryClass(App::getInstance()->getDbConnection());
        $models = $repo->findAllWithWallet();
        $response->getBody()->write(\json_encode($models));

        return $response;
    }
}