<?php
namespace Kodbruket\Margins\Controller\Adminhtml\Product;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
/**
 * Class AbstractPostProduct
 * @package Kodbruket\Margins\Controller\Adminhtml\Administration
 */
class Send extends \Magento\Catalog\Controller\Adminhtml\Product implements HttpPostActionInterface
{
    /**
     * MassActions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Action\Context $context
     * @param Builder $productBuilder
     * @param \Magento\Catalog\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Product\Builder $productBuilder,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $productBuilder);
    }

    /**
     * Validate batch of products before theirs status will be set
     *
     * @param array $productIds
     * @param int $status
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _validateMassStatus(array $productIds)
    {
        if (!$this->_objectManager->create(\Magento\Catalog\Model\Product::class)->isProductsHasSku($productIds)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Please make sure to define SKU values for all processed products.')
            );
        }
    }

    /**
     * Update product(s) status action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productIds = $collection->getAllIds();
        $requestStoreId = $storeId = $this->getRequest()->getParam('store', null);
        $filterRequest = $this->getRequest()->getParam('filters', null);

        if (null === $storeId && null !== $filterRequest) {
            $storeId = (isset($filterRequest['store_id'])) ? (int) $filterRequest['store_id'] : 0;
        }

        try {
            $this->_validateMassStatus($productIds);
            $this->_objectManager->get(\Kodbruket\Margins\Model\Product\Action::class)
                ->setStoreId((int) $storeId)
                ->import($productIds);
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been sent to Margins.', count($productIds))
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while sending the product(s) to Margins.')
            );
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('catalog/*/', ['store' => $requestStoreId]);
    }
}
