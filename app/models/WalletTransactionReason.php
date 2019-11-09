<?php
namespace app\models;

class WalletTransactionReason
{
    public const ID_STOCK = 1;
    public const ID_REFUND = 2;

    public $id;
    public $name;
}
