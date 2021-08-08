<?php
require_once(DIR_WS_MODEL.'EmailConfigurationData.php');

class EmailConfigurationMaster extends RMasterModel {
    public function addEmailConfiguration($EmailConfigurationData) {
        $FinalData = $EmailConfigurationData->InternalSync(RDataModel::INSERT, "constant_name", "status");
		$this->setInsert("email_configuration",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }
    public function addEmailConfigurationDesc($EmailConfigurationData) {
        $FinalData = $EmailConfigurationData->InternalSync(RDataModel::INSERT, "email_template_id", "template_subject", "template_content", "language_id");
		$this->setInsert("email_configuration_description",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }

    public function editEmailConfiguration($EmailConfigurationData) {
        $UpdateData = $EmailConfigurationData->InternalSync(RDataModel::UPDATE, "constant_name", "status");
		$this->setUpdate("email_configuration",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND email_configuration.email_template_id = :email_template_id", $EmailConfigurationData->email_template_id, 'int');

        return $this->exec_query();
    }

    public function editEmailConfigurationDesc($EmailConfigurationData) {
        $UpdateData = $EmailConfigurationData->InternalSync(RDataModel::UPDATE, "email_template_id", "template_subject", "template_content", "language_id");
		$this->setUpdate("email_configuration_description",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND email_configuration_description.email_template_id = :email_template_id", $EmailConfigurationData->email_template_id, 'int');
		$this->setWhere("AND email_configuration_description.language_id = :language_id", $EmailConfigurationData->language_id, 'int');

        return $this->exec_query();
    }

	public function deleteEmailConfiguration($passenger_id) {
		if(isset($passenger_id) && ($passenger_id!=null)) {
   			$this->setDelete("passengers_master");
   			$this->setWhere("passenger_id = :passenger_id", $passenger_id, 'int');

   			return $this->exec_query();
  		}
	}

    public function getEmailConfiguration($id = null, $join = null) {
        if(!empty($id)) {
			$this->setWhere("AND email_configuration.email_template_id = :email_template_id", $id, 'int');
		}
        if(!empty($join)) {
            $this->setJoin("LEFT JOIN email_configuration_description ON email_configuration.email_template_id = email_configuration_description.email_template_id");
        }
        $this->setFrom("email_configuration");
        return $this->exec_query();
    }
}
?>