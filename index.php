<?php
$config = include 'config/app.php';

/*------------- Autoload -------------*/
require_once 'vendor/Autoloader.php';

$autoloader = new Autoloader([]);
$autoloader->run();

/*------------- Set alias -------------*/
\vendor\components\Base::setAlias('@app',  dirname(__DIR__) . DIRECTORY_SEPARATOR . $config['project_name'] . DIRECTORY_SEPARATOR);
\vendor\components\Base::setAlias('@pathToNotFoundPage', \vendor\components\Base::getAlias('@app') . 'vendor' . DIRECTORY_SEPARATOR . 'defaltMessagePage' . DIRECTORY_SEPARATOR . '404.php');
\vendor\components\Base::setAlias('@view', \vendor\components\Base::getAlias('@app') . 'view' . DIRECTORY_SEPARATOR);
;
/*------------- Routing -------------*/
$query = trim($_SERVER['REQUEST_URI'], '/');

\vendor\Router::add('^$', ['controller' => 'main', 'action' => 'index']);
\vendor\Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

\vendor\Router::dispatch($query);



require_once 'application/web.php';

