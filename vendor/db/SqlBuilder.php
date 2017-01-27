<?php

namespace vendor\db;

class SqlBuilder
{
    /**
     * Access logic operators.
     *
     * @var array
     */
    private $accessOperators = [
        '=' => '=',
        '==' => '=',
        '===' => '=',
        '>=' => '>=',
        '<=' => '<=',
        '<' => '<',
        '>' => '>',
        'not' => '<>',
        'NOT' => '<>',
        '!=' => '<>',
        '!==' => '<>',
    ];

    /**
     * Names allowed aggregate functions.
     *
     * @var array
     */
    private $accessAggregateFunctions = [
        'MAX',
        'MIN',
        'COUNT',
        'AVG',
    ];

    /**
     * Allowed join types.
     *
     * @var array
     */
    private $accessJoinType = [
        'LEFT',
        'RIGHT',
        'LEFT OUTER',
        'RIGHT OUTER',
        'NATURAL LEFT',
        'NATURAL RIGHT',
        'NATURAL LEFT OUTER',
        'NATURAL RIGHT OUTER',
        'INNER',
        'FULL',
        'CROSS',
    ];

    /**
     * Object with connections to DB.
     *
     * object @var
     */
    private $connection;

    /**
     * String with generate sql query.
     *
     * string @var
     */
    private $sql;

    /**
     * Model name.
     *
     * string @var
     */
    private $table;

    /**
     * Array with information about madel table (all field and his types).
     *
     * array @var array
     */
    private $tableInfo = [];

    /**
     * Part of sql query. Contain name selected fields.
     *
     * string @var
     */
    private $select;

    /**
     * Part of sql query. Contain array with query params (limit).
     *
     * array @var array
     */
    private $where = [];

    /**
     * Part of sql query. Contain array with query field (sort).
     *
     * array @var array
     */
    private $order = [];

    /**
     * It indicates whether there is a duplicate entry in the sample.
     *
     * bool @var bool
     */
    private $distinct = false;

    /**
     * Part of sql query. Contain array with used aggregate functions.
     *
     * array @var array
     */
    private $aggregate = [];

    /**
     * Part of sql query. Contain array with field that used for grouped.
     *
     * array @var array
     */
    private $groupBy = [];

    /**
     * Part of sql query. Contain array with field that used for grouped.
     *
     * array @var array
     */
    private $having = [];

    /**
     * Part of sql query. Contain array with join.
     *
     * array @var array
     */
    private $join = [];

    /**
     * SqlBuilder constructor.
     *
     * @param object $connection connection to DB.
     * @param string $table model name.
     */
    public function __construct($connection, $table)
    {
        $this->connection = $connection;
        $this->table = $this->convertToUpperCase($table);
        $this->tableInfo = $this->getTableInformation($this->table);
    }

    /**
     * Convert to upperCase string or arrat with string.
     *
     * @param $data
     * @return array
     */
    public function convertToUpperCase($data)
    {
        if (is_string($data)) {
            $data = strtoupper($data);
        }

        if (is_array($data)) {
            foreach ($data as $key => $item) {
                $data[$key] = strtoupper($item);
            }
        }

        return $data;
    }

    /**
     * Return information about table (all field and his property).
     *
     * @param string $table table name
     * @return array mixed
     */
    public function getTableInformation($table)
    {
        return $this->connection->findAll('SHOW COLUMNS FROM ' . $table);
    }


    /**
     * Execute created sql query.
     *
     * @return mixed
     * @throws \Exception
     */
    public function find()
    {
        $this->queryBuild();
        if (empty($this->sql)) {
            throw new \Exception('You must create sql query');
        }

        return $this->connection->findAll($this->sql);
    }

