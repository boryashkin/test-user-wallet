<?php
namespace app\repositories;

use app\models\CurrencyRate;

class CurrencyRateRepository extends MysqlRepository
{
    public static $modelClass = CurrencyRate::class;
    public static $table = 'currency_rate';

    public function findLatestRateForCurrency($currencyId, $toCurrencyId)
    {
        $stmt = $this->getConnection()
            ->prepare(
                'SELECT * FROM '
                . self::$table
                . ' WHERE created_at <= NOW() AND currency_id = :currency_id  AND to_currency_id = :to_currency_id '
                . ' ORDER BY created_at DESC LIMIT 1'
            );
        $stmt->bindValue(':currency_id', $currencyId);
        $stmt->bindValue(':to_currency_id', $toCurrencyId);
        $stmt->execute();

        return $stmt->fetchObject(CurrencyRate::class);
    }

    public function createOne(CurrencyRate $rate)
    {
        $stmt = $this->getConnection()
            ->prepare(
                'INSERT INTO '
                . self::$table
                . ' (currency_id, rate, to_currency_id) VALUES '
                . '(:currency_id, :rate, :to_currency_id)'
            );
        $stmt->bindValue(':currency_id', $rate->currency_id);
        $stmt->bindValue(':rate', $rate->rate);
        $stmt->bindValue(':to_currency_id', $rate->to_currency_id);
        $ex = $stmt->execute();
        if (!$ex) {
            error_log(print_r($stmt->errorInfo(), true));
        }

        return $ex ? $this->getConnection()->lastInsertId() : 0;
    }
}
