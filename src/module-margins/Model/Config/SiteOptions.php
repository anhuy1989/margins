<?php

namespace Kodbruket\Margins\Model\Config;

/**
 * Class SiteOptions
 * @package Kodbruket\Margins\Model\Config
 */
class SiteOptions extends \Kodbruket\Margins\Model\Config\OptionAbstract
{
    /**
     * @var Cache key
     */
    const CACHE_KEY = 'margins_site_options';

    /**
     * @return array
     */

    public function toArray()
    {
        // Check cache for data
        $cachedData = $this->cacheLayer->load(self::CACHE_KEY);

        // Fetch data if nothing exists in cache
        if (!$cachedData) {
            $organisations = $this->objectManager->get(\Kodbruket\Margins\Model\Gateway\GetOrganisations::class)->execute();
            if (count($organisations)) {
                $data = ['' => ' '];
                foreach ($organisations as $org) {
                    if (count($org['sites'])) {
                        foreach ($org['sites'] as $site) {
                            $data[$site['id']] = $site['name'];
                        }
                    }
                }
                if (count($data) > 1) {
                    $this->cacheLayer->save(
                        serialize($data),
                        self::CACHE_KEY,
                        [\Kodbruket\Margins\Model\CacheLayer::CACHE_TAG],
                        self::CACHE_TIME
                    );
                }
            } else {
                $data = [];
            }
        } else {
            $return = @unserialize($cachedData);
            if (!is_array($return)) {
                $this->invalidateCache();
                $data = [];
            }
        }

        return $data;
    }

}
