<?php
namespace app\controllers\user;

use app\containers\App;
use app\models\User;
use app\models\Wallet;
use app\repositories\UserRepository;
use app\repositories\WalletRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User creation together with a wallet
 * @post [user => [login => test], wallet => [currency_id => 1, value => 10.10]]
 */
class Create
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $uRepo = new UserRepository(App::getInstance()->getDbConnection());
        $wRepo = new WalletRepository(App::getInstance()->getDbConnection());
        $post = $request->getParsedBody();
        if (!isset($post['user'], $post['wallet']) || !is_array($post['user']) || !is_array($post['wallet'])) {
            $response = $response->withStatus(400, 'Bad request');

            return $response;
        }

        $user = new User();
        $wallet = new Wallet();
        foreach ($post['user'] as $key => $value) {
            if (property_exists($user, $key)) {
                $user->$key = $value;
            }
        }
        foreach ($post['wallet'] as $key => $value) {
            if (property_exists($wallet, $key)) {
                $wallet->$key = $value;
            }
        }

        if ($uRepo->createOneWithWallet($user, $wallet, $wRepo)) {
            $response = $response->withStatus(201, 'Created');
        } else {
            $response = $response->withStatus(422, 'Unprocessable Entity');
        }

        return $response;
    }
}
