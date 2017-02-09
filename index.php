<?php
$config = include 'config/app.php';

/*------------- Autoload -------------*/
require_once 'vendor/Autoloader.php';

$autoloader = new Autoloader([]);
$autoloader->run();

/*------------- Set alias -------------*/
\vendor\components\Alias::setAlias('@app',  dirname(__DIR__) . DIRECTORY_SEPARATOR . $config['project_name'] . DIRECTORY_SEPARATOR);
\vendor\components\Alias::setAlias('@pathToNotFoundPage', \vendor\components\Alias::getAlias('@app') . 'vendor' . DIRECTORY_SEPARATOR . 'defaltMessagePage' . DIRECTORY_SEPARATOR . '404.php');
\vendor\components\Alias::setAlias('@view', \vendor\components\Alias::getAlias('@app') . 'view' . DIRECTORY_SEPARATOR);
\vendor\components\Alias::setAlias('@web', \vendor\components\Alias::getAlias('@app') . 'web' . DIRECTORY_SEPARATOR);
\vendor\components\Alias::setAlias('@config', \vendor\components\Alias::getAlias('@app') . 'config' . DIRECTORY_SEPARATOR);

/*------------- set config asset -------------*/
\vendor\components\AssetManager::setConfigAsset();
\vendor\components\AssetManager::setDefaultPath([
    'css' => \vendor\components\Alias::getAlias('@web') . 'css' . DIRECTORY_SEPARATOR,
    'js' => \vendor\components\Alias::getAlias('@web') . 'js' . DIRECTORY_SEPARATOR,
]);

/*------------- Routing -------------*/
$query = trim($_SERVER['REQUEST_URI'], '/');



\vendor\Router::add('^$', ['controller' => 'main', 'action' => 'index']);
\vendor\Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

\vendor\Router::dispatch($query);


