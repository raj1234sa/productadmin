<?php
require_once(DIR_WS_MODEL.'MenuLinksData.php');

class MenuLinksMaster extends RMasterModel {
    public function addMenuLink($MenuLinksData) {
        $FinalData = $MenuLinksData->InternalSync(RDataModel::INSERT, "menu_title", "page_link", "link_location", "display", "icon_class", "sort_order", "status");
		$this->setInsert("menu_link",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }

    public function editMenuLink($MenuLinksData) {
        $UpdateData = $MenuLinksData->InternalSync(RDataModel::UPDATE, "menu_title", "page_link", "link_location", "display", "icon_class", "sort_order", "status");
		$this->setUpdate("menu_link",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND menu_link.menu_link_id = :menu_link_id", $MenuLinksData->menu_link_id, 'int');

        return $this->exec_query();
    }

	public function deleteMenuLink($menuLinkId) {
		if(isset($menuLinkId) && ($menuLinkId!=null)) {
   			$this->setDelete("menu_link");
   			$this->setWhere("menu_link_id = :menu_link_id", $menuLinkId, 'int');

   			return $this->exec_query();
  		}
	}

    public function getMenuLinks($id = null) {
        if(!empty($id)) {
			$this->setWhere("AND menu_link_id = :menu_link_id", $id, 'int');
		}
        $this->setFrom("menu_link");
        return $this->exec_query();
    }
}
?>