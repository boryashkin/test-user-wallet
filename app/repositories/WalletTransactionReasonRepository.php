<?php
namespace app\repositories;

use app\models\WalletTransactionReason;

class WalletTransactionReasonRepository extends MysqlRepository
{
    public static $modelClass = WalletTransactionReason::class;
    public static $table = 'wallet_transaction_reason';
}
