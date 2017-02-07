<?php

\vendor\components\AssetManager::register($this->viewUniqueName);



$form = new \vendor\widgets\Form();

$form->begin('my_form', '/my/index', 'get');

$form->field('a')->defaultValue(5)->label('as')->textarea(10);

//$form->field('gender')->label('ss')->radio(['man' => 1, 'women' => 0], 'man');

$form->field('zoo')->select(['1'=> 'tiger', '2'=> 'turtle']);
$form->field('ss')->checkbox('one', 'one');

$form->end();


?>
