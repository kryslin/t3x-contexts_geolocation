<?php

namespace Netresearch\ContextsGeolocation;

/**
 * Part of geolocation context extension.
 *
 * PHP version 5
 *
 * @category   TYPO3-Extensions
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */
use Netresearch\ContextsGeolocation\Adapter\GeoIp;
use Netresearch\ContextsGeolocation\Adapter\NetGeoIp;

/**
 * Abstract base class for each adapter.
 *
 * @category   TYPO3-Extensions
 * @author     Rico Sonntag <rico.sonntag@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */
abstract class AbstractAdapter
{
    /**
     * Current IP address.
     *
     * @var string
     */
    protected $ip;

    /**
     * Get an adapter instance.
     *
     * @param string|null $ip IP address
     *
     * @return self
     * @throws Exception
     */
    public static function getInstance(?string $ip = null): self
    {
        static $instance = null;

        if ($instance !== null) {
            return $instance;
        }

        $instance = GeoIp::getInstance($ip);
        if ($instance !== null) {
            return $instance;
        }

        $instance = NetGeoIp::getInstance($ip);
        if ($instance !== null) {
            return $instance;
        }

        throw new Exception(
            'No installed geoip adapter found'
        );
    }

    /**
     * Get two-letter continent code.
     *
     * @return string|false Continent code or FALSE on failure
     */
    abstract public function getContinentCode();

    /**
     * Get two or three letter country code.
     *
     * @param bool $threeLetterCode TRUE to return 3-letter country code
     *
     * @return string|false Country code or FALSE on failure
     */
    abstract public function getCountryCode($threeLetterCode = false);

    /**
     * Get country name.
     *
     * @return string|false Country name or FALSE on failure
     */
    abstract public function getCountryName();

    /**
     * Get location record.
     *
     * @return array|false Location data or FALSE on failure
     */
    abstract public function getLocation();

    /**
     * Get country code and region.
     *
     * @return array|false Region data or FALSE on failure
     */
    abstract public function getRegion();

    /**
     * Get name of organization or of the ISP which has registered the
     * IP address range.
     *
     * @return string|false Organization name or FALSE on failure
     */
    abstract public function getOrganization();
}
