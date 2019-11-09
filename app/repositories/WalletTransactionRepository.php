<?php
namespace app\repositories;

use app\models\WalletTransaction;

class WalletTransactionRepository extends MysqlRepository
{
    public static $modelClass = WalletTransactionRepository::class;
    public static $table = 'wallet_transaction';

    public function createOne(
        WalletTransaction $walletTransaction,
        WalletRepository $wRepository,
        CurrencyRateRepository $crRepository
    )
    {
        $this->getConnection()->beginTransaction();
        $applied = false;
        try {
            $wallet = $wRepository->findOneForUpdate($walletTransaction->wallet_id);
            $cRate = $crRepository->findLatestRateForCurrency($wallet->currency_id, $walletTransaction->currency_id);
            $walletTransaction->currency_rate_id = $cRate->id;
            $this->insertOne($walletTransaction);
            $applied = $wRepository->applyTransaction($wallet, $walletTransaction, $crRepository);
        } catch (\PDOException $e) {
            $this->getConnection()->rollBack();
            throw $e;
        }

        if ($applied) {
            $this->getConnection()->commit();
        } else {
            $this->getConnection()->rollBack();
        }

        return $applied;
    }

    private function insertOne(WalletTransaction $walletTransaction)
    {
        $stmt = $this->getConnection()
            ->prepare(
                'INSERT INTO '
                . self::$table
                . ' (wallet_id, currency_id, currency_rate_id, value, reason_id) VALUES '
                . ' (:wallet_id, :currency_id, :currency_rate_id, :value, :reason_id)'
            );
        $stmt->bindValue(':wallet_id', $walletTransaction->wallet_id);
        $stmt->bindValue(':currency_id', $walletTransaction->currency_id);
        $stmt->bindValue(':currency_rate_id', $walletTransaction->currency_rate_id);
        $stmt->bindValue(':value', $walletTransaction->value);
        $stmt->bindValue(':reason_id', $walletTransaction->reason_id);
        $ex = $stmt->execute();
        if (!$ex) {
            error_log(print_r($stmt->errorInfo(), true));
        }

        return $ex ? $this->getConnection()->lastInsertId() : 0;
    }
}
