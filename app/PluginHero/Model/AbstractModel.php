<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Model;

/**
 * @author BeycanDeveloper
 * @version 0.1.0
 * @link https://beycanpress.com
 * Our abstract model class that will allow users to create their
 * own models and use auxiliary database processing methods
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
    public string $version = '1.0.0';
    /**
     * The database table name you specified when creating the model. So the parent::_construct 1st parameter.
     * @var string
     */
    public string $tableName;

    /**
     * parent::_construct 2st parameter. Retrieves table columns and properties with an array or as a direct SQL query.
     * @var array<string,array<mixed>>
     */
    private array $columns = [
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
     * @var object
     */
    protected object $db;

    /**
     * The method to initialize for each model created.
     * @param array<string,array<mixed>> $columns
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
    public function initWPDB(): void
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->tableName = $wpdb->prefix . $this->tableName;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->db->last_error;
    }

    /**
     * A method of preparing SQL queries before executing them to make them more secure and desirable.
     * It uses wpdb->prepare.
     * @param string $query
     * @param mixed ...$parameters
     * @return string
     */
    public function prepare(string $query, mixed ...$parameters): string
    {
        if (empty($parameters)) {
            return $query;
        }

        return $this->db->prepare($query, ...$parameters);
    }

    /**
     * @param string $query
     * @return void
     */
    protected function query(string $query): void
    {
        $this->db->query($query);
    }

    /**
     * It uses wpdb->get_results to run the SQL Query.
     * @param string $query
     * @param mixed $output
     * @return array<mixed>|object|null
     */
    protected function getResults(string $query, mixed $output = OBJECT): array|object|null
    {
        return $this->db->get_results($query, $output);
    }

    /**
     * It uses wpdb->get_var to run the SQL Query.
     * @param string $query
     * @param int $x
     * @param int $y
     * @return mixed
     */
    protected function getVar(string $query, int $x = 0, int $y = 0): mixed
    {
        return $this->db->get_var($query, $x, $y);
    }

    /**
     * It uses wpdb->get_row to run the SQL Query. Just to fetch a single line.
     * @param string $query
     * @param mixed $output
     * @param int $y
     * @return array<mixed>|object|null
     */
    protected function getRow(string $query, mixed $output = OBJECT, int $y = 0): array|object|null
    {
        return $this->db->get_row($query, $output, $y);
    }

    /**
     * It uses wpdb->get_col to run the SQL Query. Just to fetch a single column.
     * @param string $query
     * @param int $x
     * @return mixed
     */
    protected function getCol(string $query, int $x = 0): mixed
    {
        return $this->db->get_col($query, $x);
    }

    /**
     * The method you will use to add records to the table.
     * @param array<mixed> $data
     * @param mixed $format
     * @return int|bool
     */
    public function insert(array $data, mixed $format = null): int|bool
    {
        $this->db->insert($this->tableName, $data, $format);
        return $this->db->insert_id;
    }

    /**
     * The method you use to update a record in the table.
     * @param array<mixed> $data
     * @param array<mixed> $where
     * @param mixed $format
     * @param mixed $whereFormat
     * @return int|bool
     */
    public function update(array $data, array $where, mixed $format = null, mixed $whereFormat = null): int|bool
    {
        return $this->db->update($this->tableName, $data, $where, $format, $whereFormat);
    }

    /**
     * The method we use to delete a record in the table.
     * @param array<mixed> $where
     * @param mixed $whereFormat
     * @return int|bool
     */
    public function delete(array $where, mixed $whereFormat = null): int|bool
    {
        return $this->db->delete($this->tableName, $where, $whereFormat);
    }

    /**
     * @param string $columnName
     * @return boolean
     */
    public function existColumn(string $columnName): bool
    {
        return (bool) $this->getVar("SHOW COLUMNS FROM `{$this->tableName}` LIKE '$columnName'");
    }

    /**
     * @return boolean
     */
    public function existTable(): bool
    {
        return (bool) $this->getVar("SHOW TABLES LIKE '{$this->tableName}'");
    }

    /**
     * @param string $columnName
     * @param array<mixed> $properties
     * @param string $beforeColumn
     * @return void
     */
    public function addColumn(string $columnName, array $properties, string $beforeColumn): void
    {
        if ($this->existColumn($columnName)) {
            return;
        }

        $columnQuery = $this->arrayToSqlQuery([$columnName => $properties]);
        $this->getVar("ALTER TABLE `{$this->tableName}` ADD COLUMN $columnQuery AFTER `{$beforeColumn}`");
    }

    /**
     * @param string $columnName
     * @return void
     */
    public function deleteColumn(string $columnName): void
    {
        if (!$this->existColumn($columnName)) {
            return;
        }

        $this->getVar("ALTER TABLE `{$this->tableName}` DROP COLUMN `{$columnName}`");
    }

    /**
     * @param array<string,array<mixed>> $columns
     * @return void
     */
    public function setColumns(array $columns): void
    {
        $this->columns = array_merge($this->columns, $columns);
    }

    /**
     * A method where we can filter data from the table by sending where operations as an array.
     * @param array<mixed> $predicates
     * @param array<string> $orderBy
     * @param int $limit
     * @param int $offset
     * @return array<mixed>|object|null
     */
    // @phpcs:ignore
    public function findBy(array $predicates = [], array $orderBy = [], int $limit = 0, int $offset = 0): array|object|null
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

        return $this->getResults($this->prepare($qb->getQuery(), ...$qb->getParameters()));
    }

    /**
     * A method where we can return a single result
     * by filtering the where operations from the table by sending them as an array.
     * @param array<mixed> $predicates
     * @param array<string> $orderBy
     * @return array<mixed>|object|null
     */
    public function findOneBy(array $predicates = [], array $orderBy = []): array|object|null
    {
        $qb = $this->createQuery()->parsePredicates($predicates);

        if (!empty($orderBy)) {
            $qb->orderBy($orderBy[0], $orderBy[1]);
        }

        return $this->getRow($this->prepare($qb->getQuery(), ...$qb->getParameters()));
    }

    /**
     * Method where we can get all the data in the table.
     * @param array<string> $orderBy
     * @return array<mixed>|object|null
     */
    public function findAll(array $orderBy = []): array|object|null
    {
        return $this->findBy([], $orderBy);
    }

    /**
     * A method where we can get the number of data returned
     * as a result of the query by sending the where operations as an array.
     * @param array<mixed> $predicates
     * @return int
     */
    public function getCount(array $predicates = []): int
    {
        $qb = $this->createQuery()->select('COUNT(*)')->parsePredicates($predicates);
        return (int) $this->getVar($this->prepare($qb->getQuery(), ...$qb->getParameters()));
    }

    /**
     * @param string $text
     * @param array<mixed> $predicates
     * @return array<mixed>
     */
    public function search(string $text, array $predicates = []): array
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
            'count' => $this->getCount($predicates)
        ];
    }

    /**
     * Drop table
     * @return void
     */
    public function drop(): void
    {
        $this->db->query("DROP TABLE IF EXISTS $this->tableName");
    }
}
