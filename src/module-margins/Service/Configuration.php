<?php
namespace Kodbruket\Margins\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Request\Http;

/**
 * Class ConfigurationService
 * @package Kodbruket\Margins\Service
 *
 * Contains all accessors to configurable extension options (api credentials, general settings)
 */
class Configuration
{
    /**#@+
     * Admin config XML Path constants
     * @var string
     */
    const XML_TOKEN = 'margins_general/api/token';
    const XML_AUTH_URL = 'margins_general/api/auth_url';
    const XML_CRAWLER_URL = 'margins_catalog/settings/crawler_url';
    const XML_SITE_ID = 'margins_catalog/settings/site_id';

    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Http
     */
    private $request;

    /**
     * Configuration constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Http $request
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Http $request
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
    }

    /**
     * Returns token from config
     *
     * @param string|null $store
     * @return string
     */
    public function getToken($store = null)
    {
        $token = $this->request->getParam('token');
        return $token ? $token : $this->scopeConfig->getValue(
            self::XML_TOKEN,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }


    /**
     * Returns auth URL from config
     *
     * @param string|null $store
     * @return string
     */
    public function getAuthUrl($store = null)
    {

        $authUrl = $this->request->getParam('auth_url');
        return $authUrl ? $authUrl : $this->scopeConfig->getValue(
            self::XML_AUTH_URL,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Returns crawler URL from config
     *
     * @param string|null $store
     * @return string
     */
    public function getCrawlerUrl($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_CRAWLER_URL,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Returns siteId from config
     *
     * @param string|null $store
     * @return string
     */
    public function getSiteId($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_SITE_ID,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
