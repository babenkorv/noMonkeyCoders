<?php

namespace controller;

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