<?php
namespace Kodbruket\Margins\Model\Gateway;

use Kodbruket\Margins\Api\HttpClientInterface;
use Kodbruket\Margins\Api\HttpRequestTransferInterfaceFactory;
use Kodbruket\Margins\Helper\Data as Helper;

/**
 * Class GetOrganisations
 * @package Kodbruket\Margins\Model\Gateway
 *
 * Responsibility : Check if API ready.
 * Used check api state before sending any requests.
 */
class GetOrganisations
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var HttpRequestTransferInterfaceFactory
     */
    private $httpRequestTransferInterfaceFactory;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * GetOrganisations constructor.
     * @param HttpRequestTransferInterfaceFactory $httpRequestTransferInterfaceFactory
     * @param HttpClientInterface $httpClient
     * @param Helper $helper
     */
    public function __construct(
        HttpRequestTransferInterfaceFactory $httpRequestTransferInterfaceFactory,
        HttpClientInterface $httpClient,
        Helper $helper

    ) {
        $this->httpClient = $httpClient;
        $this->httpRequestTransferInterfaceFactory = $httpRequestTransferInterfaceFactory;
        $this->helper = $helper;
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Kodbruket\Margins\Api\HttpRequestTransferInterface $transfer */
        $transfer = $this->httpRequestTransferInterfaceFactory->create([
            'headers' => [],
            'params' => [],
            'body' => '',
            'method' => \Zend_Http_Client::GET,
            'uri' => '/organisations',
            'isAuth' => true
        ]);

        $result = $this->httpClient->sendRequest($transfer);
        $result = $this->helper->convertJsonToArray($result['body']);
        return $result;

    }
}
