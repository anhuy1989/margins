<?php

/**
 * * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kodbruket\Margins\Controller\Adminhtml\Token;

use Magento\Framework\Controller\Result\JsonFactory;

class Check extends \Magento\Backend\App\Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Check whether vat is valid
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_objectManager->get(\Kodbruket\Margins\Model\Gateway\TestConnection::class)
            ->execute();

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([
            'valid' => $result,
            'message' => $result ? 'Token verification successfully.' : 'Error during Token verification.',
        ]);
    }
}
