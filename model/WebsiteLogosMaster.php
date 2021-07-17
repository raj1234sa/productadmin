<?php
require_once(DIR_WS_MODEL.'WebsiteLogosData.php');

class WebsiteLogosMaster extends RMasterModel {
    public function addWebsiteLogos($WebsiteLogosData) {
        $FinalData = $WebsiteLogosData->InternalSync(RDataModel::INSERT, "site_logo", "site_favicon");
		$this->setInsert("website_logos",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }

    public function editWebsiteLogos($WebsiteLogosData) {
        $UpdateData = $WebsiteLogosData->InternalSync(RDataModel::UPDATE, "site_logo", "site_favicon");
		$this->setUpdate("website_logos",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND website_logos.logo_id = :logo_id", $WebsiteLogosData->logo_id, 'int');

        return $this->exec_query();
    }

    public function getWebsiteLogos($id = null) {
        if(!empty($id)) {
			$this->setWhere("AND logo_id = :logo_id", $id, 'int');
		}
        $this->setFrom("website_logos");
        return $this->exec_query();
    }
}
?>