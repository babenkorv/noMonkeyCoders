<?php

namespace models;

use vendor\components\Model;

class MyModel extends  Model
{
    public function tableName()
    {
        return 'a';
    }

    public function rule()
    {
        return [

            [['name'], 'email'],
            
        ];
    }
    
 
}


