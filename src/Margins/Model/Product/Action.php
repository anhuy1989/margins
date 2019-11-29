<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kodbruket\Margins\Model\Product;


/**
 * Margins Product Mass Action processing model
 *
 * @since 100.0.2
 */
class Action extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Kodbruket\Margins\Api\HttpRequestTransferInterfaceFactory
     */
    protected $httpRequestTransferInterfaceFactory;

    /**
     * @var
     */
    protected $storeManager;

    /**
     * @var storeId int
     */
    protected $storeId = 0;

    /**
     * @var \Kodbruket\Margins\Model\Logger\Logger
     */
    protected $logger;

    /**
     * @var \Kodbruket\Margins\Service\Configuration
     */
    protected $configuration;

    /**
     * @var \Kodbruket\Margins\Api\HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var \Kodbruket\Margins\Helper\Data
     */
    protected $helper;

    /**
     * Action constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Kodbruket\Margins\Api\HttpRequestTransferInterfaceFactory $httpRequestTransferInterfaceFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Kodbruket\Margins\Model\Logger\Logger $logger
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Kodbruket\Margins\Api\HttpRequestTransferInterfaceFactory $httpRequestTransferInterfaceFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Kodbruket\Margins\Model\Logger\Logger $logger,
        \Kodbruket\Margins\Service\Configuration $configuration,
        \Kodbruket\Margins\Api\HttpClientInterface $httpClient,
        \Kodbruket\Margins\Helper\Data $helper,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->httpRequestTransferInterfaceFactory = $httpRequestTransferInterfaceFactory;
        $this->storeManager = $storeManager;
        $this->configuration = $configuration;
        $this->logger = $logger;
        $this->httpClient = $httpClient;
        $this->helper = $helper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Update attribute values for entity list per store
     *
     * @param array $productIds
     * @param array $attrData
     * @return $this
     */
    public function import($productIds)
    {
        foreach ($productIds as $productId) {
            $this->create($productId);
        }
        return $this;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function create($productId)
    {
        $headers = [
            'Content-Type' =>  'application/x-www-form-urlencoded',
            'Site-Id' => $this->configuration->getSiteId()
        ];
        $product = $this->productRepository->getById($productId, false, $this->storeId);
        /** @var \Kodbruket\Margins\Api\HttpRequestTransferInterface $transfer */
        $transfer = $this->httpRequestTransferInterfaceFactory->create([
            'headers' => $headers,
            'params' => [],
            'body' => $this->convertToMarginsProduct($product),
            'method' => \Zend_Http_Client::POST,
            'uri' => '/products',
            'isAuth' => false,
        ]);
        try {
            $result = $this->httpClient->sendRequest($transfer);
            $result = $this->helper->convertJsonToArray($result['body']);
            $this->logger->info(sprintf('Import product success %s has data: \n', $productId, var_export($result, true)));
        } catch (\Magento\Framework\Exception\LocalizedException $localizedException) {
            $this->logger->critical(
                sprintf('Exception for product %s , message :',
                    $productId,
                    $localizedException->getMessage()
                )
            );
        }
        return $result;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magento\Catalog\Model\ResourceModel\Product\Action::class);
    }

    protected function convertToMarginsProduct($product)
    {
        if (!$product) {
            throw new LocalizedException(__('The product does not exist'));
        }
        $store = $this->storeManager->getStore($this->storeId);
        $currencyCode = $store->getCurrentCurrency()->getCode();
        return  http_build_query([
            'name' => $product->getName(),
            'sku' => $product->getSku(),
            'url' => $product->getProductUrl(),
            'price' => $product->getFinalPrice(),
            'cost' => $product->getFinalPrice(),
            'costCurrency' => $currencyCode,
            'currency' => $currencyCode,
            'status' => $product->getStatus(),
            'imageUrl' => $product->getData('image'),
            'availability' => $product->isSaleable(),
            'siteId'=> $this->configuration->getSiteId(),
        ]);
    }
}
