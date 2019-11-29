<?php

namespace Kodbruket\Margins\Model\Http;

use Kodbruket\Margins\Api\HttpRequestTransferInterface;

/**
 * Class Transfer
 * @package Kodbruket\Margins\Model\Http
 */
class Transfer implements HttpRequestTransferInterface
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $params;

    /**
     * @var array|string
     */
    private $body;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var bool
     */
    private $encode;

    /**
     * @var bool
     */
    private $isAuth;

    /**
     * Transfer constructor.
     * @param array $headers
     * @param $body
     * @param array $params
     * @param $method
     * @param $uri
     * @param $isAuth
     */
    public function __construct(
        array $headers,
        $body,
        array $params,
        $method,
        $uri,
        $isAuth
    ) {
        $this->headers = $headers;
        $this->body = $body;
        $this->params = $params;
        $this->method = $method;
        $this->uri = $uri;
        $this->isAuth = $isAuth;
    }


    /**
     * @return string|int
     */
    public function getMethod()
    {
        return (string) $this->method;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return array|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return (string) $this->uri;
    }

    /**
     * @return boolean
     */
    public function getIsAuth()
    {
        return $this->isAuth;
    }
}

