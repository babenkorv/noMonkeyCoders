<?php

namespace vendor\widgets;

use vendor\components\AssetManager;

class GridView
{
    private $data = [];
    private $searchModel;
    private $columnName;
    private $columnFilter;
    private $bodyRows;
    private $rows;
    private $tableLabel;


    public function __toString()
    {
        return $this->render();
    }

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->searchModel = $data['searchModel'];
        $this->setLabels();
        $this->findAllTableRows();
        $this->setFindField();
        $this->setBody();
    }

    private function setFindField()
    {
        $columns = $this->data['column'];
        $filterColumns = [];
        foreach ($columns as $column) {
            if(!is_array($column)) {
                $filterColumns[] = ['attribute' => $column, 'filter' => ''];
            } else {
                if(isset($column['filter'])) {
                    $filterColumns[] = ($column['filter'] != '')
                        ? ['attribute' => $column['attribute'], 'filter' => $column['filter']]
                        : ['attribute' => '', 'filter' =>''];
                } else {
                    $filterColumns[] = ['attribute' => $column['attribute'], 'filter' => ''];
                }
            }
        }

        $this->columnFilter .= '<form name="test" method="get" id="find_form"></form>';
        $this->columnFilter .= '<input type="submit" form="find_form" hidden>';

        foreach ($filterColumns as $filterColumn) {
            $value = (isset($_GET[$filterColumn['attribute']])) ? $_GET[$filterColumn['attribute']]: '';
            $this->columnFilter .= ($filterColumn['attribute'] != '') ? '<th>' . '<input class="form-input" type="text" name="' . $filterColumn['attribute'] . '" form="find_form" value="' . $value  .'">' . '</th>' : '<th></th>';
        }
    }

    /**
     * Find all table rows.
     */
    private function findAllTableRows()
    {
        if(empty($_GET)) {
            $this->rows = $this->data['model']->select($this->columnName)->find();
        } else {
            $this->rows = $this->searchModel->search($_GET)->select($this->columnName)->find();
        }
    }

    /**
     * Set column name and his labels
     */
    private function setLabels()
    {
        $columns = $this->data['column'];

        $this->tableLabel = '<tr>';

        $columnsName = [];
        foreach ($columns as $column) {
            $this->tableLabel .= (isset($column['label']))
                ? '<th>' . $column['label'] . '</th>'
                :  ((isset($column['attribute'])) ? '<th>' . $column['attribute'] . '</th>' :'<th>' . $column . '</th>');

            if (is_array($column)) {
                $columnsName[] = $column['attribute'] . ' AS ' . "'" . $column['label'] . "'";
            } else {
                $columnsName[] = $column;
            }

        }
        $this->tableLabel .= '</tr>';

        $this->columnName = $columnsName;
    }

    /**
     * Set body path of table
     */
    private function setBody()
    {
        foreach ($this->rows as $row) {
            $this->bodyRows .= '<tr>';
            foreach ($row as $item) {
                $this->bodyRows .= '<td>' . $item . '</td>';
            }
            $this->bodyRows .= '</tr>';
        }
    }



    private function render()
    {
        $option = (!empty($this->data['options'])) ? $this->data['options'] : '';
        $html =
            '<table ' . $option . '>'
            . $this->tableLabel
            . $this->columnFilter
            . $this->bodyRows
            . '</table>';

        return $html;
    }
}