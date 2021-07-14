<?php
require_once(DIR_WS_MODEL.'AdminData.php');

class AdminMaster extends RMasterModel {
    public function editAdmin($AdminData) {
        $UpdateData = $AdminData->InternalSync(RDataModel::UPDATE, "admin_username","admin_password","is_superadmin");
		$this->setUpdate("admin",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND admin.admin_id = :admin_id", $AdminData->admin_id, 'int');

        return $this->exec_query();
    }

    public function getAdmin($id = null) {
        if(!empty($id)) {
			$this->setWhere("AND admin_id = :admin_id", $id, 'int');
		}
        $this->setFrom("admin");
        return $this->exec_query();
    }
}
?>