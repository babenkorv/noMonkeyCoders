<?php

namespace db\adapters;

use vendor\db\DB;

class SQLITEAdapter extends DB
{

    private $options;

    public function __construct($options)
    {
        try {
            $this->pdo = new \PDO($options['scheme'] . ':' . $options['path']);
        } catch
        (\PDOException $e) {
            print "Error connect to SQLite !: " . $e->getMessage();
            die();
        }
    }
}