<?php
namespace app\controllers\walletTransaction;

use app\containers\App;
use app\models\WalletTransaction;
use app\repositories\CurrencyRateRepository;
use app\repositories\WalletRepository;
use app\repositories\WalletTransactionRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * User creation together with a wallet
 * @post [transaction => [wallet_id => 1, currency_id => 1, value => 12.1, reason_id => 1]]
 */
class Create
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $wtRepo = new WalletTransactionRepository(App::getInstance()->getDbConnection());
        $wRepo = new WalletRepository(App::getInstance()->getDbConnection());
        $crRepo = new CurrencyRateRepository(App::getInstance()->getDbConnection());
        if (!isset($_POST['transaction']) || !is_array($_POST['transaction'])) {
            $response = $response->withStatus(400, 'Bad request');

            return $response;
        }

        $walletTransaction = new WalletTransaction();
        foreach ($_POST['transaction'] as $key => $value) {
            if (property_exists($walletTransaction, $key)) {
                $walletTransaction->$key = $value;
            }
        }

        if ($wtRepo->createOne($walletTransaction, $wRepo, $crRepo)) {
            $response = $response->withStatus(201, 'Created');
        } else {
            $response = $response->withStatus(422, 'Unprocessable Entity');
        }

        return $response;
    }
}
