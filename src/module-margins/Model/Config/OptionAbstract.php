<?php
namespace Kodbruket\Margins\Model\Config;

/**
 * Class OptionAbstract
 * @package Kodbruket\Margins\Model\Config
 */
abstract class OptionAbstract implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var Cache key
     */
    const CACHE_KEY = 'margins_cache_key';

    /**
     * @var Cache time in seconds
     */
    const CACHE_TIME = 3600;

    /**
     * @var \Kodbruket\Margins\Model\CacheLayer
     */
    protected $cacheLayer;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * OptionAbstract constructor.
     * @param \Kodbruket\Margins\Model\CacheLayer $cacheLayer
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Kodbruket\Margins\Model\CacheLayer $cacheLayer,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->cacheLayer = $cacheLayer;
        $this->objectManager = $objectManager;
    }

    /**
     * Invalidate the cache
     *
     * @return $this
     */
    public function invalidateCache()
    {
        $this->cacheLayer->remove(self::CACHE_KEY);

        return $this;
    }

    /**
     * To options array
     *
     * @return array
     */
    final public function toOptionArray()
    {
        $arr = $this->toArray();
        $ret = [];

        foreach ($arr as $key => $value) {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }
}
