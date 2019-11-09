<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use app\models\Currency;

//const URL_CURRENCY_RATES = 'http://www.cbr.ru/scripts/XML_daily.asp';
const URL_CURRENCY_RATES = __DIR__ . '/XML_daily.asp';//for tests


$loop = \React\EventLoop\Factory::create();
$db = \app\containers\App::getInstance()->getDbConnection();
$crRepo = new \app\repositories\CurrencyRateRepository($db);
$updater = function () use ($db, $crRepo) {
    $source = file_get_contents(URL_CURRENCY_RATES);
    if (!$source) {
        error_log('Failed to open ' . URL_CURRENCY_RATES);

        return 1;
    }
    $stmt = $db->query('SELECT c1.id id1, c1.code code1, c2.id id2, c2.code code2 from currency c1, currency c2');
    $stmt->execute();
    $rateDirections = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $rateDirections[$row['code1'] . $row['code2']] = $row;
    }

    $xml = simplexml_load_string($source);
    $db->beginTransaction();
    $saved = true;
    foreach ($xml->Valute as $element) {
        $value = (float)str_replace(',', '.', $element->Value) / (int)$element->Nominal;
        if (isset($rateDirections[Currency::CODE_RUB . $element->CharCode])) {
            $cr = new \app\models\CurrencyRate();
            $cr->rate = 1 / $value;
            $cr->currency_id = $rateDirections[Currency::CODE_RUB . $element->CharCode]['id1'];
            $cr->to_currency_id = $rateDirections[Currency::CODE_RUB . $element->CharCode]['id2'];
            $saved = $saved && $crRepo->createOne($cr);
        }
        if (isset($rateDirections[$element->CharCode . Currency::CODE_RUB])) {
            $cr = new \app\models\CurrencyRate();
            $cr->rate = $value;
            $cr->currency_id = $rateDirections[Currency::CODE_RUB . $element->CharCode]['id2'];
            $cr->to_currency_id = $rateDirections[Currency::CODE_RUB . $element->CharCode]['id1'];
            $saved = $saved && $crRepo->createOne($cr);
        }
    }
    if ($saved) {
        $db->commit();
    } else {
        $db->rollBack();
        error_log('rollback');
    }

    return 0;
};
$updater();

$loop->addPeriodicTimer(60 * 10, $updater);

$loop->run();