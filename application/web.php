<?php

namespace application;

use vendor\Log\Logger;
use vendor\Log\LogLevel;
use vendor\psr7\HttpMessage;
use vendor\psr7\HttpRequest;
use vendor\psr7\HttpServerRequest;
use vendor\psr7\HttpStream;
use vendor\psr7\HttpUploadedFile;
use vendor\psr7\HttpUri;

$comfig = require_once '/config/app.php';
echo '<pre>';

//$request = new HttpRequest('GET', new HttpUri(), ['roma' => '1', '1' => ['1', 2]], null, '1.0');
//
//$request->withMethod('OPTIONS')
//    ->withRequestTarget('*')
//    ->withUri(new HttpUri('https://example.org/'));

//var_dump($request);

//$request = new HttpRequest('post', new HttpUri('https://example.org/aaa/a'),['r' => ['aa']], new HttpStream(fopen('1.txt', 'r')), '1.0');

//var_dump($request);

$uploadF = new HttpUploadedFile(fopen('1.txt', 'r'), 222, 22, '111', '222');

$uploadF->moveTo('2.txt');

$serverRequest = new HttpServerRequest('post', new HttpUri('https://example.org/aaa/a'),['r' => ['aa']], new HttpStream(fopen('1.txt', 'r')), '1.0', []);

var_dump($serverRequest);
