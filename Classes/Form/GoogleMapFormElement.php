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

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;

/**
 * Provides methods used in the backend by flexforms.
 *
 * @category   TYPO3-Extensions
 * @author     Marian Pollzien <marian.pollzien@netresearch.de>
 * @license    http://opensource.org/licenses/gpl-license GPLv2 or later
 * @link       http://github.com/netresearch/contexts
 */
class GoogleMapFormElement extends AbstractFormElement
{
    public function render(): array
    {
        $result = $this->initializeResultArray();
        $result['html'] = 'TODO: Implement google map field';
        return $result;
    }

    /**
     * Display input field with popup map element to select a position
     * as latitude/longitude points.
     *
     * @param array  $arFieldInfo Information about the current input field
     * @param object $tceforms    Form rendering library object
     *
     * @return string HTML code
     */
    protected function inputMapPosition($arFieldInfo, $tceforms): string
    {
        if (!is_array($arFieldInfo['row']['type_conf'])) {
            $flex = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($arFieldInfo['row']['type_conf']);
        } else {
            $flex = $arFieldInfo['row']['type_conf'];
        }

        if (is_array($flex)
            && isset($flex['data']['sDEF']['lDEF']['field_position']['vDEF'])
        ) {
            list($lat, $lon) = explode(
                ',',
                $flex['data']['sDEF']['lDEF']['field_position']['vDEF']
            );
            $lat      = (float)trim($lat);
            $lon      = (float)trim($lon);
            $jZoom    = 10;
            $inputVal = $flex['data']['sDEF']['lDEF']['field_position']['vDEF'];
        } else {
            // TODO: geoip current address
            $lat      = 51.33876;
            $lon      = 12.3761;
            $jZoom    = 8;
            $inputVal = '';
        }

        $jLat = json_encode($lat);
        $jLon = json_encode($lon);

        if (is_array($flex)
            && isset($flex['data']['sDEF']['lDEF']['field_distance']['vDEF'])
        ) {
            $jRadius = json_encode((float)$flex['data']['sDEF']['lDEF']['field_distance']['vDEF'], JSON_THROW_ON_ERROR);
        } else {
            $jRadius = 10;
        }

        if ($tceforms instanceof \TYPO3\CMS\Backend\Form\FormEngine) {
            $input = $tceforms->getSingleField_typeInput(
                $arFieldInfo['table'],
                $arFieldInfo['field'],
                $arFieldInfo['row'],
                $arFieldInfo
            );
        } elseif ($tceforms instanceof \TYPO3\CMS\Backend\Form\Element\UserElement) {
            $factory = new \TYPO3\CMS\Backend\Form\NodeFactory();
            $nodeInput = $factory->create(
                [
                    'renderType' => 'input',
                    'tableName' => $arFieldInfo['table'],
                    'databaseRow' => $arFieldInfo['row'],
                    'fieldName' => $arFieldInfo['field'],
                    'parameterArray' => $arFieldInfo,
                ]
            );
            $arInput = $nodeInput->render();
            $input = $arInput['html'];
        } else {
            return '';
        }

        preg_match('#id=["\']([^"\']+)["\']#', $input, $arMatches);
        $inputId = $arMatches[1];
        $appKey = $this->getAppKey();

        $html = <<<HTM
$input<br/>

<link rel="stylesheet" href="/typo3conf/ext/contexts_geolocation/Resources/Public/JavaScript/Leaflet/leaflet.css" />
<!--[if lte IE 8]>
    <link rel="stylesheet" href="/typo3conf/ext/contexts_geolocation/Resources/Public/JavaScript/Leaflet/leaflet.ie.css" />
<![endif]-->
<script src="/typo3conf/ext/contexts_geolocation/Resources/Public/JavaScript/Leaflet/leaflet.js"></script>

<script src="https://www.mapquestapi.com/sdk/leaflet/v2.2/mq-map.js?key=$appKey"></script>

<div id="map"></div>
<style type="text/css">
#map { height: 400px; }
</style>
<script type="text/javascript">
//<![CDATA[


function updatePosition(latlng, marker, circle)
{
    var input = document.getElementById('$inputId');

    input.value = latlng.lat + ", " + latlng.lng;
    
    if (input.dataset.formengineInputName) {
        document.getElementsByName(input.dataset.formengineInputName)[0].value = input.value;
    }
    
    if (input.name) {
        document.getElementsByName(input.name.replace('_hr', ''))[0].value = input.value;
    }
    

    if (marker !== null) {
        marker.setLatLng(latlng);
    }

    if (circle !== null) {
        circle.setLatLng(latlng);
    }
}



// Set view to chosen geographical coordinates
var map = L.map('map', {
    layers: MQ.mapLayer(),
    center: [ $jLat, $jLon ],
    zoom: $jZoom
});
    
// Add marker of current coordinates
var marker = L.marker([$jLat, $jLon]).addTo(map);
marker.dragging.enable();

// Add distance circle
var circle = L.circle(
    [$jLat, $jLon], $jRadius * 1000,
    {
        color       : 'red',
        fillColor   : '#f03',
        fillOpacity : 0.2
    }
).addTo(map);

// Handle dragging of marker
marker.on('drag', function(e) {
    updatePosition(e.target.getLatLng(), null, circle);
});

// Handle click on map
map.on('click', function(e) {
    updatePosition(e.latlng, marker, circle);
});

//TYPO3 7.6 we have data attributes
var distanceId = null;
var distanceElement = document.querySelector('[data-formengine-input-name*="field_distance"]');
if (distanceElement) {
    distanceId = distanceElement.id;
}

//before TYPO3 7.6
var distanceName = document.getElementById('$inputId').name.replace(
    'field_position', 'field_distance'
);

if (distanceId) {
    document.getElementById(distanceId).addEventListener(
        'change', function(e) {
            circle.setRadius(e.target.value * 1000);
        }, false
    );
} else if (distanceName) {
    document.getElementsByName(distanceName)[0].addEventListener(
        'change', function(e) {
            circle.setRadius(e.target.value * 1000);
        }, false
    );
}


// Update map if new latitude/longitude input is provided
document.getElementById('$inputId').addEventListener(
    'change', function(e) {
        var values = e.target.value.split(',');
        var lat    = parseFloat(values[0]);
        var lon    = parseFloat(values[1]);
        var latlon = new L.LatLng(lat, lon);

        updatePosition(latlon, marker, circle);

        map.panTo(latlon);
    }, false
);


//]]>
</script>
HTM;

        return $html;
    }
    /**
     * Get the aopp key for mapquest api
     *
     * @return string|null app key
     */
    protected function getAppKey(): ?string
    {
        $arExtConf = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['contexts_geolocation'],
            ['allowed_classes' => false]
        );

        return $arExtConf['app_key'];
    }
}
