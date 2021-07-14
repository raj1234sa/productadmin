<?php
require_once(DIR_WS_MODEL.'AdminSectionData.php');

class AdminSectionMaster extends RMasterModel {
    public function getAdminSection($join='') {
        $this->setFrom("admin_section");
        if(!empty($join)) {
            $this->setJoin("LEFT JOIN admin_section_description ON admin_section_description.section_id = admin_section.section_id");
        }
        return $this->exec_query();
    }
}
?>