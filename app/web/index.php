<?php

require __DIR__ . '/../vendor/autoload.php';

$db = \app\containers\App::getInstance()->getDbConnection();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Template project</title>
</head>
<body>
    <h1>It works!</h1>
    <div>PHP: <?= date(DATE_ATOM) ?></div>
    <div>MySQL: <?= $db->query('select now() from dual')->fetchColumn(0) ?></div>
</body>
</html>
