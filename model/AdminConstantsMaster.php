<?php
require_once(DIR_WS_MODEL.'AdminConstantsData.php');

class AdminConstantsMaster extends RMasterModel {
    public function addAdminConstants($AdminConstantsData) {
        $FinalData = $AdminConstantsData->InternalSync(RDataModel::INSERT, "constant_id","constant_name","section_menu_id");
		$this->setInsert("admin_constants",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }

    public function addAdminConstantsDesc($AdminConstantsData) {
        $FinalData = $AdminConstantsData->InternalSync(RDataModel::INSERT, "constant_id","constant_value","language_id");
		$this->setInsert("admin_constants_description",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }

    public function editAdminConstants($AdminConstantsData) {
		$UpdateData = $AdminConstantsData->InternalSync(RDataModel::UPDATE, "constant_name","section_menu_id");
		$this->setUpdate("admin_constants",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND admin_constants.constant_id = :constant_id", $AdminConstantsData->constant_id, 'int');

        return $this->exec_query();
    }

    public function editAdminConstantsDesc($AdminConstantsData) {
		$UpdateData = $AdminConstantsData->InternalSync(RDataModel::UPDATE, "constant_value");
		$this->setUpdate("admin_constants_description",$UpdateData['query'], $UpdateData['params']);
        $this->setWhere('AND admin_constants_description.constant_id = :constant_id', $AdminConstantsData->constant_id, 'int');
        $this->setWhere('AND admin_constants_description.language_id = :language_id', $AdminConstantsData->language_id, 'int');

        return $this->exec_query();
    }

    public function getAdminConstants($join = '') {
        if(!empty($join)) {
			$this->setJoin("LEFT JOIN admin_constants_description ON admin_constants_description.constant_id = admin_constants.constant_id");
		}
        $this->setFrom("admin_constants");
        return $this->exec_query();
    }
}
?>