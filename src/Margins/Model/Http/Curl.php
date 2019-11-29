<?php

namespace Kodbruket\Margins\Model\Http;

use Kodbruket\Margins\Api\HttpClientInterface;
use Kodbruket\Margins\Api\HttpRequestTransferInterface;
use Kodbruket\Margins\Service\Configuration;
use Magento\Framework\HTTP\Adapter\Curl as CurlAdapter;
use Kodbruket\Margins\Model\Logger\Logger;
use Kodbruket\Margins\Helper\Data as Helper;


/**
 * Class Curl
 * @package Kodbruket\Margins\Model\Http
 */
class Curl implements HttpClientInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var CurlAdapter
     */
    private $curl;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * Curl constructor.
     * @param Configuration $configuration
     * @param CurlAdapter $curl
     * @param Logger $logger
     * @param Helper $helper
     */
    public function __construct(
        Configuration $configuration,
        CurlAdapter $curl,
        Logger $logger,
        Helper $helper
    ) {
        $this->configuration = $configuration;
        $this->curl = $curl;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @param HttpRequestTransferInterface $transfer
     * @return array
     */
    public function sendRequest(HttpRequestTransferInterface $transfer)
    {

        $headers = ['User-Agent: MarginsMagento2/' . $this->helper->getVersion()];
        $token = $this->configuration->getToken();
        if (! empty($token)) {
            // Apply the authorization token if it is set
            $headers[] = 'Authorization: Personal ' . $token;
        }

        foreach ($transfer->getHeaders() as $name => $value) {
            $headers[] = sprintf('%s: %s', $name, $value);
        }

        if ($transfer->getIsAuth()) {
            $url = $this->configuration->getAuthUrl() . $transfer->getUri();
        } else {
            $url = $this->configuration->getCrawlerUrl() . $transfer->getUri();
        }
        $this->logger->debug('Sending request to API', [
            'method' => $transfer->getMethod(),
            'url' => $url,
            'body' => $transfer->getBody(),
            'headers' => var_export($headers, true)
        ]);
        $this->curl->write(
            $transfer->getMethod(),
            $url,
            '1.1',
            $headers,
            $transfer->getBody()
        );

        $response = $this->curl->read();
        $result = [
            'code' => \Zend_Http_Response::extractCode($response),
            'body' => \Zend_Http_Response::extractBody($response),
        ];
        return $result;
    }
}
