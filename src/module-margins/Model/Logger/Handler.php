<?php

namespace Kodbruket\Margins\Model\Logger;

use Magento\Framework\Logger\Handler\Base;

/**
 * Class Handler
 * @package Kodbruket\Margins\Model\Logger
 *
 * Main margins logger handler
 */
class Handler extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/margins.log';
}
