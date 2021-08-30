<?php
    echo drawActionButtons($actionButtons);
?>
<form id="frm" class="form-inline mb-2">
    <?php
        echo formText('keyword', '', array('element_class'=>'mr-1', 'placeholder'=>COMMON_SEARCH));
        echo formButton('button', '<i class="ti-search mr-2"></i>'.COMMON_SEARCH, 'btn-primary mr-1', array('id'=>'btn'));
        echo formButton('button', COMMON_RESET, 'reset-btn');
    ?>
</form>
<div class="table-responsive datatable-dark">
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th data-data='sr' data-class='text-center' data-orderable='false'><?= COMMON_SR_NO ?></th>
                <th data-data='menu_title' data-class='text-center'><?= COMMON_TITLE ?></th>
                <th data-data='link_location' data-class='text-center'><?= LINK_LOCATION ?></th>
                <th data-data='page_link' data-class='text-center'><?= MENU_PAGE_LINK ?></th>
                <th data-data='display' data-class='text-center'><?= COMMON_DISPLAY ?></th>
                <th data-data='status' data-class='text-center'><?= COMMON_STATUS ?></th>
                <th data-data='action' data-class='text-center no-print' data-orderable='false'><?= COMMON_ACTION ?></th>
            </tr>
        </thead>
    </table>
</div>
<?php
$globalJs = <<<JS
    $("#dataTable").PATable({
        serverSide: true,
        tabletools: true,
        tablebuttons: ['print', 'export'],
        search: {
            form: '#frm',
            button: '#btn',
        }
    });
JS;
?>