<?php
namespace app\models;

class User
{
    public $id;
    public $login;
    public $created_at;

    /** @var int joined value */
    public $wallet_id;
    /** @var float joined value */
    public $balance;
    /** @var int joined value */
    public $currency_id;
}
