<?php
/**
 * Created by PhpStorm.
 * User: rbabenko
 * Date: 07.02.2017
 * Time: 15:23
 */

namespace vendor\widgets;


use models\User;
use vendor\components\Auth;

class Form
{
    private $field;
    private $config = [];
    private $formName;
    private $value;
    private $label;

    public function field($fieldName)
    {
        $this->field = '';
        $this->value = '';
        $this->field = $fieldName;

        return $this;
    }

    public function defaultValue($default = '')
    {
        $this->value = $default;

        return $this;
    }

    public function text()
    {
        echo (!empty($this->label)) ? '<label class="form-label" for="' . $this->field . '">' . $this->label . '</label>' : '';
        echo '<input type="text" name="' . $this->field . '"' . 'value="' . $this->value . '"' . 'form="' . $this->formName . '">';
    }

    public function email()
    {
        echo (!empty($this->label)) ? '<label class="form-label" for="' . $this->field . '">' . $this->label . '</label>' : '';
        echo '<input type="email" name="' . $this->field . '"' . 'value="' . $this->value . '"' . 'form="' . $this->formName . '">';
    }

    public function radio($value = [], $active = '')
    {
        echo '<div class="form-group">';
        echo '<label class="form-label">' . $this->label . '</label>';
        foreach ($value as $key => $value) {
            echo '<label class="form-radio">';
            echo '<input type="radio" name="' . $this->field . '" value="' . $value . '"';
            echo ($key == $active) ? 'checked' . ' />' : ' />';
            echo '<i class="form-icon"></i>' . $key;
            echo '</label>';
        }
        echo '</div>';
    }

    public function checkbox($value, $message)
    {
        echo '<div class="form-group">';
        echo '<label class="form-checkbox">';
        echo '<input type="checkbox" name="' . $this->field . '" value="' . $value . '"/>';
        echo ' <i class="form-icon"></i>' . $message;
        echo '</label>';
        echo '</div>';
    }

    public function textarea($rows = 5)
    {
        echo '<div class="form-group">';
        echo (!empty($this->label)) ? '<label class="form-label" for="' . $this->field . '">' . $this->label . '</label>' : '';
        echo '<textarea class="form-input" name="' . $this->field . '"' . 'value="' . $this->value . '"' . 'form="' . $this->formName . '" rows="' . $rows . '"> </textarea>';
        echo '</div>';
    }

    public function select($data = [])
    {
        echo '<div class="form-group">';
        echo '<select class="form-select" form="' . $this->formName . '" name="' . $this->field . '">';

        foreach ($data as $key => $value) {
            echo '<option  value="' . $key . '"> ' . $value . '</option>';
        }

        echo '</select>';
        echo '</div>';
    }

    public function label($label)
    {
        $this->label = $label;

        return $this;
    }

    private function submitToken()
    {
        echo '<input type="text" hidden value="' . Auth::getUserToken() . '">';
    }
    
    public function begin($name, $action, $method = 'GET', $config = [])
    {
        $this->formName = $name;
        $this->config = $config;
        echo '<form action="' . $action . '" id="' . $name . '" method="' . $method . '">';
        return $this;
    }

    public function end()
    {
        $this->submitToken();
        echo '<input type="submit" id="' . $this->formName . '">';
        echo '</form>';
    }
}