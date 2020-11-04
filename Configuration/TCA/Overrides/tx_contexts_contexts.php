<?php

// Register context
\Netresearch\Contexts\Api\Configuration::registerContextType(
    'geolocation_country',
    'LLL:EXT:contexts_geolocation/Resources/Private/Language/locallang_db.xml:title_country',
    'Netresearch\ContextsGeolocation\Context\Type\CountryContext',
    'FILE:EXT:contexts_geolocation/Configuration/FlexForms/Country.xml'
);
\Netresearch\Contexts\Api\Configuration::registerContextType(
    'geolocation_continent',
    'LLL:EXT:contexts_geolocation/Resources/Private/Language/locallang_db.xml:title_continent',
    'Netresearch\ContextsGeolocation\Context\Type\ContinentContext',
    'FILE:EXT:contexts_geolocation/Configuration/FlexForms/Continent.xml'
);
\Netresearch\Contexts\Api\Configuration::registerContextType(
    'geolocation_distance',
    'LLL:EXT:contexts_geolocation/Resources/Private/Language/locallang_db.xml:title_distance',
    'Netresearch\ContextsGeolocation\Context\Type\DistanceContext',
    'FILE:EXT:contexts_geolocation/Configuration/FlexForms/Distance.xml'
);
