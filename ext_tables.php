<?php
/**
 * Geolocation contexts: Database table backend configuration
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
        'Position' => false,
    ];

    foreach ($arPluginList as $strPluginName => $bUseFlexform) {
        $strPluginKey = strtolower(str_replace('_', '', $extKey) . '_' . $strPluginName);
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            $extKey,
            $strPluginName,
            $strPluginName
        );
        if ($bUseFlexform) {
            $TCA['tt_content']['types']['list']['subtypes_addlist'][$strPluginKey]
                = 'pi_flexform';
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
                $strPluginKey,
                'FILE:EXT:' . $extKey . '/Configuration/FlexForms/' . $strPluginName . '.xml'
            );
        }
    }
})('contexts_geolocation');
