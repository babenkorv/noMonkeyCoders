<?php

return [
    'basePath' => dirname( __DIR__),

    'classMap' => [
        'app\folder' => 'app'
    ],

    'pathToLogFile' => dirname(__DIR__) . '\\' .  'Log\log.txt',

    'defaultRoute' => 'main/index',
    'project_name' => 'NoMonkeyCoders',
];