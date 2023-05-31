<table id="<?php echo esc_attr($id); ?>" class="display">
    <thead>
        <tr>
            <?php foreach ($this->columns as $column) : ?>
                <th><?php echo $column; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->dataList as $item): ?>
            <tr>
                <?php 
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
                    
                    $items = array_intersect_key($item, array_flip(array_keys($this->columns)));

                    foreach ($items as $val) : ?>
                    <td><?php print($val); ?></td>
                    <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    (($) => {
        $('document').ready(() => {
            let table = $('#<?php echo esc_js($id); ?>').DataTable(<?php echo json_encode($options); ?>);
            table.buttons().container().appendTo($('.col-sm-6:eq(0)', table.table().container()));
        });
    })(jQuery);
</script>