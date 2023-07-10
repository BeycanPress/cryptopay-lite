<?php 

namespace BeycanPress\WPTable;

defined('ABSPATH') || die('You can use the WordPress Table Creator package only one WordPress in!');

// WP_List_Table is not loaded automatically so we need to load it in our application
if(!class_exists('WP_List_Table')){
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Table extends \WP_List_Table
{
    /**
     * Item to show per page
     * @var int
     */
    private $perPage = 10;

    /**
     * Total item count
     * @var int
     */
    private $totalRow = 0;
    
    /**
     * Columns to be used for sorting
     * @var array
     */
    private $sortableColumns = [];

    /**
     * @var object
     */
    private $model;

    /**
     * @var array
     */
    private $hooks = [];

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var array
     */
    private $dataList = [];

    /**
     * @var array
     */
    private $orderQuery = [];

    /**
     * @var array
     */
    private $headerElements = [];

    /**
     *
     * @param object $model
     * @param array $params
     * @param array $args
     * @return void
     */
    public function __construct(object $model, array $params = [], array $args = [])
    {
        $this->params = $params;
        $this->setModel($model);
        parent::__construct($args);
    }

    /**
     * @param null|\Closure $customDataList
     * @return self
     */
    public function createDataList(?\Closure $customDataList = null) : self
    {
        $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
        $offset = (($paged - 1) * $this->perPage);

        if (isset($_GET['orderby'])) {
            $orderBy = sanitize_text_field($_GET['orderby']);
            $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'desc';
            $this->setOrderQuery([$orderBy, $order]);
        }

        if ($customDataList) {
            [$dataList, $dataListCount] = $customDataList($this->model, $this->orderQuery, $this->perPage, $offset);
        }
        
        if (!isset($dataList)) {
            $dataList = $this->model->findBy($this->params, $this->orderQuery, $this->perPage, $offset);
            $dataListCount = $this->model->getCount($this->params);
        }

        $this->setDataList($dataList);
        $this->setTotalRow($dataListCount);

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
            ?>
                <form>
                    <?php if (!empty($_GET)) {
                        foreach ($_GET as $key => $value) { ?>
                            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/>
                        <?php }
                    } ?>
                    <?php $this->search_box(
                        $this->options['search']['title'], 
                        $this->options['search']['id']
                    ); ?>
                </form>
            <?php
        }

        if (!empty($this->headerElements)) {
            $headerElements = '';
            foreach ($this->headerElements as $func) {
                $headerElements .= call_user_func($func);
            }
            echo $headerElements;
        }

        ob_start();
        $this->display();
        return ob_get_clean();
    }

    /**
     * Prepare table data list
     * 
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
                array_map(function($hooks) use (&$item) {
                    foreach($hooks as $key => $func) {
                        $item[$key] = call_user_func($func, (object) $item);
                    }
                }, $this->hooks);
            }

            $this->dataList[] = array_intersect_key($item, array_flip(array_keys($this->columns)));
        }
    }

    /**
     * Makes our table ready to be shown.
     * 
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

        $this->_column_headers = array($this->columns, [], $this->sortableColumns);
    }

    /**
     * @param array $hooks
     * @return self
     */
    public function addHooks(array $hooks) : self
    {
        $this->hooks[] = $hooks;
        return $this;
    }

    /**
     * @param \Closure $callback
     * @return self
     */
    public function addHeaderElements(\Closure $callback) : self
    {
        $this->headerElements[] = $callback;
        return $this;
    }

    /**
     * Set the columns with sorting feature in the table.
     * 
     * @param array $sortableColumns
     * @return self
     */
    public function setSortableColumns(array $sortableColumns): self
    {
        array_map(function($column) {
            $this->sortableColumns[$column] = [$column, true];
        }, $sortableColumns);
        return $this;
    }

    /**
     * @param array $options
     * @return self
     */
    public function setOptions(array $options) : self
    {
        $this->options = $options;
        return $this;
    }

    
    /**
     * @param array $columns
     * @return self
     */
    public function setColumns(array $columns) : self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param array $orderQuery
     * @return self
     */
    public function setOrderQuery(array $orderQuery) : self
    {
        $this->orderQuery = $orderQuery;
        return $this;
    }

    /**
     * Sets the data the table displays per page.
     * 
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
     * @return void
     */
    private function setModel(object $model) : void
    {
        $this->model = $model;
    }

    /**
     * total number of data to be paged
     * 
     * @param int $totalRow
     * @return void
     */
    private function setTotalRow(int $totalRow): void
    {
        $this->totalRow = $totalRow;
    }

    /**
     * @param array $dataList
     * @return void
     */
    private function setDataList(array $dataList) : void
    {
        $this->dataList = $dataList;
    }

    /** Parent class methods */
    
    /**
     * Table columns to be submitted by the user
     * Mandatory and private for WordPress
     * 
     * @return array
     */
    public function get_columns(): array
    {
        return $this->columns;
    }

    /**
     * Columns to be used for sorting
     * Mandatory and private for WordPress
     * 
     * @return array
     */
    public function get_sortable_columns(): array
    {
        return $this->sortableColumns;
    }

    /**
     * Define what data to show on each column of the table
     * Mandatory and private for WordPress
     * 
     * @param array $itemList current row
     * @param string $columnName - Current column name
     *
     * @return mixed
     */
    public function column_default($itemList, $columnName)
    {
        if (in_array($columnName, array_keys($itemList))) {
            return $itemList[$columnName];
        } else {
            return esc_html__('Key not found!');
        }
    }

    /**
     * @param string $id
     * @param array $options
     * @return string
     */
    public function renderDataTable(string $id, array $options = []) : string
    {
        wp_enqueue_style('bwptable-data-table', 'https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/r-2.3.0/sb-1.3.4/datatables.min.css');
    
        wp_enqueue_script('bwptable-pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js', [], NULL, true); 
        wp_enqueue_script('bwptable-vfs-font', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js', [], NULL, true); 
        wp_enqueue_script('bwptable-data-table', 'https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/r-2.3.0/sb-1.3.4/datatables.min.js', ['jquery'], NULL, true); 
        
        return include dirname(__DIR__) . '/views/data-table.php';
    }
}