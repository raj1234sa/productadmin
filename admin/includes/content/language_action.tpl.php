<?php echo drawActionButtons($actionButtons) ?>
<form id="filterForm">
    <?php
    echo formText('text', 'keyword', '', array('placeholder' => COMMON_SEARCH));
    echo formButton('button', COMMON_SEARCH, 'btn-outline-primary ml-2', array('id' => 'search'));
    ?>
</form>

<div class="table-responsive">
    <table class="table dataTable ajax datatable-dark table-bordered" id="dataTable">
        <thead class="text-capitalize">
            <tr>
                <th data-data="language_id" data-class="text-center" data-orderable="false">Language Id</th>
                <th data-data="language_name">Language Name</th>
                <th data-data="status" data-class="text-center">Status</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    var tabletools = ['export', 'print'];
</script>