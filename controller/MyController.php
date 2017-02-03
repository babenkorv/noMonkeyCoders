<?php

namespace controller;

use vendor\components\Controller;

class MyController extends Controller
{
    public function actionIndex()
    {
        var_dump($this->routeData);
        echo 'hello';
    }
}