<?php

namespace vendor\components;


trait Validator
{
    private $rules = [];
    
    private $error = [];
    
    abstract public function rule();
    
    public function validate()
    {
        echo 'zdraste';
        $this->rules = $this->rule();

        foreach ($this->rules as $rule) {
            $param = [];
            $fields = $rule[0];
            $validator = 'validate' . ucfirst($rule[1]);
            if(!empty($rule['param'])) {
                $param = $rule['param'];
            }

            if(is_array($fields)) {
                foreach ($fields as $field) {
                    $this->$validator($fields, $param);
                }
            } else {
                $this->$validator($fields, $param);
            }
        }

        if (empty($this->error)) {
            return true;
        }

        return false;
    }

    public function addError($field, $errorDescription)
    {

        array_push($this->error[$field], $errorDescription);
    }

    public function validateEqual($field, $param)
    {
       var_dump($this->attribute);

    }

    public function validateRequired($field, $param)
    {
        var_dump('required');
    }

    public function validateEmail($field, $param)
    {
       var_dump('email');
    }
}