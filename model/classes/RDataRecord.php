<?php
class RDataRecord implements Iterator, ArrayAccess
{
	/**
	 * @var array
	 * not write private because it access by InternalSync() method of RModuleData()
	 */
	protected $_Data 			= array();  
	
	/**
	 * @var array
	 */	
	protected $_IteratorKeys	= array();
	
	
	/**
	 * Constructor 
	 * @internal 
	 * @param array $InputData
	 */
	public function __construct($InputData = null)
	{
		if (is_array($InputData)) {
			$this->_Data = $InputData;
		}
		else if($InputData instanceof RDataRecord) {
			$this->_Data = $InputData->_Data;  
		}
		
		$this->_IteratorKeys	= array_keys($this->_Data);
	}
	
	/**
	 * Get raw value without DB escape and property mapping of given field
	 *
	 * @param string $Name
	 * @param bool $IsEscape
	 * @return mixed
	 */
	final protected function InternalGetValue($Name, $IsEscape = true)
	{	
		if (array_key_exists($Name, $this->_Data)) {
			return $this->_Data[$Name];
		}
		if (is_int($Name) && array_key_exists($this->_IteratorKeys[$Name], $this->_Data)) { 
			return $this->_Data[$this->_IteratorKeys[$Name]];
		}
		return null;
	}
	
	/**
	 * Get raw value without DB escape and property mapping of given field
	 *
	 * @param string $Name
	 * @param string $Value
	 * @param bool $IsEscape
	 * @return mixed
	 */
	final protected function InternalSetValue($Name, $Value, $IsEscape = true)
	{
		$this->_Data[$Name] = $Value;
	}

	/**
	 * Has record contain given filed value
	 *
	 * @param string $Name
	 * @return bool
	 */
	final protected function InternalHasValue($Name)
	{
		if (array_key_exists($Name, $this->_Data)) {
			return true;
		}
		if (is_int($Name) && array_key_exists($this->_IteratorKeys[$Name], $this->_Data)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Has record contain given filed value
	 *
	 * @param string $Name
	 * @return bool
	 */
	public function HasValue($Name)
	{
		return $this->InternalHasValue($Name);		
	}
	
	
	/**
	 * Get value of given field
	 *
	 * @param string $Name
	 * @param bool $IsEscape
	 * @return mixed
	 */
	public function GetValue($Name, $IsEscape = true)
	{	
		return $this->InternalGetValue($Name, $IsEscape);
	}
	
	/**
	 * Set value of given field
	 *
	 * @param string $Name
	 * @param string $Value
	 * @param bool $IsEscape
	 * @return mixed
	 */
	public function SetValue($Name, $Value, $IsEscape = true)
	{
		$this->InternalSetValue($Name, $Value, $IsEscape);
	}

	//--------------------------------------------------------------
	//	property overloading
	//---------------------------------------------------------------	
	/**
	 * property get overloading
	 * @internal 
	 * @param string $Name
	 * @return mixed
	 */
	public function __get($Name)
	{
		//return $this->GetValue($Name);
		$Data = stripslashes($this->GetValue($Name));
		return $Data;
	}
	
	/**
	 * property set overloading
	 * @internal 
	 * @param string $Name
	 * @param mixed $Value
	 */
	public function __set($Name, $Value)
	{
		$this->SetValue($Name, $Value);
	}	
	
	/**
	 * property isset overloading
	 * @internal 
	 * @param string $Name
	 */
	public function __isset($Name)
	{
		return $this->HasValue($Name);
	}	
	
	//--------------------------------------------------------------
	//	Iterator implements
	//---------------------------------------------------------------
	/**
	 * @internal 
	 * @return mixed
	 */
	public function current()
	{
		return $this->GetValue($this->key());
	}
	/**
	 * @internal 
	 * @return string
	 */
	public function key()
	{
		return current($this->_IteratorKeys);
	}
	/**
	 * @internal 
	 */
	public function next()
	{
		next($this->_IteratorKeys);
	}
	/**
	 * Rewinds internal array pointer.
	 * This method should only be used by framework
	 * @internal 
	 */
	public function rewind()
	{
		reset($this->_IteratorKeys);
	}
	/**
	 * @internal 
	 * @return bool
	 */
	public function valid()
	{
		return current($this->_IteratorKeys) !== false;
	}


	
	//--------------------------------------------------------------
	//	ArrayAccess implements
	//--------------------------------------------------------------
	/**
	 * @internal 
	 * @param string
	 * @return bool
	 */
	public function offsetExists($Offset)
	{
		return $this->HasValue($Offset);
	}
	/**
	 * @internal 
	 * @param string
	 * @return mixed
	 */
	public function offsetGet($Offset)
	{
		return $this->GetValue($Offset);
	}
	/**
	 * @internal 
	 * @param string
	 * @param mixed
	 */
	public function offsetSet($Offset, $Item)
	{
		$this->SetValue($Offset, $Item);
	}
	/**
	 * @internal 
	 * @param string
	 */
	public function offsetUnset($Offset)
	{
		//unset($this->_Data[$Offset]);
	}	
}


?>