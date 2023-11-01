<?php

namespace BeycanPress\WPModel\Traits;

trait QueryBuilder
{
    /**
     * @var string|null
     */
    private $queryText = null;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param string|null $alias
     * @return self
     */
    protected function createQuery(string $alias = null) : self
    {
        $query = " SELECT * FROM $this->tableName ";
        $this->parameters = [];

        if (!is_null($alias)) {
            $query .= " AS $alias ";
        }
        
        $this->queryText = $query;
        return $this;
    }

    /**
     * @param string ...$columns
     * @return self
     */
    protected function select(string ...$columns) : self
    {
        $this->queryText = str_ireplace('*', implode(', ', $columns), $this->queryText);
        return $this;
    }

    /**
     * @param string $tableName
     * @param string $alias
     * @param string $predicate
     * @return self
     */
    protected function innerJoin(string $tableName, string $alias, string $predicate) : self
    {
        $this->addJoin($tableName, $alias, $predicate, 'INNER');
        return $this; 
    }

    /**
     * @param string $tableName
     * @param string $alias
     * @param string $predicate
     * @return self
     */
    protected function leftJoin(string $tableName, string $alias, string $predicate) : self
    {
        $this->addJoin($tableName, $alias, $predicate, 'LEFT');
        return $this; 
    }

    /**
     * @param string $tableName
     * @param string $alias
     * @param string $predicate
     * @return self
     */
    protected function rightJoin(string $tableName, string $alias, string $predicate) : self
    {
        $this->addJoin($tableName, $alias, $predicate, 'RIGHT');
        return $this; 
    }

    /**
     * @param string $tableName
     * @param string $alias
     * @param string $predicate
     * @return self
     */
    protected function fullJoin(string $tableName, string $alias, string $predicate) : self
    {
        $this->addJoin($tableName, $alias, $predicate, 'FULL');
        return $this; 
    }
    
    /**
     * @param string $predicate
     * @param mixed $parameter
     * @return self
     */
    protected function where(string $predicate, $parameter = null) : self
    {
        $this->addWhere($predicate, $parameter);
        return $this; 
    }

    /**
     * @param string $predicate
     * @param mixed $parameter
     * @return self
     */
    protected function andWhere(string $predicate, $parameter = null) : self
    {
        $this->addWhere($predicate, $parameter, 'AND');
        return $this; 
    }

    /**
     * @param string $predicate
     * @param mixed $parameter
     * @return self
     */
    protected function orWhere(string $predicate, $parameter = null) : self
    {
        $this->addWhere($predicate, $parameter, 'OR');
        return $this; 
    }

    /**
     * @param array $predicates
     * @return self
     */
    protected function parsePredicates(array $predicates) : self
    {
        $type = 'AND';
        if (empty($predicates)) {
            return $this;
        }

        foreach ($predicates as $columnName => $parameter) {
            if (is_array($parameter)) {
                $predicate = $parameter;
                $columnName = $predicate[0];
                $condition = mb_strtoupper($predicate[1], 'UTF-8');
                $parameter = $predicate[2];
                $type = isset($predicate[3]) ? mb_strtoupper($predicate[3], 'UTF-8') : $type;
                $parameterType = $this->getParameterType($parameter);
                if ($condition == 'IN') {
                    $parameter = array_map(function ($parameter) {
                        return "'$parameter'";
                    }, $parameter);
                    $parameterType = '('.implode(',', $parameter).')';
                } elseif ($condition == 'LIKE') {
                    $parameterType = "'%".$this->db->esc_like($parameter)."%'";
                    $parameter = null;
                }
                $predicate = "`$columnName` $condition $parameterType";
            } else {
                $type = 'AND';
                $parameterType = $this->getParameterType($parameter);
                $predicate = "`$columnName` = $parameterType";
            }
            $this->addWhere($predicate, $parameter, $type);
        }

        return $this;
    }

    /**
     * @param string $predicate
     * @param mixed $parameter
     * @param string $operator
     * @return void
     */
    protected function addJoin(string $tableName, string $alias, string $predicate, string $type) : void
    {
        $tableName = $this->addPrefix($tableName);
        $this->queryText .= " $type JOIN $tableName AS $alias ON $predicate ";
    }

    /**
     * @param string $predicate
     * @param mixed $parameter
     * @param string $operator
     * @return void
     */
    protected function addWhere(string $predicate, $parameter, $type = 'AND') : void
    {
        if (!is_null($parameter)) {
            $this->setParameter($parameter);
        }

        if (strpos($this->queryText, 'WHERE') === false) {
            $type = 'WHERE';
        }

        $this->queryText .= " $type $predicate ";
    }

    /**
     * @param mixed $parameter
     * @return void
     */
    protected function setParameter($parameter) : void
    {
        if (is_array($parameter)) {
            return;
        } elseif (is_bool($parameter)) {
            $parameter = $parameter ? 1 : 0;
        }

        $this->parameters[] = $parameter;
    }

    /**
     * @param mixed $parameter
     * @return string
     */
    protected function getParameterType($parameter) : string
    {
        if (is_string($parameter) || is_array($parameter)) {
            return '%s';
        } elseif (is_float($parameter)) {
            return '%f';
        } elseif (is_int($parameter) || is_bool($parameter)) {
            return '%d';
        } else {
            return '%%';
        }
    }

    /**
     * @param int $number
     * @return self
     */
    protected function limit(int $number) : self
    {
        $this->parameters[] = $number;
        $this->queryText .= " LIMIT %d ";
        return $this; 
    }

    /**
     * @param int $number
     * @return self
     */
    protected function offset(int $number) : self
    {
        $this->parameters[] = $number;
        $this->queryText .= " OFFSET %d ";
        return $this; 
    }

    /**
     * @param string ...$columns
     * @return self
     */
    protected function groupBy(string ...$columns) : self
    {
        $this->queryText .= " GROUP BY ".implode(', ', $columns)." ";
        return $this; 
    }

    /**
     * @param string $column
     * @param string $predicate
     * @return self
     */
    protected function orderBy(string $column, string $predicate) : self
    {
        $predicate = strtoupper($predicate);
        $this->queryText .= " ORDER BY $column $predicate ";
        return $this; 
    }

    /**
     * @param string $table
     * @return string
     */
    protected function addPrefix(string $table) : string
    {
        return $this->prefix . $table;
    }

    /**
     * @return string
     */
    protected function getQuery() : string
    {
        return $this->queryText;
    }

    /**
     * @return array
     */
    protected function getParameters() : array
    {
        return $this->parameters;
    }

}