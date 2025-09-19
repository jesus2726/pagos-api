<?php

return [
    'api_path' => 'api',
    'api_domain' => null,
    'export_path' => 'api.json',

    'info' => [
        'version' => '1.0.0',
        'description' => 'API REST para el manejo de pagos a dispersar por determinadas plataformas.',
    ],

    'ui' => [
        'title' => 'API de Pagos - Documentación',
        'theme' => 'light',
        'hide_try_it' => false,
        'hide_schemas' => false,
        'logo' => '',
        'try_it_credentials_policy' => 'include',
        'layout' => 'responsive',
    ],

    'servers' => null,
    'enum_cases_description_strategy' => 'description',
    'middleware' => ['web'],
    'extensions' => [],
];