    /**
     * Build sql query.
     */
    public function queryBuild()
    {
        if (!empty($this->aggregate)) {
            $selectFields = explode(',', $this->select);

            foreach ($selectFields as $key => $field) {
                $partOfField = explode('as', $field);

                if ($partOfField[1]) {
                    $partOfField[1] = "'" . trim($partOfField[1]) . "'";
                }
                foreach ($this->aggregate as $aggr) {
                    if (trim($partOfField[0]) == trim($aggr['field'])) {
                        $partOfField[0] = $aggr['func'] . '(' . trim($partOfField[0]) . ') ';
                    }
                }

                $selectFields[$key] = implode('AS ', $partOfField);
            }
            $this->select = implode(',', $selectFields);
        }
        var_dump($this->select);
        if (!empty($this->select)) {
            $this->sql = 'SELECT ';
            $this->sql .= ($this->distinct === true) ? 'DISTINCT ' : '';
            $this->sql .= $this->select;
        } else {
            $this->sql = 'SELECT *';
        }
        $this->sql .= ' ';
        $this->sql .= 'FROM ' . $this->table;
        $this->sql .= ' ';

        if (!empty($this->join)) {
            foreach ($this->join as $j) {
                $this->sql .= ' ' . $j . ' ';
            }
        }

        if (!empty($this->where)) {
            $this->sql .= 'WHERE';
            foreach ($this->where as $wherePart) {
                $this->sql .= ' ';
                $this->sql .= $wherePart['prefix']
                    . ' ' . $wherePart['field']
                    . ' ' . $wherePart['operator']
                    . ' ' . $wherePart['value'];

                $this->sql .= ' ';
            }
        }

        if (!empty($this->groupBy)) {
            $this->sql .= 'GROUP BY ' . implode(',', $this->groupBy);
        }

        if (!empty($this->having)) {
            $this->sql .= ' HAVING';
            foreach ($this->having as $wherePart) {
                $this->sql .= ' ';
                $this->sql .= $wherePart['prefix']
                    . ' ' . $wherePart['func'] . '(' . trim($wherePart['field']) . ')'
                    . ' ' . $wherePart['operator']
                    . ' ' . $wherePart['value'];

                $this->sql .= ' ';
            }
        }
        echo $this->sql;
    }

    /**
     * Sets selected fields.
     *
     * @param array $fields selected field.
     * @return $this
     * @throws \Exception
     */
    public function select(array $fields = [])
    {
        $fields = $this->convertToUpperCase($fields);

        foreach ($fields as $field) {
            $tablAndFied = array_reverse(explode('.', $field));
            $this->validate([
                [
                    'type' => 'fieldInTableExist',
                    'value' => [
                        'table' => (isset($tablAndFied[1])) ? $this->getTableInformation($tablAndFied[1]) : $this->table,
                        'field' => $tablAndFied[0],
                    ]
                ],
            ]);
        }
        if (!empty($fields)) {
            $this->select = implode(',', $fields);
        }

        return $this;
    }

    /**
     * Joined two table
     *
     * @param string $childTable name table for joined
     * @param string $fieldBaseTable name filed base table for joined
     * @param string $fieldChildTable name filed table for joined
     * @param string $operator join operator
     * @param string $type type of join
     * @return $this
     * @throws \Exception
     */
    public function join($childTable, $fieldBaseTable, $fieldChildTable, $operator = '=', $type = 'LEFT')
    {

        if (trim($this->table) == trim($childTable)) {
            throw new \Exception('You must use alias');
        }

        $childTableInfo = $this->getTableInformation($childTable);

        $this->validate([
            [
                'type' => 'tableExist',
                'value' => $childTable
            ],
            [
                'type' => 'fieldInTableExist',
                'value' => [
                    'table' => $childTableInfo,
                    'field' => $fieldChildTable,
                ],
            ],
            [
                'type' => 'fieldInTableExist',
                'value' => [
                    'table' => $this->tableInfo,
                    'field' => $fieldBaseTable
                ],
            ],
            [
                'type' => 'accessJoinType',
                'value' => $type,
            ],
            [
                'type' => 'accessLogicOperator',
                'value' => $operator,
            ],

        ]);

        $operator = $this->accessOperators[$operator];

        $this->join[] = $type . ' JOIN ' . $childTable
            . ' ON ' . $this->table . '.' . $fieldBaseTable . ' ' . $operator . ' ' . $childTable . '.' . $fieldChildTable;

        return $this;
    }

    /**
     * Sets where part sql query.
     *
     * @param string $field table field name.
     * @param string $operator where operator.
     * @param string $value value for equal with table filed.
     * @param string $prefix prefix (and, or ...).
     * @return $this
     * @throws \Exception
     */
    public function where($field, $operator, $value, $prefix = '')
    {
        $table = $this->tableInfo;

        if (strpos($field, '.')) {
            $table = $this->getTableInformation(explode('.', $field)[0]);
        };

        $this->validate([
            [
                'type' => 'accessLogicOperator',
                'value' => $operator,
            ],
            [
                'type' => 'fieldInTableExist',
                'value' => [
                    'table' => $table,
                    'field' => (strpos($field, '.')) ? explode('.', $field)[1] : $field
                ],
            ],
        ]);

        $this->where[] = [
            'field' => $field,
            'operator' => $this->accessOperators[$operator],
            'value' => $value,
            'prefix' => $prefix
        ];

        return $this;
    }

    /**
     * Sets where part with prefix = and sql query.
     * 
     * @param string $field table field name.
     * @param string $operator where operator.
     * @param string $value value for equal with table filed.
     * @return SqlBuilder
     * @throws \Exception
     */
    public function andWhere($field, $operator, $value)
    {
        if (empty($this->where)) {
            throw new \Exception('You must user where before adnWhere');
        }
        return $this->where($field, $operator, $value, 'AND');
    }

