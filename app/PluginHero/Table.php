<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero;

// @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// @phpcs:disable WordPress.Security.NonceVerification.Missing
// @phpcs:disable WordPress.Security.NonceVerification.Recommended

use BeycanPress\CryptoPayLite\PluginHero\Model\AbstractModel;

class Table extends \WP_List_Table
{
    /**
     * Item to show per page
     * @var int
     */
    private int $perPage = 10;

    /**
     * Total item count
     * @var int
     */
    private int $totalRow = 0;

    /**
     * Columns to be used for sorting
     * @var array<string>
     */
    private array $sortableColumns = [];

    /**
     * @var AbstractModel|array<mixed> $entry
     */
    private AbstractModel|array $entry;

    /**
     * @var AbstractModel|null
     */
    private AbstractModel|null $model = null;

    /**
     * @var array<array<string,\Closure>>
     */
    private array $hooks = [];

    /**
     * @var array<mixed>
     */
    private array $params = [];

    /**
     * @var array<mixed>
     */
    private array $options = [];

    /**
     * @var array<string,string>
     */
    private array $columns = [];

    /**
     * @var array<mixed>
     */
    private array $dataList = [];

    /**
     * @var array<string>
     */
    private array $orderQuery = [];

    /**
     * @var array<\Closure>
     */
    private array $headerElements = [];

    /**
     * @var array<mixed>
     */
    // @phpcs:ignore
    public $items = [];
    /**
     * @var array<int,array<string>>
     */
    // @phpcs:ignore
    protected $_column_headers = [];

    /**
     *
     * @param AbstractModel|array<mixed> $entry
     * @param array<mixed> $params
     * @param array<mixed> $args
     * @return void
     */
    public function __construct(AbstractModel|array $entry, array $params = [], array $args = [])
    {
        $this->entry = $entry;
        $this->params = $params;

        if ($entry instanceof AbstractModel) {
            $this->setModel($entry);
        } else {
            $this->setDataList($entry);
        }

        parent::__construct($args);
    }

    /**
     * @param null|\Closure $customDataList
     * @return self
     */
    public function createDataList(?\Closure $customDataList = null): self
    {
        $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
        $offset = (($paged - 1) * $this->perPage);

        if (isset($_GET['orderby'])) {
            $orderBy = sanitize_text_field($_GET['orderby']);
            $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'desc';
            $this->setOrderQuery([$orderBy, $order]);
        }

        if ($customDataList) {
            [$dataList, $dataListCount] = $customDataList($this->entry, $this->orderQuery, $this->perPage, $offset);
        }

        if (!isset($dataList) && $this->model && !$this->dataList) {
            $dataList = $this->model->findBy($this->params, $this->orderQuery, $this->perPage, $offset);
            $dataListCount = $this->model->getCount($this->params);
        } elseif (!isset($dataList) && is_array($this->dataList)) {
            $dataList = $this->getDataList();
            $dataListCount = $this->getDataListCount();
        } elseif (!isset($dataList)) {
            $dataList = [];
            $dataListCount = 0;
        }

        $this->setDataList($dataList);
        $this->setTotalRow($dataListCount ?? 0);

        return $this;
    }

    /**
     * Prepares and shows the table.
     * @return string
     */
    public function renderWpTable(): string
    {
        $this->prepare();

        if (isset($this->options['search'])) {
            Helpers::echoTemplate('table-search-form', [
                'options' => $this->options,
                'searchBox' => fn(...$args) => $this->search_box(...$args)
            ]);
        }

        if (!empty($this->headerElements)) {
            /** @var \Closure $func */
            foreach ($this->headerElements as $func) {
                call_user_func($func);
            }
        }

        ob_start();
        $this->display();
        return ob_get_clean();
    }

    /**
     * Prepare table data list
     * @return void
     */
    private function prepareDataList(): void
    {
        $dataList = $this->dataList;
        $this->dataList = [];

        foreach ($dataList as $item) {
            if (!is_array($item)) {
                $item = (array) $item;
            }

            if (!empty($this->hooks)) {
                array_map(function ($hooks) use (&$item): void {
                    $itemPlaceHolder = $item;
                    /** @var \Closure $func */
                    foreach ($hooks as $key => $func) {
                        $item[$key] = call_user_func($func, (object) $itemPlaceHolder);
                    }
                }, $this->hooks);
            }

            $this->dataList[] = array_intersect_key($item, array_flip(array_keys($this->columns)));
        }
    }

