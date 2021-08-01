<?php
require_once(DIR_WS_MODEL.'PassengersData.php');

class PassengersMaster extends RMasterModel {
    public function addPassenger($PassengersData) {
        $FinalData = $PassengersData->InternalSync(RDataModel::INSERT, "firstname", "lastname", "email", "phone", "password", "status");
		$this->setInsert("passengers_master",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }

    public function editPassenger($PassengersData) {
        $UpdateData = $PassengersData->InternalSync(RDataModel::UPDATE, "firstname", "lastname", "email", "phone", "password", "status");
		$this->setUpdate("passengers_master",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND passengers_master.passenger_id = :passenger_id", $PassengersData->passenger_id, 'int');

        return $this->exec_query();
    }

	public function deletePassenger($passenger_id) {
		if(isset($passenger_id) && ($passenger_id!=null)) {
   			$this->setDelete("passengers_master");
   			$this->setWhere("passenger_id = :passenger_id", $passenger_id, 'int');

   			return $this->exec_query();
  		}
	}

    public function getPassenger($id = null) {
        if(!empty($id)) {
			$this->setWhere("AND passenger_id = :passenger_id", $id, 'int');
		}
        $this->setFrom("passengers_master");
        return $this->exec_query();
    }
}
?>