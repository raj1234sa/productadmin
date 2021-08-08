<?php
    echo draw_action_buttons($action_buttons);
?>
<div class="table-responsive datatable-dark">
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th data-data='sr' data-class='text-center' data-orderable='false'><?php echo COMMON_SR_NO ?></th>
                <th data-data='template_constant'><?php echo EMAIL_TEMPLATE_KEY ?></th>
                <th data-data='status' data-class='text-center'><?php echo COMMON_STATUS ?></th>
                <th data-data='action' data-class='text-center' data-orderable='false'><?php echo COMMON_ACTION ?></th>
            </tr>
        </thead>
    </table>
</div>
<?php
$globalJs .= <<<JS
    $("#dataTable").PATable({
        tabletools: true,
        tablebuttons: ['print', 'export'],
        search: {
            form: '#frm',
            button: '#btn',
        },
    });
JS;
?>