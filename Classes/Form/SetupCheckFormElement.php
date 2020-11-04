<?php

namespace Netresearch\ContextsGeolocation\Form;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Netresearch\ContextsGeolocation\AbstractAdapter;
use Netresearch\ContextsGeolocation\Exception;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Provides methods used in the backend by flexforms.
 *
 * @category   TYPO3-Extensions
 * @author     Marian Pollzien <marian.pollzien@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts
 */
class SetupCheckFormElement extends AbstractFormElement
{
    public function render(): array
    {
        try {
            AbstractAdapter::getInstance();
        } catch (Exception $exception) {
            $strMessage =  'The "geoip" PHP extension is not available.'
                . ' Geolocation contexts will not work.';
            /* @var $message FlashMessage */
            $message = GeneralUtility::makeInstance(
                FlashMessage::class,
                $strMessage,
                'Geolocation configuration',
                FlashMessage::ERROR
            );

            /* @var $flashMessageService FlashMessageService */
            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            $queue = $flashMessageService->getMessageQueueByIdentifier();
            $queue->addMessage($message);
        }
        return $this->initializeResultArray();
    }
}
