<?php
/**
 * Geolocation contexts: Frontend configuration
 *
 * PHP version 5
 *
 * @category   TYPO3-Extensions
 * @author     Christian Weiske <christian.weiske@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts_geolocation
 */
defined('TYPO3_MODE') or die('Access denied.');

$arPluginList = [
    'Position'     => [
        'action' => [
            'Position' => 'show'
        ],
        'noncachable' => [],
    ],
];

foreach ($arPluginList as $strPluginName => $arControllerActions) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Netresearch.' . $_EXTKEY,
        $strPluginName,
        $arControllerActions['action'],
        // non-cacheable actions
        $arControllerActions['noncachable']
    );
}
