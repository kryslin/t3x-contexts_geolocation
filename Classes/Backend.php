<?php

namespace Netresearch\ContextsGeolocation;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Part of geolocation context extension.
 *
 * PHP version 5
 *
 * @category   TYPO3-Extensions
 * @author     Christian Weiske <christian.weiske@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */

/**
 * Provides methods used in the backend by flexforms.
 *
 * @category   TYPO3-Extensions
 * @author     Christian Weiske <christian.weiske@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */
class Backend
{
    /**
     * Get all countries from static info tables.
     * Uses the three-letter country code as key instead of the uid.
     *
     * @param array  &$params      Additional parameters
     * @param object $parentObject Parent object instance
     */
    public function getCountries(array &$params, $parentObject): void
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_contexts_contexts');
        $arRows = $queryBuilder->select('cn_iso_3 AS code', 'cn_short_en AS name')
            ->from('static_countries')
            ->orderBy('name')
            ->execute()
            ->fetchAllAssociative();

        $params['items'][] = ['- unknown -', '*unknown*'];
        foreach ($arRows as $arRow) {
            $params['items'][] = [
                $arRow['name'], $arRow['code']
            ];
        }
    }
}
