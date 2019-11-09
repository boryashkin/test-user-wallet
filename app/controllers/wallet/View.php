<?php
namespace app\controllers\wallet;

use app\controllers\BasicView;
use app\repositories\WalletRepository;

class View extends BasicView
{
    public static $modelRepositoryClass = WalletRepository::class;
}