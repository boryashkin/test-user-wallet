<?php
namespace app\controllers\analytic\walletTransaction;

use app\containers\App;
use app\repositories\WalletTransactionRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @get reason => refund, target_currency_id => 1
 */
class SumOfTransactions
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!isset($_GET['reason'], $_GET['target_currency_id'])) {
            $response = $response->withStatus(422, 'Bad request');

            return $response;
        }
        $repo = new WalletTransactionRepository(App::getInstance()->getDbConnection());
        $sum = $repo->findSumOfTransactionsByReason($_GET['reason'], $_GET['target_currency_id']);
        $response->getBody()->write($sum ?? "0");

        return $response;
    }
}
