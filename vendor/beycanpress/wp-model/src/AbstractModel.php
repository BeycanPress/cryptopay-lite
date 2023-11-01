<?php

namespace BeycanPress\WPModel;

/**
 * @author BeycanDeveloper
 * @version 0.1.0
 * @link https://beycanpress.com
 * 
 * Our abstract model class that will allow users to create their own models and use auxiliary database processing methods
 */
class AbstractModel
{
    /**
     * The class we use to build SQL queries with parameters.
     */
    use Traits\QueryBuilder;

    /**
     * The class that does the table creation the first time the model runs.
     */
    use Traits\TableCreator;

    /**
     * @var string
     */
    public $version = '1.0.0';
    
    /**
     * The database table name you specified when creating the model. So the parent::_construct 1st parameter.
     * 
     * @var string
     */
    public $tableName = null;

    /**
     * parent::_construct 2st parameter. Retrieves table columns and properties with an array or as a direct SQL query.
     * 
     * @var string|array
     */
    private $columns = [
        'id' => [
            'type' => 'integer',
            'nullable' => true,
            'index' => [
                'type' => 'primary',
                'autoIncrement' => true
            ]
        ]
    ];

    /**
     * $wpdb example
     * 
     * @var object
     */
    protected $db;

    /**
     * The method to initialize for each model created.
     * 
     * @param array $columns
     */
    protected function __construct(array $columns = [])
    {
        $this->initWPDB();
        $this->setColumns($columns);
        $this->createTable();
    }

    /**
     * @return void
     */
    public function initWPDB()
    {
        global $wpdb;
        $this->db = $wpdb;

        if (is_null($this->tableName)) {
            $trace = debug_backtrace();
            if (isset($trace[2])) {
                $prefix = isset($this->prefix) ? $this->prefix : substr(md5($trace[2]['class']), 0, 5);
                $className = explode('\\', $trace[2]['class']);
                $tableName = isset($this->name) ? $this->name : strtolower(end($className));
            }
    
            $prefix = $wpdb->prefix . ($prefix ? $prefix . '_' : null);
            $this->tableName = $prefix . $tableName;
        } else {
            $this->tableName = $wpdb->prefix . $this->tableName;
        }
    }

    /**
     * @return string
     */
    public function getError() : string
    {
        return $this->db->last_error;
    }

    /**
     * A method of preparing SQL queries before executing them to make them more secure and desirable. It uses wpdb->prepare.
     * 
     * @param string $query
     * @param array $parameters
     * 
     * @return string
     */
    public function prepare(string $query, array $parameters) : string
    {
        if (empty($parameters)) {
            return $query;
        }

        return $this->db->prepare($query, $parameters);
    }

    /**
     * It uses wpdb->get_results to run the SQL Query.
     * 
     * @param string $query
     * @param const @output
     * 
     * @return array|object|null
     */
    protected function getResults(string $query, $output = OBJECT) 
    {
        return $this->db->get_results($query, $output);
    }

    /**
     * It uses wpdb->get_var to run the SQL Query.
     * 
     * @param string $query
     * @param int $x
     * @param int $y
     * 
     * @return mixed
     */
    protected function getVar(string $query, $x = 0, $y = 0) 
    {
        return $this->db->get_var($query, $x, $y);
    }

    /**
     * It uses wpdb->get_row to run the SQL Query. Just to fetch a single line.
     * 
     * @param string $query
     * @param const @output
     * @param int $y
     * 
     * @return array|object|null
     */
    protected function getRow(string $query, $output = OBJECT, $y = 0) 
    {
        return $this->db->get_row($query, $output, $y);
    }

    /**
     * It uses wpdb->get_col to run the SQL Query. Just to fetch a single column.
     * 
     * @param string $query
     * @param int $x
     * 
     * @return mixed
     */
    protected function getCol(string $query, $x = 0)
    {
        return $this->db->get_col($query, $x);
    }
    
    /**
     * The method you will use to add records to the table.
     * 
     * @param array $data
     * @param null|? $format
     * 
     * @return int|bool
     */
    public function insert(array $data, $format = null)
    {
        $this->db->insert($this->tableName, $data, $format); 
        return $this->db->insert_id;
    }

    /**
     * The method you use to update a record in the table.
     * 
     * @param array $data
     * @param array $where
     * @param null|? $format
     * @param null|? $whereFormat
     * 
     * @return int|bool
     */
    public function update(array $data, array $where, $format = null, $whereFormat = null)
    {
        return $this->db->update($this->tableName, $data, $where, $format, $whereFormat);
    }

