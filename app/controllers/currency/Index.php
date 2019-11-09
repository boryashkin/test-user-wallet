<?php
namespace app\controllers\currency;

use app\controllers\BasicIndex;
use app\repositories\CurrencyRepository;

/**
 * @get
 */
class Index extends BasicIndex
{
    public static $modelRepositoryClass = CurrencyRepository::class;
}
