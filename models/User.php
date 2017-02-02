<?php
/**
 * Created by PhpStorm.
 * User: rbabenko
 * Date: 01.02.2017
 * Time: 16:57
 */

namespace models;


use vendor\components\Model;

class User extends Model
{
    public $authHash = null;
    
    public function tableName()
    {
        return 'user';
    }

    public function rule()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
        ];
    }
}