<?php
namespace Kodbruket\Margins\Api;

/**
 * Interface HttpClientInterface
 * @package Kodbruket\Margins\Api
 */
interface HttpClientInterface
{
    /**
     * @param HttpRequestTransferInterface $httpRequestTransfer
     * @return array
     */
    public function sendRequest(HttpRequestTransferInterface $httpRequestTransfer);
}
