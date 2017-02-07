<?php

namespace controller;

use vendor\components\AssetManager;
use vendor\components\Base;
use vendor\components\Controller;

class MainController extends Controller
{
    public function actionIndex()
    {   
        $this->render('index', [
            'a' => 22,
        ]);
    }
}