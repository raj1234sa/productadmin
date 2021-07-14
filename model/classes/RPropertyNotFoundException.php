<?php
class RPropertyNotFoundException extends Exception
{
	function __construct($Class, $Property)
	{
		if (is_object($Class)) {
			$Class = get_class($Class);
		}
		parent::__construct("Property '$Property' not found in '$Class'");
	}
}
?>