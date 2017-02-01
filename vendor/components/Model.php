<?php

namespace vendor\components;

use vendor\db\DataBaseConnect;
use vendor\db\SqlBuilder;

abstract class Model extends SqlBuilder
{
    public $attribute = [];
    public $oldAttribute = [];

    abstract public function tableName();

    public function __construct()
    {
        parent::__construct(DataBaseConnect::instance(null)->getAdapter(), $this->tableName());

        $this->attribute = $this->getAttribute();
    }

    private function getAttribute()
    {
        $attribute = [];
        foreach ($this->tableInfo as $field) {
            $attribute[$field['Field']] = [
                'type' => $field['Type'],
                'null' => $field['Null'],
                'auto_increment' => $field['Extra'],
                'value' => '',
            ];
        }

        return $attribute;
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->attribute)) {
            $this->attribute[$name]['value'] = $value;
        } else {
            throw new \Exception('Attribute with name: ' . '"' . $name . '"' . 'in table:' . ' "' . $this->tableName() . '"' . ' is not exist');
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attribute)) {
            echo $this->attribute[$name]['value'];
            return $this->attribute[$name]['value'];
        } else {
            throw new \Exception('Attribute with name: ' . '"' . $name . '"' . 'in table:' . ' "' . $this->tableName() . '"' . ' is not exist');
        }
    }

    public function save()
    {
        $this->clearSqlPart();
        $data = [];

        foreach ($this->attribute as $fieldName => $fieldValue) {
            if ($fieldValue['value'] != '') {
                $data[$fieldName] = $fieldValue['value'];
            }
        }

        if (!empty($data)) {

            if (empty($this->oldAttribute)) {

                $this->insert($data)->execute();
                $this->oldAttribute = $this->attribute;
                $this->oldAttribute['id']['value'] = $this->lastInsertId();
            } else {
                $count = 0;
                foreach ($this->oldAttribute as $fieldName => $fieldValue) {
                    $this->where($fieldName, '=', (!empty($fieldValue['value'])) ? '"' . $fieldValue['value'] . '"' : null, ($count === 0) ? '' : ' and');
                    $count++;
                }

                $this->update($data)->execute();
            }
        }
    }

    public function findOne()
    {
        $modelData =  parent::findOne();

        foreach ($modelData as $key => $value) {
            $this->oldAttribute[$key]['value'] = $value;
        }

    }
}

