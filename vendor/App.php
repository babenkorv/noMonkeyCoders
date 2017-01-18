<?php

namespace vendor;

class App
{
    public function get($path = null ,\vendor\psr7\psr7interface\RequestInterface $request, \vendor\psr7\psr7interface\ResponseInterface $response) {
        
        if ($path === null) {
            $path = $request->getUri();        
        }
        
        $responseBody = $response->getBody();

        return $responseBody->getContents();
    }
}