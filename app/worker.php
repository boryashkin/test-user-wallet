<?php

require __DIR__ . '/vendor/autoload.php';

$db = \app\containers\App::getInstance()->getDbConnection();
$result = $db->query('select now() from dual')->fetchColumn(0);

echo "Executed, $result \n";