    /**
     * Sets where part with prefix = or sql query.
     * 
     * @param string $field table field name.
     * @param string $operator where operator.
     * @param string $value value for equal with table filed.
     * @return SqlBuilder
     * @throws \Exception
     */
    public function orWhere($field, $operator, $value)
    {
        if (empty($this->where)) {
            throw new \Exception('You must user where before orWhere');
        }

        return $this->where($field, $operator, $value, 'OR');
    }

    /**
     * Sets between in part sql query.
     *
     * @param string $field table field name.
     * @param array $value array with appropriate values.
     * @param string $prefix
     * @return $this
     */
    public function inWhere($field, array $value, $prefix = 'AND')
    {
        if (empty($this->where)) {
            $prefix = '';
        }

        $this->where[] = [
            'field' => $field,
            'value' => "(" . implode(',', $value) . ")",
            'prefix' => $prefix,
            'operator' => 'IN'
        ];

        return $this;
    }

    /**
     * Sets between where part sql query.
     * 
     * @param string $field name field table.
     * @param string $minValue start value for equal.
     * @param string $maxValue finish value for equal.
     * @param string $prefix query prefix.
     * @return $this
     */
    public function betweenWhere($field, $minValue, $maxValue, $prefix = 'AND')
    {
        if (empty($this->where)) {
            $prefix = '';
        }
            
        $this->where[] = [
            'field' => $field,
            'prefix' => $prefix,
            'value' => $minValue . ' AND ' . $maxValue,
            'operator' => 'BETWEEN'
        ];
        return $this;
    }

    /**
     * Sets table field for order.
     * 
     * @param string $field table field name.
     * @param string $param
     * @return $this
     */
    public function orderBy($field, $param = 'ASC')
    {
        $table = $this->tableInfo;

        if (strpos($field, '.')) {
            $table = $this->getTableInformation(explode('.', $field)[0]);
        };

        $this->validate([
            [
                'type' => 'fieldInTableExist',
                'value' => [
                    'table' => $table,
                    'field' => (strpos($field, '.')) ? explode('.', $field)[1] : $field
                ],
            ],
        ]);
        
        $this->order[] = [
            'field' => $field,
            'param' => $param
        ];
        return $this;
    }

    /**
     * Set distinct property.
     * 
     * @param bool $param
     * @return $this
     */
    public function distinct($param = false)
    {
        $this->distinct = $param;

        return $this;
    }

    /**
     * Set on table field aggregate functions.
     * 
     * @param string $field field name.
     * @param string $func aggregate function.
     * @return $this
     */
    public function aggregate($field, $func)
    {
        $table = $this->tableInfo;

        if (strpos($field, '.')) {
            $table = $this->getTableInformation(explode('.', $field)[0]);
        };

        $this->validate([
            [
                'type' => 'accessAggregateFunction',
                'value' => $func,
            ],
            [
                'type' => 'fieldInTableExist',
                'value' => [
                    'table' => $table,
                    'field' => (strpos($field, '.')) ? explode('.', $field)[1] : $field
                ],
            ],
        ]);

        $this->aggregate[] = [
            'field' => strtoupper($field),
            'func' => strtoupper($func)
        ];

        return $this;
    }

    /**
     * Count row in table.
     * 
     * @param string $field name table field.
     * @return SqlBuilder
     */
    public function count($field)
    {
        return $this->aggregate($field, 'COUNT');
    }

    /**
     * Max value 
     * 
     * @param string $field name table field.
     * @return SqlBuilder
     */
    public function max($field)
    {
        return $this->aggregate($field, 'MAX');
    }

    /**
     * Min value
     * 
     * @param string $field name table field.
     * @return SqlBuilder
     */
    public function min($field)
    {
        return $this->aggregate($field, 'MIN');
    }

    /**
     * AVG value
     * 
     * @param string $field name table field.
     * @return SqlBuilder
     */
    public function avg($field)
    {
        return $this->aggregate($field, 'AVG');
    }

    /**
     * Sets gropu by param sql query.
     * 
     * @param array $fields array with field name.
     * @return $this
     * @throws \Exception
     */
    public function groupBy(array $fields)
    {
        $table = $this->tableInfo;
        
        foreach ($fields as $field) {
            if (strpos($field, '.')) {
                $table = $this->getTableInformation(explode('.', $field)[0]);
            };

            $this->validate([
                [
                    'type' => 'fieldInTableExist',
                    'value' => [
                        'table' => $table,
                        'field' => (strpos($field, '.')) ? explode('.', $field)[1] : $field
                    ],
                ],
            ]);
        }
        $this->groupBy[] = implode(',', $fields);

        return $this;
    }

