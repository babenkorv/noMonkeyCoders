<?php

namespace vendor\psr7;

use vendor\psr7\psr7interface\UriInterface;

class HttpUri implements UriInterface
{

    private $uri;

    private $scheme = '';

    private $allowSheme = [
        'http',
        'https',
    ];

    private $userInfo = '';

    private $host = '';

    private $port;

    private $path = '';

    private $query = '';

    private $fragment = '';

    public static function createUriString($scheme, $authority, $path, $query, $fragment)
    {
        $uri = '';

        if ($scheme != '') {
            $uri .= $scheme . ':';
        }
        if ($authority != '') {
            $uri .= '//' . $authority;
        }
        $uri .= $path;
        if ($query != '') {
            $uri .= '?' . $query;
        }
        if ($fragment != '') {
            $uri .= '#' . $fragment;
        }
        
        return $uri;
    }

    public function __toString()
    {
        return self::createUriString(
            $this->scheme,
            $this->getAuthority(),
            $this->path,
            $this->query,
            $this->fragment
        );
    }

    /**
     * Set scheme (http or https).
     *
     * @param string $scheme
     * @return $this
     * @throws \Exception
     */
    public function withScheme($scheme)
    {
        $lowerScheme = strtolower($scheme);

        if (in_array($lowerScheme, $this->allowSheme)) {
            $this->scheme = strtolower($scheme);
        } else {
            throw new \Exception('Use default scheme(http, https)');
        }
        return $this;
    }

    /**
     * Return scheme.
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }


    /**
     * Set host.
     *
     * @param string $host
     * @return $this
     */
    public function withHost($host)
    {
        if (is_string($host)) {
            $this->host = strtolower($host);
            return $this;
        } else {
            throw new \Exception('Host must be string');
        }

    }

    /**
     * Return host name.
     *
     * @return string
     */
    public function getHost()
    {
        if (!empty($this->host)) {
            return $this->host;
        } else {
            return '';
        }
    }

    public function withPort($port)
    {
        $this->port = $port;

        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }


    /**
     * Create authority string.
     *
     * @return string
     */
    public function getAuthority()
    {
        $authority = $this->host;

        if($this->userInfo != '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if($this->port != null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function withUserInfo($user, $password = null)
    {
        $this->userInfo = $user . ':' . $password;

        return $this;
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function withPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function withFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    public function getFragment()
    {
        return $this->fragment;
    }

    public function withQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

}