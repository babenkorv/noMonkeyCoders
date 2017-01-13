<?php
/**
 * Created by PhpStorm.
 * User: rbabenko
 * Date: 12.01.2017
 * Time: 16:56
 */

namespace application;


use vendor\psr7\psr7interface\MessageInterface;

class Http
{
    public $httpMessage;
    
    public function __construct(MessageInterface $messageInterface)
    {
        $this->httpMessage = $messageInterface;
    }
}