<?php

namespace controller;

use models\MyModel;
use vendor\components\Controller;
use vendor\widgets\GridView;

class MyController extends Controller
{
    public function actionIndex()
    {

        $this->render('index', []);
    }
}