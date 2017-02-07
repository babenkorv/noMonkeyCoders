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
    private $view;
    private $layout;
    private $globParam = [];
    private $renderParam = [];
    public $viewUniqueName = '';

    public function __construct($route)
    {
        $this->view = $route['action'];
        $this->layout = $route['controller'];
        $this->viewUniqueName = $this->setViewUniqueName();

        foreach ($_GET as $key => $value) {
            if(!empty($value)) {
                $this->param[$key] = $value;
            }
        }

        unset($_GET['index_php']);
        unset($_GET[$this->layout]);
    }

    public function setViewUniqueName()
    {
        return $this->layout . '::' . $this->view;
    }

    public function render($view, $params = [])
    {
        $assets = '';
        
        $path = Base::getAlias('@view') . $this->layout . DIRECTORY_SEPARATOR . $view . '.php';
        if(!empty($params)) {
            foreach ($params as $paramKey => $paramValue) {
                ${$paramKey} = $paramValue;
            }
        }

        if(file_exists($path)) {
            include $path;
        } else {
            $message = 'view not found';
            include Base::getAlias('@pathToNotFoundPage');
        }
    }
    
}