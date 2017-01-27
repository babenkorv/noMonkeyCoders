<?php

namespace application;

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


$d = DataBaseConnect::instance(null);

//var_dump($d->getAdapter()->findOne('select * from a WHERE id = :id', [':id' => 1]));

$queryBilder = new SqlBuilder($d->getAdapter(), 'a');

//$queryBilder->select(['id as 2'])->count('id')->avg('name')->where('id', '=', 1)->inWhere('id', [1,2,3,4])->betweenWhere('id', 1, 10)->orWhere('id', '=', 2 )->groupBy(['name'])->queryBuild();
//$q = $queryBilder->select(['a.id', 'a.name'])->join('b ', 'id', 'id_a');
$q = $queryBilder->select(['a.id', 'a.name'])->join('b', 'id', 'id_a')->aggregate('a.id', 'COUNT')->groupBy(['a.name'])->having('a.id', '>', 1, 'MAX')->find();
var_dump($q);