<?php
class RCollection implements Iterator, ArrayAccess, Countable

{
	/**
	 * internal data storage
	 * @var array
	 */
	protected $Data		= array();
	/**
	 * whether this collection allows addition/removal of elements
	 * @var bool
	 */
	protected $ReadOnly	= false;

	/**
	 * Constructor.
	 * @internal 
	 * @param mixed $Data 		initial collection data
	 * @param bool  $ReadOnly  	the collection is read-only
	 */
	public function __construct($Data = null, $ReadOnly = false)
	{
		if (is_array($Data) || $Data instanceof Traversable) {
			foreach ($Data as $Value) {
				$this->Add($Value);
			}
		}
		$this->ReadOnly = $ReadOnly;
	}


	
	/**
	 * The number of elements in the collection
	 * @return int 
	 */
	public function Length()
	{ 
		return count($this->Data);
	}

	/**
	 * Whether this collection allows addition/removal of elements.
	 * @return bool
	 */
	public function IsReadOnly()
	{
		return $this->ReadOnly;
	}
	/**
	 * Returns the element at the specified index.
	 * @param int $Index then index of the item in the collection
	 * @return mixed
	 */
	public function Get($Index)
	{
		return $this->offsetGet($Index);
	}

	/**
	 * Appends an item at the end of the collection.
	 * @param mixed $Item new item
	 */
	public function Add($Item)
	{
		$this->AddAt(count($this->Data), $Item);
	}

	/**
	 * Inserts an item at the specified position.
	 * Original item at the position and the next items 
	 * will be moved one step towards the end.
	 * @param int $Index the speicified position.
	 * @param mixed $Item new item
	 */
	public function AddAt($Index, $Item)
	{
		if ($this->IsReadOnly()) {
			throw new RReadOnlyCollectionException();
		}
		if ($Index === count($this->Data)) {
			if ($this->OnAddItem($Item, $Index)) {
				$this->Data[] = $Item;
			}
		}
		else if (is_integer($Index) && $Index >= 0 && $Index < count($this->Data)) {
			if ($this->OnAddItem($Item, $Index)) {
				array_splice($this->Data, $Index, 0, array($Item));				
			}
		}
		else {
			throw new RInvalidCollectionOffsetException($Index);
		}
	}
	
	/**
	 * Returns the index by comparing $data==$method
	 *
	 * @access	public
	 * @param	mix		$data		data
	 *			string	$method		object's method name
	 * @return	int		-1, if failed
	 */
	function searchIndex( $data, $method )
	{
		if( empty($method) )
			return -1;

		//debug_print( 'size', $this->size() );
		for( $i=0; $i<$this->count(); $i++ ) {
			$obj =& $this->Get($i);
			//debug_print( 'obj', $obj );
			if( method_exists($obj, $method) ) {
				if( $obj->$method()==$data )
					return $i;
			}
		}
		return -1;
	}
	
	/**
	 * Removes all items in the collection.
	 */
	public function Clear()
	{
		if ($this->IsReadOnly()) {
			throw new RReadOnlyCollectionException();
		}
		foreach ($this->Data as $Index => $Item) {
			$this->OnRemoveItem($Item, $Index);
		}
		$this->Data = array();
	}

	/**
	 * Removes an item from the collection.
	 * The collection will first search for the item.
	 * The first item found will be removed from the collection.
	 * @param mixed $Item the item to be removed.
	 */
	public function Remove($Item)
	{
		if (($Index = $this->IndexOf($Item)) >= 0) {
			$this->RemoveAt($Index);
		}
	}

	/**
	 * Removes an item at the specified position.
	 * @param int $Index the index of the item to be removed.
	 */
	public function RemoveAt($Index)
	{
		//debug_print(get_class($this), __CLASS__, __FUNCTION__, $Index);
		if ($this->IsReadOnly()) {
			throw new RReadOnlyCollectionException();
		}
		if (is_integer($Index) && $Index >= 0 && $Index < count($this->Data))	{
			$this->OnRemoveItem($this->Data[$Index], $Index);
			array_splice($this->Data, $Index, 1);
		}
		else {
			throw new RInvalidCollectionOffsetException($Index);
		}
	}

