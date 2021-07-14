<?php
include_once DIR_WS_MODEL_CLASSES.'RCollection.php';
class RModelCollection extends RCollection
{
	/**
	 * Constructor.
	 * @param mixed $Data 		initial collection data
	 * @param bool  $ReadOnly  	the collection is read-only
	 */
	public function __construct($Data = null, $ReadOnly = false)
	{
		parent::__construct($Data, $ReadOnly);
	}	
	
	
	// modify column
}
?>