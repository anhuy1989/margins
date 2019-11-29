<?php
namespace Kodbruket\Margins\Api;

/**
 * Interface HttpRequestTransferInterface
 * @package Kodbruket\Margins\Api
 */
interface HttpRequestTransferInterface
{
    /**
     * Returns method used to place request
     *
     * @return string|int
     */
    public function getMethod();

    /**
     * Returns headers
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Returns request parameters
     *
     * @return array|string
     */
    public function getParams();

    /**
     * Returns request body
     *
     * @return array|string
     */
    public function getBody();

    /**
     * Returns URI
     *
     * @return string
     */
    public function getUri();


    /**
     * Return isAuth
     * @return boolean
     */
    public function getIsAuth();
}
