<?php
require_once(DIR_WS_MODEL.'AdminPagesData.php');

class AdminPagesMaster extends RMasterModel {
    public function getAdminPage() {
        $this->setFrom("admin_pages");
        return $this->exec_query();
    }
}
?>