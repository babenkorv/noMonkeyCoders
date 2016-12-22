<?php

$comfig = require_once '/config/app.php';

$autoloader = new Autoloader([]);
$autoloader->addCustomNamespace('app\folder', 'app');
$autoloader->printCustomNamespace();

$autoloader->run();

$first = new \app\folder\First();
$second = new \app\BlockOne\Third();

echo '<br>';
echo $second->thirdMethod();
echo '<br>';
echo $first->firstMethod();
