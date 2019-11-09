<?php
namespace app\repositories;

use app\models\Currency;

class CurrencyRepository extends MysqlRepository
{
    public static $modelClass = Currency::class;
    public static $table = 'currency';
}
