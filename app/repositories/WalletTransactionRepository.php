<?php
namespace app\repositories;

use app\models\WalletTransaction;

class WalletTransactionRepository extends MysqlRepository
{
    public static $modelClass = WalletTransaction::class;
    public static $table = 'wallet_transaction';

    public function createOne(
        WalletTransaction $walletTransaction,
        WalletRepository $wRepository,
        CurrencyRateRepository $crRepository
    )
    {
        $this->getConnection()->beginTransaction();
        try {
            $wallet = $wRepository->findOneForUpdate($walletTransaction->wallet_id);
            $cRate = $crRepository->findLatestRateForCurrency($walletTransaction->currency_id, $wallet->currency_id);
            $walletTransaction->currency_rate_id = $cRate->id;
            $this->insertOne($walletTransaction);
            $applied = $wRepository->applyTransaction($wallet, $walletTransaction, $cRate ?: null);
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

    public function findSumOfTransactionsByReason($reason, $targetCurrencyId)
    {
        $stmt = $this->getConnection()->prepare(file_get_contents(__DIR__ . '/sql/refundSumForWeek.sql'));
        $stmt->bindValue(':reason_name', $reason);
        $stmt->bindValue(':target_currency', $targetCurrencyId);
        $ex = $stmt->execute();
        if (!$ex) {
            error_log(print_r($stmt->errorInfo(), true));
        }

        return $ex ? $stmt->fetchColumn(0) : 0;
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
