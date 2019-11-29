<?php

namespace Kodbruket\Margins\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const MODULE_NAME = 'Kodbruket_Margins';

    protected $_moduleList;

    public function __construct(
        Context $context,
        ModuleListInterface $moduleList)
    {
        $this->_moduleList = $moduleList;
        parent::__construct($context);
    }

    public function getVersion()
    {
        return $this->_moduleList
            ->getOne(self::MODULE_NAME)['setup_version'];
    }

    /**
     * Converts gateway response to ENV structure
     *
     * @param mixed $response
     * @return array
     * @throws LocalizedException
     */
    public function convertJsonToArray($response)
    {
        if (!is_string($response)) {
            throw new LocalizedException(__('Wrong response type'));
        }

        return json_decode($response, true);
    }

    /**
     * @param $product
     * @return string
     * @throws LocalizedException
     */


}

