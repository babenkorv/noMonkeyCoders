<?php

namespace application;

use vendor\App;
use vendor\db\Connect;
use vendor\db\DataBase;
use vendor\Log\Logger;
use vendor\Log\LogLevel;
use vendor\psr7\HttpMessage;
use vendor\psr7\HttpRequest;
use vendor\psr7\HttpResponse;
use vendor\psr7\HttpServerRequest;
use vendor\psr7\HttpStream;
use vendor\psr7\HttpUploadedFile;
use vendor\psr7\HttpUri;

$comfig = require_once '/config/app.php';
echo '<pre>';
//
//$app = new App();
//
//$c = $app->get('/application/index.html',
//    new HttpRequest('get', new HttpUri('/application/index.html'), ['1' => 'sssw']),
//    new HttpResponse('200', [], new HttpStream(fopen(__DIR__.'/index.html', 'r'), 'r'), null)
//);
//
//echo $c;


$d = DataBase::instance(null);

var_dump($d->getAdapter()->findOne('select * from a WHERE id = :id', [':id' => 1]));
