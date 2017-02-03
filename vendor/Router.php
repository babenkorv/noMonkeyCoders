<?php
/**
 * Created by PhpStorm.
 * User: rbabenko
 * Date: 03.02.2017
 * Time: 13:27
 */

namespace vendor;

use vendor\components\Base;

class Router
{
    public static $routes = [

    ];
    public static $route = [];

    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function upperCamelCase($str)
    {
        $str = str_replace('-', ' ', $str);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);
        return $str;
    }

    public static function matchRoutes($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                    if (empty($route['action'])) {
                        $route['action'] = 'index';
                    }
                }
                self::$route = $route;
                return true;
            }
        }

        return false;
    }

    public static function dispatch($url)
    {
        $url = explode('?', $url)[0];
        if (self::matchRoutes($url)) {
            $controller = 'controller\\' . self::upperCamelCase(self::$route['controller']) . 'Controller';
            if (class_exists($controller)) {
                $controller = new $controller(self::$route);
                $action = 'action' . self::upperCamelCase(self::$route['action']);
                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    $message = 'Controller : ' . self::upperCamelCase(self::$route['controller']) . 'Controller' . '<br>' . 'action : ' . $action . ' is not fund';
                    include Base::getAlias('@pathToNotFoundPage');
                }
            } else {
                $message = 'Controller : ' . self::upperCamelCase(self::$route['controller']) . 'Controller is not fund';
                include Base::getAlias('@pathToNotFoundPage');
            }
        } else {
            $message = 'Page not found';
            include Base::getAlias('@pathToNotFoundPage');
        }
    }
}