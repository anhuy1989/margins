<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Adminhtml Check Token button
 *
 * @author  Huy Nguyen <huy@kodbruket.se>
 */
namespace Kodbruket\Margins\Block\Adminhtml\System\Config;

class Checktoken extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Margins URL
     *
     * @var string
     */
    protected $_auth_url = 'margins_general_api_auth_url';

    /**
     * Margins token
     *
     * @var string
     */
    protected $_token = 'margins_general_api_token';

    /**
     * Check Token Button Label
     *
     * @var string
     */
    protected $_buttonLabel = 'Check Token';

    /**
     * Set Margins auth url
     *
     * @param string $url
     * @return \Kodbruket\Margins\Block\Adminhtml\System\Config\Checktoken
     */
    public function setAuthUrl($url)
    {
        $this->_auth_url = $url;
        return $this;
    }

    /**
     * Get Margins auth url
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->_auth_url;
    }

    /**
     * Set Margins token
     * @param string $token
     * @return \Kodbruket\Margins\Block\Adminhtml\System\Config\Checktoken
     */
    public function setToken($token)
    {
        $this->_token = $token;
        return $this;
    }

    /**
     * Get Margins Token
     *
     * @return string
     *
     **/
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Set Check Token Button Label
     *
     * @param string $label
     * @return \Kodbruket\Margins\Block\Adminhtml\System\Config\Checktoken
     */
    public function setButtonLabel($label)
    {
        $this->_buttonLabel = $label;
        return $this;
    }

    /**
     * Set template to itself
     *
     * @return \Kodbruket\Margins\Block\Adminhtml\System\Config\Checktoken
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('Kodbruket_Margins::system/config/checktoken.phtml');
        }
        return $this;
    }

    /**
     * Unset some non-related element parameters
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Get the button and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $buttonLabel = !empty($originalData['button_label']) ? $originalData['button_label'] : $this->_buttonLabel;
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl('margins/token/check'),
            ]
        );

        return $this->_toHtml();
    }
}
