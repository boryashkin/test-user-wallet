<?php
namespace app\repositories;

use app\models\Wallet;
use app\models\WalletTransaction;

class WalletRepository extends MysqlRepository
{
    public static $modelClass = Wallet::class;
    public static $table = 'wallet';

    public function createOne(Wallet $wallet)
    {
        $stmt = $this->getConnection()->prepare('INSERT INTO ' . self::$table . ' (user_id, value, currency_id) VALUES (:user_id, :value, :currency_id)');
        $stmt->bindValue(':user_id', $wallet->user_id);
        $stmt->bindValue(':value', $wallet->value);
        $stmt->bindValue(':currency_id', $wallet->currency_id);

        return $stmt->execute() ? $this->getConnection()->lastInsertId() : 0;
    }

    public function updateOne(Wallet $wallet)
    {
        if (!$wallet->id) {
            throw new \PDOException('wallet.id is required for update');
        }
        //пока поддерживаем только изменение баланса
        $stmt = $this->getConnection()->prepare('UPDATE ' . self::$table . ' SET value = :value WHERE id = :id');
        $stmt->bindValue(':value', $wallet->value);
        $stmt->bindValue(':id', $wallet->id);

        return $stmt->execute();
    }

    public function applyTransaction(Wallet $wallet, WalletTransaction $walletTransaction, CurrencyRateRepository $crRepository)
    {
        $rate = 1;
        if ($wallet->currency_id !== $walletTransaction->currency_id) {
            $cRate = $crRepository->findLatestRateForCurrency($wallet->currency_id, $walletTransaction->currency_id);
            if (!$cRate) {
                throw new \PDOException('Currency rate not found: ' . $wallet->currency_id . ' -> ' . $walletTransaction->currency_id);
            }
            $rate = $cRate->rate;
        }
        $wallet->value += $walletTransaction->value * $rate;

        return $this->updateOne($wallet);
    }
}
