<?php
require_once(DIR_WS_MODEL.'SiteLanguageData.php');
require_once(DIR_WS_MODEL_CLASSES.'RMasterModel.php');

class SiteLanguageMaster extends RMasterModel {
    public function editSiteLanguage($SiteLanguageData) {
		$UpdateData = $SiteLanguageData->InternalSync(RDataModel::UPDATE, "status");
		$this->setUpdate("site_language",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND site_language.language_id = :language_id", $SiteLanguageData->language_id, 'int');

        return $this->exec_query();
    }

	public function getSiteLanguage($id = null) {
		if(!empty($id)) {
			$this->setWhere("AND language_id = :language_id", $id, 'int');
		}
		$this->setFrom("site_language");
		return $this->exec_query();
	}
}
?>