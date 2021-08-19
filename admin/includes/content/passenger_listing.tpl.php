<?php
    echo draw_action_buttons($action_buttons);
?>
<form id="frm" class="form-inline mb-2">
    <?php
        echo form_text('keyword', '', array('element_class'=>'mr-1', 'placeholder'=>COMMON_SEARCH));
        echo form_button('button', '<i class="ti-search mr-2"></i>'.COMMON_SEARCH, 'btn-primary mr-1', array('id'=>'btn'));
        echo form_button('button', 'Reset', 'reset-btn');
    ?>
</form>
<div class="table-responsive datatable-dark">
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th data-data='sr' data-class='text-center' data-orderable='false'><?php echo COMMON_SR_NO ?></th>
                <th data-data='user_info'><?php echo PASSENGER_PERSONAL_DETAILS ?></th>
                <th data-data='address_info'><?php echo COMMON_ADDRESS_DETAILS ?></th>
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