<?php
require_once(DIR_WS_MODEL.'AdminMenuData.php');

class AdminMenuMaster extends RMasterModel {
    public function getAdminMenu($join = '') {
        $this->setFrom("admin_section_menu");
        if(!empty($join)) {
            $this->setJoin("LEFT JOIN admin_section_menu_description ON admin_section_menu_description.menu_id = admin_section_menu.menu_id");
        }
        return $this->exec_query();
    }
}
?>