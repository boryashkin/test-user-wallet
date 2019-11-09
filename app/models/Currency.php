<?php
namespace app\models;

class Currency
{
    public const CODE_RUB = 'RUB';
    public const CODE_USD = 'USD';

    public $id;
    public $code;
    public $created_at;
}
