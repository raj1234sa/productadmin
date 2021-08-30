<?php
    echo drawActionButtons($actionButtons);
?>
<form id="frm" class="form-inline mb-2">
    <?php
        echo formText('keyword', '', array('element_class'=>'mr-1', 'placeholder'=>COMMON_SEARCH));
        echo formButton('button', '<i class="ti-search mr-2"></i>'.COMMON_SEARCH, 'btn-primary mr-1', array('id'=>'btn'));
        echo formButton('button', 'Reset', 'reset-btn');
    ?>
</form>
<div class="table-responsive datatable-dark">
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th data-data='sr' data-class='text-center' data-orderable='false'><?= COMMON_SR_NO ?></th>
                <th data-data='user_info'><?= PASSENGER_PERSONAL_DETAILS ?></th>
                <th data-data='address_info'><?= COMMON_ADDRESS_DETAILS ?></th>
                <th data-data='status' data-class='text-center'><?= COMMON_STATUS ?></th>
                <th data-data='action' data-class='text-center no-print' data-orderable='false'><?= COMMON_ACTION ?></th>
            </tr>
        </thead>
    </table>
</div>
<?php
if($SITE_VAR_PASSENGER_SHOW_PASSWORD) {
    $labelColClass = 3;
?>
<div class="modal fade" id="show_password">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= PASSENGER_LOGIN_DETAILS ?></h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <?php
                    echo formElement(COMMON_EMAIL_ADDRESS, 'label', '', '', '', array('element_class'=>'email_modal'));
                    echo formElement(COMMON_PASSWORD, 'label', '', '', '', array('element_class'=>'password_modal'));
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= COMMON_CLOSE ?></button>
            </div>
        </div>
    </div>
</div>
<?php } ?>