<?php

namespace vendor\psr7;

use vendor\psr7\psr7interface\RequestInterface;
use vendor\psr7\psr7interface\UriInterface;

class HttpRequest extends HttpMessage implements RequestInterface
{
    private $uri;

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;

        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    public function getMethod()
    {
        // TODO: Implement getMethod() method.
    }
}