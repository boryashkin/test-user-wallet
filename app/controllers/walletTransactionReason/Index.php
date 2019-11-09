<?php
namespace app\controllers\walletTransactionReason;

use app\controllers\BasicIndex;
use app\repositories\WalletTransactionReasonRepository;

/**
 * @get
 */
class Index extends BasicIndex
{
    public static $modelRepositoryClass = WalletTransactionReasonRepository::class;
}
