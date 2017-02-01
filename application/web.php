<?php

namespace application;

use models\MyModel;
use vendor\App;
use vendor\db\Connect;
use vendor\db\DataBase;
use vendor\db\DataBaseConnect;
use vendor\db\SqlBuilder;
use vendor\Log\Logger;
use vendor\Log\LogLevel;
use vendor\psr7\HttpMessage;
use vendor\psr7\HttpRequest;
use vendor\psr7\HttpResponse;
use vendor\psr7\HttpServerRequest;
use vendor\psr7\HttpStream;
use vendor\psr7\HttpUploadedFile;
use vendor\psr7\HttpUri;

echo '<pre>';

$d = DataBaseConnect::instance(null);
//
//$queryBilder = new SqlBuilder($d->getAdapter(), 'a');

//$q = $queryBilder->update(['name' => 'ak'])->where('id', '=', 78)->execute();
//var_dump($q);
$model = new MyModel();


$model->where('id', '=', 159)->findOne();

$model->name  = 'dasda22';

$model->save();