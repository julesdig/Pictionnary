<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'admin' => [
        'path' => './assets/admin.js',
        'entrypoint' => true,
    ],
    'game_controller' => [
        'path' => './assets/controllers/game_controller.js',
        'entrypoint' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.13',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.4',
        'type' => 'css',
    ],
    'bootstrap' => [
        'version' => '5.3.4',
    ],
    'chart.js' => [
        'version' => '3.9.1',
    ],
    '@wandb/sdk' => [
        'version' => '0.5.1',
    ],
    'graphql-request' => [
        'version' => '5.2.0',
    ],
    'cross-fetch' => [
        'version' => '3.1.6',
    ],
    '@wandb/sdk/dist/sdk/lib/netrc.js' => [
        'version' => '0.5.1',
    ],
    'readline' => [
        'version' => '1.3.0',
    ],
    'extract-files' => [
        'version' => '9.0.0',
    ],
    'form-data' => [
        'version' => '3.0.1',
    ],
    'graphql' => [
        'version' => '16.6.0',
    ],
];
