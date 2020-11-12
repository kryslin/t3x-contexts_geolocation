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

(static function ($extKey) {
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
            'Netresearch.' . $extKey,
            $strPluginName,
            $arControllerActions['action'],
            // non-cacheable actions
            $arControllerActions['noncachable']
        );
    }

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1602981747] = [
        'nodeName' => 'SetupCheck',
        'priority' => 40,
        'class' => \Netresearch\ContextsGeolocation\Form\SetupCheckFormElement::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][2302981747] = [
        'nodeName' => 'MapFormElement',
        'priority' => 40,
        'class' => \Netresearch\ContextsGeolocation\Form\MapFormElement::class,
    ];

})('contexts_geolocation');
