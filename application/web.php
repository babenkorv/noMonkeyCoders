<?php

namespace application;

use vendor\Log\Logger;
use vendor\Log\LogLevel;
use vendor\psr7\HttpMessage;
use vendor\psr7\HttpRequest;
use vendor\psr7\HttpStream;
use vendor\psr7\HttpUri;

$comfig = require_once '/config/app.php';
echo '<pre>';
$http = new Http(new HttpRequest());



echo PHP_EOL;
echo 'protocol version' . PHP_EOL;
var_dump($http->httpMessage->getProtocolVersion());

echo PHP_EOL;
echo 'set new protocol version' . PHP_EOL;
var_dump($http->httpMessage
    ->withProtocolVersion('1.0')
    ->withHeader('qqs', ['222222222222'])
    ->withAddedHeader('qqss', ['sssss' => '2'])
    ->withoutHeader('qqss')
);

$stream = new HttpStream();

$uri = new HttpUri();

var_dump(
    $uri
        ->withScheme('http')
        ->withHost('example.com')
        ->withPath('/foo/bar')
        ->withQuery('?baz=bat')->getPath()
);

