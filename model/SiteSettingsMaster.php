<?php
require_once(DIR_WS_MODEL.'SiteSettingsData.php');

class SiteSettingsMaster extends RMasterModel {
    public function editSiteSetting($SiteSettingData) {
        $UpdateData = $SiteSettingData->InternalSync(RDataModel::UPDATE, "set_value");
		$this->setUpdate("site_settings",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND site_settings.setting_id = :setting_id", $SiteSettingData->setting_id, 'int');

        return $this->exec_query();
    }

    public function getSiteSetting($id = null, $name = array(), $join = null) {
        if(!empty($id)) {
			$this->setWhere("AND site_settings.setting_id = :setting_id", $id, 'int');
		}
        if(is_array($name) && !empty($name)) {
            $this->setWhere("AND site_settings.setting_constant IN @setting_constant", $name, 'string');
        } elseif(!empty($name)) {
            $this->setWhere("AND site_settings.setting_constant = :setting_constant", $name, 'string');
        }
        
        if(!empty($join)) {
            $this->setJoin("LEFT JOIN site_settings_description ON site_settings.setting_id = site_settings_description.setting_id");
        }
        $this->setFrom("site_settings");
        return $this->exec_query();
    }
}
?>