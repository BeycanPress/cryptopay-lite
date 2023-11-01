<?php

namespace BeycanPress\WPModel\Traits;

trait TableCreator
{
    /**
     * @var string
     */
    private $charset;

    /**
     * @var array
     */
    private $columnTypes = [
        'integer' => 'INT',
        'smallint' => 'SMALLINT',
        'bigint' => 'BIGINT',
        'float' => 'DOUBLE',
        'boolean' => 'TINYINT(1)',
        'string' => 'VARCHAR(255)',
        'text' => 'LONGTEXT',
        'json' => 'JSON',
        'datetime' => 'DATETIME',
        'timestamp' => 'TIMESTAMP',
        'date' => 'DATE',
        'time' => 'TIME',
        'year' => 'YEAR'
    ];

    /**
     * @var array
     */
    private $indexTypes = [
        'primary' => 'PRIMARY KEY',
        'unique' => 'UNIQUE'
    ];

    /**
     * @return void
     */
    public function createTable()
    {
        if ($this->db->has_cap('collation')) {
            $this->charset = $this->db->get_charset_collate();
        }

        if (!$this->existTable()) {
            $columnQuery = $this->arrayToSqlQuery($this->columns);
            update_option($this->tableName . '_version', $this->version);
            $this->db->get_var("CREATE TABLE IF NOT EXISTS `{$this->tableName}` ($columnQuery) {$this->charset};");
        } else {
            $this->updateTable();
        }

        $this->errorCheck();
    }

    /**
     * @return void
     */
    public function updateTable()
    {
        if (get_option($this->tableName . '_version') != $this->version) {
            update_option($this->tableName . '_version', $this->version);

            $i = 1;
            $keys = array_keys($this->columns);
            foreach ($this->columns as $columnName => $properties) {
                if ($columnName == 'id') continue;
                $after = isset($properties['after']) ? $properties['after'] : $keys[($i - 1)];
                $this->addColumn($columnName, $properties, $after);
                $i++;
            }

            $oldColumns = get_option($this->tableName . '_old_columns', []);
            $removedColumns = array_diff_key($oldColumns, $this->columns);
            foreach ($removedColumns as $columnName => $properties) {
                $this->deleteColumn($columnName);
            }
            
            update_option($this->tableName . '_old_columns', $this->columns);
        } 
    }

    /**
     * @return void
     */
    private function errorCheck()
    {
        if (!empty($this->db->last_error)) {
            throw new \Exception($this->db->last_error);
        }
    }

    /**
     *
     * @param array $columns
     * @return void
     */
    private function arrayToSqlQuery(array $columns)
    {
        $sqlQuery = null;
        foreach ($columns as $column => $properties) {
            
            $sqlQuery .= "`$column` {{type}} {{nullable}} {{default}} ";

            if (!isset($properties['type'])) {
                throw new \Exception('It is mandatory to specify a type for each column.');
            }

            if (!$this->columnTypes[$properties['type']]) {
                throw new \Exception('An invalid data type was entered!');
            }

            if ($properties['type'] == 'string' && isset($properties['length'])) {
                $this->columnTypes['string'] = "VARCHAR(".$properties['length'].")";
            }

            $sqlQuery = str_ireplace('{{type}}', $this->columnTypes[$properties['type']], $sqlQuery);

            if (isset($properties['nullable']) && $properties['nullable']) {
                $sqlQuery = str_ireplace('{{nullable}}', '', $sqlQuery);
            } else {
                $sqlQuery = str_ireplace('{{nullable}}', 'NOT NULL', $sqlQuery);
            }

            if (isset($properties['default']) && $properties['default']) {
                $sqlQuery = str_ireplace('{{default}}', " DEFAULT " . strtoupper($properties['default']), $sqlQuery);
            } else {
                $sqlQuery = str_ireplace('{{default}}', '', $sqlQuery);
            }
            
            if (isset($properties['index'])) {
                if (!isset($properties['index']['type'])) {
                    throw new \Exception('It is mandatory to specify a type for each index.');
                }

                $key = $this->indexTypes[$properties['index']['type']];

                $sqlQuery .= " $key ";

                if (isset($properties['index']['autoIncrement']) && $properties['index']['autoIncrement']) {
                    $sqlQuery .= " AUTO_INCREMENT ";
                }
            }

            $sqlQuery .= ", ";

        }

        return rtrim($sqlQuery, ', ');
    }
}