    /**
     * Sets having part sql query.
     * 
     * @param string $field table field name.
     * @param string $operator operator name.
     * @param string $value value from equal.
     * @param string $aggregateFunction name aggregate function.
     * @param string $prefix query prefix.
     * @return $this
     * @throws \Exception
     */
    public function having($field, $operator, $value, $aggregateFunction = '', $prefix = '')
    {
        if (!empty($this->where)) {
            throw new \Exception('Use having without groupBy is impossible');
        }
        
        $table = $this->tableInfo;

        if (strpos($field, '.')) {
            $table = $this->getTableInformation(explode('.', $field)[0]);
        };
        
        $this->validate([
            [
                'type' => 'accessLogicOperator',
                'value' => $operator,
            ],
            [
                'type' => 'fieldInTableExist',
                'value' => [
                    'table' => $table,
                    'field' => (strpos($field, '.')) ? explode('.', $field)[1] : $field
                ],
            ],
            [
                'type' => 'accessAggregateFunction',
                'value' => $aggregateFunction,
            ],
        ]);
        
        $this->having[] = [
            'field' => $field,
            'operator' => $this->accessOperators[$operator],
            'value' => $value,
            'func' => $aggregateFunction,
            'prefix' => $prefix
        ];

        return $this;
    }

    /**
     * Sets and having part sql query.
     * 
     * @param string $field table field name.
     * @param string $operator operator name.
     * @param string $value value from equal.
     * @param string $aggregateFunction name aggregate function.
     * @return SqlBuilder
     * @throws \Exception
     */
    public function andHaving($field, $operator, $aggregateFunction, $value)
    {
        if (empty($this->having)) {
            throw new \Exception('You must used having before adnHaving');
        }

        return $this->having($field, $operator, $value, $aggregateFunction, 'AND');
    }

    /**
     * Sets or having part sql query.
     *
     * @param string $field table field name.
     * @param string $operator operator name.
     * @param string $value value from equal.
     * @param string $aggregateFunction name aggregate function.
     * @return SqlBuilder
     * @throws \Exception
     */
    public function orHaving($field, $operator, $aggregateFunction, $value)
    {
        if (empty($this->having)) {
            throw new \Exception('You must used having before orHaving');
        }

        return $this->having($field, $operator, $value, $aggregateFunction, 'OR');
    }
    
    
    /**
     * ------------------------------------ validate methods ------------------------------------
     */

    /**
     * Validate correct input data.
     *
     * @param array $options
     * @throws \Exception
     */
    public function validate($options = [])
    {
        if (!empty($options)) {
            foreach ($options as $option) {
                switch ($option['type']) {
                    case 'tableExist' :
                        $this->checkTableExist($option['value']);
                        break;
                    case 'fieldInTableExist':
                        $this->checkExistFieldInTable($option['value']['field'], $option['value']['table']);
                        break;
                    case 'accessJoinType':
                        $this->checkAccessJoinType($option['value']);
                        break;
                    case 'accessLogicOperator':
                        $this->checkLogicOperator($option['value']);
                        break;
                    case 'accessAggregateFunction':
                        $this->checkAggregateFunction($option['value']);
                        break;
                }
            }
        }
    }


    public function checkAggregateFunction($func)
    {
        if (!in_array($func, $this->accessAggregateFunctions)) {
            throw new \Exception('Incorrect aggregate function');
        }
    }

    /**
     * Check exist table in connection database.
     *
     * @param $table
     * @throws \Exception
     */
    public function checkTableExist($table)
    {
        if (!$this->connection->findAll('show tables like "' . trim($table) . '"')) {
            throw new \Exception('Table with name ' . $table . ' is not exist');
        }
    }

    /**
     * Check on exist field in table. If exist return true, else return false.
     *
     * @param string $field field name
     * @param null|array $table table information (use getTableInformation())
     * @return bool
     * @throws \Exception
     */
    public function checkExistFieldInTable($field, $table = null)
    {
        $f = explode('AS', $field);

        foreach ($table as $fieldInTable) {

            if (strtoupper(trim($f[0])) == strtoupper($fieldInTable['Field'])) {
                return true;
            }
        }

        throw new \Exception('Filed with name ' . $field . ' in table: ' . $table . ' is not exist');
    }

    /**
     * Check on allowed join type.
     *
     * @param string $type type join
     * @throws \Exception
     */
    public function checkAccessJoinType($type)
    {
        if (!in_array($type, $this->accessJoinType)) {
            throw new \Exception('Incorrect join type');
        }
    }

    /**
     * Check on allowed logic operator.
     *
     * @param string $operator logic operator
     * @throws \Exception
     */
    public function checkLogicOperator($operator)
    {
        if (!in_array($operator, $this->accessOperators)) {
            throw new \Exception('Incorrect logic operator');
        }
    }
}
