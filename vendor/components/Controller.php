<?php
/**
 * Created by PhpStorm.
 * User: rbabenko
 * Date: 03.02.2017
 * Time: 15:46
 */

namespace vendor\components;


class Controller
{
    protected $view;
    protected $layout;
    protected $globParam = [];
    protected $renderParam = [];

    public function __construct($route)
    {
        $this->view = $route['action'];
        $this->layout = $route['controller'];

        foreach ($_GET as $key => $value) {
            if(!empty($value)) {
                $this->param[$key] = $value;
            }
        }
    }

    public function render($view, $params = [])
    {
        $path = Base::getAlias('@view') . $this->layout . DIRECTORY_SEPARATOR . $view . '.php';

        if(file_exists($path)) {
            include $path;
        } else {
            $message = 'view not found';
            include Base::getAlias('@pathToNotFoundPage');
        }
    }
}