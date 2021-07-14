<?php
include_once DIR_WS_MODEL_CLASSES.'RDataModel.php';

class AdminData extends RDataModel {
    protected function PropertyMap() {
 		$PropMap = array();

		$PropMap['admin_id'] = array('Field', 'admin_id', 'admin_id', 'int');
		$PropMap['admin_username'] = array('Field', 'admin_username', 'admin_username', 'string');
		$PropMap['admin_password'] = array('Field', 'admin_password', 'admin_password', 'string');
		$PropMap['is_superadmin'] = array('Field', 'is_superadmin', 'is_superadmin', 'string');

		return $PropMap;
	}
}
?>