    /**
     * The method we use to delete a record in the table.
     * 
     * @param array $where
     * @param null|? $whereFormat
     * 
     * @return int|bool
     */
    public function delete(array $where, $whereFormat = null)
    {
        return $this->db->delete($this->tableName, $where, $whereFormat);
    }

    /**
     * @param string $columnName
     * @return boolean
     */
    public function existColumn(string $columnName) : bool
    {
        return (bool) $this->getVar("SHOW COLUMNS FROM `{$this->tableName}` LIKE '$columnName'");
    }

    /**
     * @return boolean
     */
    public function existTable() : bool
    {
        return (bool) $this->getVar("SHOW TABLES LIKE '{$this->tableName}'");
    }

    /**
     * @param string $columnName
     * @param array $properties
     * @param string $beforeColumn
     * @return void
     */
    public function addColumn(string $columnName, array $properties, string $beforeColumn)
    {
        if ($this->existColumn($columnName)) return;
        $columnQuery = $this->arrayToSqlQuery([$columnName => $properties]);
        $this->getVar("ALTER TABLE `{$this->tableName}` ADD COLUMN $columnQuery AFTER `{$beforeColumn}`");
    }

    /**
     * @param string $columnName
     */
    public function deleteColumn(string $columnName)
    {
        if (!$this->existColumn($columnName)) return;
        $this->getVar("ALTER TABLE `{$this->tableName}` DROP COLUMN `{$columnName}`");
    }

    /**
     * @param array $columns
     * @return void
     */
    public function setColumns(array $columns)
    {
        $this->columns = array_merge($this->columns, $columns);
    }

    /**
     * A method where we can filter data from the table by sending where operations as an array.
     * 
     * @param array $predicates
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * 
     * @return array|object|null
     */
    public function findBy(array $predicates = [], array $orderBy = [], int $limit = 0, int $offset = 0)
    {
        $qb = $this->createQuery()->parsePredicates($predicates);        

        if (!empty($orderBy)) {
            $qb->orderBy($orderBy[0], $orderBy[1]);
        }

        if ($limit) {
            $qb->limit($limit);
        }

        if ($offset) {
            $qb->offset($offset);
        }

        return $this->getResults($this->prepare($qb->getQuery(), $qb->getParameters())); 
    }

    /**
     * A method where we can return a single result by filtering the where operations from the table by sending them as an array.
     * 
     * @param array $predicates
     * 
     * @return array|object|null
     */
    public function findOneBy(array $predicates = [], array $orderBy = [])
    {
        $qb = $this->createQuery()->parsePredicates($predicates);
        
        if (!empty($orderBy)) {
            $qb->orderBy($orderBy[0], $orderBy[1]);
        }
        
        return $this->getRow($this->prepare($qb->getQuery(), $qb->getParameters())); 
    }

    /**
     * Method where we can get all the data in the table.
     * 
     * @param array $orderBy
     * 
     * @return array|object|null
     */
    public function findAll(array $orderBy = [])
    {
        return $this->findBy([], $orderBy);
    }

    /**
     * A method where we can get the number of data returned as a result of the query by sending the where operations as an array.
     * 
     * @param array $predicates
     * 
     * @return array|object|null
     */
    public function getCount(array $predicates = [])
    {
        $qb = $this->createQuery()->select('COUNT(*)')->parsePredicates($predicates);
        return (int) $this->getVar($this->prepare($qb->getQuery(), $qb->getParameters())); 
    }

    /**
     * @param string $text
     * @param array $predicates
     * @return array
     */
    public function search(string $text, array $predicates = []) : array
    {
        $columns = array_keys($this->columns);

        $first = true;
        foreach ($columns as $key) {
            if ($first && !empty($predicates)) {
                $first = false;
                $predicates[] = [$key, 'LIKE', $text, 'AND'];
            } else {
                $predicates[] = [$key, 'LIKE', $text, 'OR'];
            }
        }

        return [
            'data' => $this->findBy($predicates, ['id', 'desc']),
            'count' => $this->getCount($predicates) ?? 0
        ];
    }

    /**
     * Drop table
     * @return void
     */
    public function drop()
    {
        $this->db->query("DROP TABLE IF EXISTS $this->tableName");
    }
}