	/**
	 * whether the collection contains the item
	 * @param mixed $Item the item
	 * @return bool
	 */
	public function Contains($Item)
	{
		return $this->IndexOf($Item) >= 0;
	}

	/**
	 * @param mixed the item
	 * @return int  $Item the index of the item in the collection, -1 if not found.
	 */
	public function IndexOf($Item)
	{
		$Index = array_search($Item, $this->Data, true);
		if ($Index === false) {
			$Index = -1;
		}
		return $Index;
	}
	
	/**
	 * This method will be invoked when an item is being added to the collection.
	 * @param mixed $Item the item to be added.
	 * @param int $Index then index of the item in the collection
	 * @return boolean whether the item should be added.
	 */
	protected function OnAddItem($Item, $Index)
	{
		return true;
	}

	/**
	 * This method will be invoked when an item is being removed from the collection.
	 * @param mixed $Item the item to be removed.
	 * @param int $Index
	 */
	protected function OnRemoveItem($Item, $Index)
	{
	}

	/**
	 * array representation of the data in the collection
	 * @return array
	 */
	public function ToArray()
	{
		return $this->Data;
	}


	/**
	 * Returns count of element
	 * Countable impements
	 * @internal This method should only be used by framework
	 * @return int
	 */
	public function count()
	{
		return count($this->Data);
	}
	
	
	//--------------------------------------------------------------
	//	Iterator implements
	//	
	//	current () 
	//  key () 
	//  next () 
	//  rewind () 
	//  valid () 
	//---------------------------------------------------------------
	
	/**
	 * Returns the current array element.
	 * @internal This method should only be used by framework
	 * @return mixed the current array element
	 */
	public function current()
	{
		return current($this->Data);
	}

	/**
	 * Returns the key of the current array element.
	 * @internal This method should only be used by framework
	 * @return int the key of the current array element
	 */
	public function key()
	{
		return key($this->Data);
	}

	/**
	 * Moves the internal pointer to the next array element.
	 * @internal This method should only be used by framework
	 */
	public function next()
	{
		return next($this->Data);
	}

	/**
	 * Rewinds internal array pointer.
	 * @internal This method should only be used by framework
	 */
	public function rewind()
	{
		reset($this->Data);
	}

	/**
	 * Returns whether there is an element at current position.
	 * @internal This method should only be used by framework
	 * @return bool
	 */
	public function valid()
	{
		return $this->current() !== false;
	}


	
	//--------------------------------------------------------------
	//	ArrayAccess implements
	//
	//  offsetExists ($offset) 
  	//	offsetGet ($offset) 
  	//	offsetSet ($offset, $value) 
  	//	offsetUnset ($offset) 
	//--------------------------------------------------------------
	
	/**
	 * Returns whether there is an element at the specified offset.
	 * @internal This method should only be used by framework
	 * @param int the offset to check on
	 * @return bool
	 */
	public function offsetExists($Offset)
	{
		return isset($this->Data[$Offset]);
	}

	/**
	 * Returns the element at the specified offset.
	 * @internal This method should only be used by framework
	 * @param int the offset to retrieve element.
	 * @return mixed
	 */
	public function offsetGet($Offset)
	{
		if (isset($this->Data[$Offset])) {
			return $this->Data[$Offset];
		}
		else {
			throw new RInvalidCollectionOffsetException($Offset);
		}
	}

	/**
	 * Required by interface.
	 * @internal This method should only be used by framework
	 */
	public function offsetSet($Offset, $Item)
	{
		if (is_null($Offset)) {
			$this->Add($Item);
		}
		else if (isset($this->Data[$Offset])) {
			if ($Item !== $this->Data[$Offset]) {
				$this->RemoveAt($Offset);
				$this->AddAt($Offset, $Item);
			}
		}
		else {
			throw new RInvalidCollectionOffsetException($Offset);
		}
	}

	/**
	 * Required by interface.
	 * @internal This method should only be used by framework
	 */
	public function offsetUnset($Offset)
	{
		if (isset($this->Data[$Offset])) {
			$this->RemoveAt($Offset);
		}
		else {
			throw new RInvalidCollectionOffsetException($Offset);
		}
	}
}
?>