    /**
     * Makes our table ready to be shown.
     * @return void
     */
    private function prepare(): void
    {
        $this->setPerPage((isset($_GET['per-page']) ? intval($_GET['per-page']) : $this->perPage));

        // Prepare data list
        $this->prepareDataList();

        // Set pagination variables
        $totalRow = $this->totalRow > 0 ? $this->totalRow : count($this->dataList);

        $this->set_pagination_args([
            'total_items' => $totalRow,
            'per_page'    => $this->perPage
        ]);

        $this->items = array_slice($this->dataList, 0, $this->perPage);

        // phpcs:ignore
        $this->_column_headers = [$this->columns, [], $this->sortableColumns];
    }

    /**
     * @param array<string,\Closure> $hooks
     * @return self
     */
    public function addHooks(array $hooks): self
    {
        $this->hooks[] = $hooks;
        return $this;
    }

    /**
     * @param \Closure $callback
     * @return self
     */
    public function addHeaderElements(\Closure $callback): self
    {
        $this->headerElements[] = $callback;
        return $this;
    }

    /**
     * Set the columns with sorting feature in the table.
     * @param array<string> $sortableColumns
     * @return self
     */
    public function setSortableColumns(array $sortableColumns): self
    {
        array_map(function ($column): void {
            $this->sortableColumns[$column] = [$column, true];
        }, $sortableColumns);
        return $this;
    }

    /**
     * @param array<mixed> $options
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param array<string,string> $columns
     * @return self
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param array<string> $orderQuery
     * @return self
     */
    public function setOrderQuery(array $orderQuery): self
    {
        $this->orderQuery = $orderQuery;
        return $this;
    }

    /**
     * Sets the data the table displays per page.
     * @param int $perPage
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @param object $model
     * @return self
     */
    public function setModel(object $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * total number of data to be paged
     * @param int $totalRow
     * @return void
     */
    private function setTotalRow(int $totalRow): void
    {
        $this->totalRow = $totalRow;
    }

    /**
     * @param array<mixed> $dataList
     * @return void
     */
    private function setDataList(array $dataList): void
    {
        $this->dataList = $dataList;
    }

    /**
     * @return array<mixed>
     */
    public function getDataList(): array
    {
        return $this->dataList;
    }

    /**
     * @return int
     */
    public function getDataListCount(): int
    {
        return count($this->dataList);
    }

    /**
     * @return bool
     */
    public function dataListIsEmpty(): bool
    {
        return 0 == $this->getDataListCount();
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getTotalRow(): int
    {
        return $this->totalRow;
    }

    /**
     * @return object
     */
    public function getModel(): object
    {
        return $this->model;
    }

    /**
     * @return array<mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array<string,string>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array<string>
     */
    public function getOrderQuery(): array
    {
        return $this->orderQuery;
    }

    /**
     * @return array<\Closure>
     */
    public function getHeaderElements(): array
    {
        return $this->headerElements;
    }

    /**
     * @return array<array<string,\Closure>>
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }

    /**
     * @return array<string>
     */
    public function getSortableColumns(): array
    {
        return $this->sortableColumns;
    }

    /** Parent class methods */
    /**
     * Table columns to be submitted by the user
     * Mandatory and private for WordPress
     * @return array<string>
     */
    // @phpcs:ignore
    public function get_columns()
    {
        return $this->columns;
    }

    /**
     * Columns to be used for sorting
     * Mandatory and private for WordPress
     * @return array<string>
     */
    // @phpcs:ignore
    public function get_sortable_columns()
    {
        return $this->sortableColumns;
    }

    /**
     * Define what data to show on each column of the table
     * Mandatory and private for WordPress
     * @param array<mixed> $itemList current row
     * @param string $columnName - Current column name
     * @return mixed
     */
    // @phpcs:ignore
    public function column_default($itemList, $columnName)
    {
        if (in_array($columnName, array_keys($itemList))) {
            return $itemList[$columnName];
        } else {
            return esc_html__('Key not found!', 'cryptopay');
        }
    }
}
