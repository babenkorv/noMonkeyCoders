<?php

return [
    'basePath' => dirname(__DIR__),

    'classMap' => [
        'app\folder' => 'app'
    ],

    'pathToLogFile' => dirname(__DIR__) . '\\' . 'Log\log.txt',

    'defaultRoute' => 'main/index',
    'project_name' => 'NoMonkeyCoders',

    'assetManager' => [
        'css' => [
            '*' => ['assets' . DIRECTORY_SEPARATOR . 'spectre.min.css'],
        ],
        'js' => [
            '*' => [
                'assets' . DIRECTORY_SEPARATOR . 'validate.min.js',
                'assets' . DIRECTORY_SEPARATOR . 'tiny-min.js'
            ],
        ],
    ]
];