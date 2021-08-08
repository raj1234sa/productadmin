<?php
require_once(DIR_WS_MODEL.'BusStopsData.php');

class BusStopsMaster extends RMasterModel {
    public function addBusStop($BusStopData) {
        $FinalData = $BusStopData->InternalSync(RDataModel::INSERT, "city_id", "stop_title", "stop_internal_name", "stop_image", "status");
		$this->setInsert("bus_stops_master",$FinalData['query'], $FinalData['params']);

        return $this->exec_query();
    }

    public function editBusStop($BusStopData) {
        $UpdateData = $BusStopData->InternalSync(RDataModel::UPDATE, "city_id", "stop_title", "stop_internal_name", "stop_image", "status");
		$this->setUpdate("bus_stops_master",$UpdateData['query'], $UpdateData['params']);
		$this->setWhere("AND bus_stops_master.stop_id = :stop_id", $BusStopData->stop_id, 'int');

        return $this->exec_query();
    }

    public function getBusStops($id = null) {
        if(!empty($id)) {
			$this->setWhere("AND stop_id = :stop_id", $id, 'int');
		}
        $this->setFrom("bus_stops_master");
        return $this->exec_query();
    }
}
?>