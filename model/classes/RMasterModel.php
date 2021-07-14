<?php

require_once(DIR_WS_MODEL_CLASSES."RDataConnection.php");
require_once(DIR_WS_MODEL_CLASSES."RModelCollection.php");
require_once(DIR_WS_MODEL_CLASSES."RCollection.php");

abstract class RMasterModel {
	const SELECT = 0;							// constant created for identify queryType for select query
	const DELETE = 1;							// constant created for identify queryType for delete query
	const UPDATE = 2;							// constant created for identify queryType for update query
	const INSERT = 3;							// constant created for identify queryType for inster query
	public 	$Connection;						// object of RDataConnection
	private $buildQuery;						// contains query infomration
	private $params;							// contains named parameter values of query #key is named parameter & #value is value of named parameter
	private $types;								// contains named parameter values #key is named parameter & #value is type of named parameter
	private $customQuery;						// contains custom query information
	private $customParams;						// contains named parameter values of custom query #key is named parameter & #value is value of named parameter
	private $customTypes;						// contains named parameter types of custom query #key is named parameter & #value is value of named parameter
	private $query;								// used for internal use in class
	private $addFoundRows;						// contains true or false for prepand 'SELECT SQL_CALC_FOUND_ROWS' in SQL statement by default it is false
	private $queryType;							// contains type of query from defined constants SELECT, DELETE, UPDATE, INSERT
	private static $inputIdx;					// used for create unique named parameters for IN query
	private $debug = false;						// used to on debug mode
	private $backtrace = array();				// used to store backtrace information to display on exception.
	
	public function __construct() { 
		$this->Connection = new RDataConnection();	// connection object
		$this->buildQuery = array(
			'query'  	=> array(),
			'select'  	=> array(),
			'where'   	=> array(),
			'insert'   	=> array(),
			'update'   	=> array(),
			'join'    	=> array(),
			'orderBy' 	=> array(),
			'limit'		=> array(),
			'groupBy' 	=> array(),
			'having'  	=> array(),
			'from'   	=> array(),
			'delete'   	=> array()
	    );							 
		$this->params = array();	
		$this->types = array();		
		$this->customQuery = array();	
		$this->customParams = array();	
		$this->customTypes = array();	
		$this->addFoundRows = false;	
		$this->queryType = self::SELECT;
		self::$inputIdx = 0;
		//$this->debug = (defined('DEV_MODE') && constant('DEV_MODE') === true); 
		$this->debug = is_dev_mode(); 
			
		// init connection
		//$this->Connection->Connect(CONNECTION_TYPE,DATABASE_USERNAME,DATABASE_PASSWORD,DATABASE_NAME,DATABASE_HOST,DATABASE_PORT);
		$this->Connection->Connect('mysql',DATABASE_USERNAME,DATABASE_PASSWORD,DATABASE_NAME,DATABASE_HOST,DATABASE_PORT);
	}
		
	/**
	 * Execute Query
	 * if successful then it will return Recordset for SELECT, UPDATE & DELETE query, It will return last inserted id if INSERT Query
	 * if not successful then throw exception
	 * @param string $sql(Optional) - If passed that it will use as query otherwise getFullQuery function will be used to build query.
	 * 
	 * @return boolean|RDataRecordSet
	 */
	public function exec_query($sql = null) {
		$this->setFoundRows();
		if($this->debug) {
			try {
				// Execute query
				$this->query = (isset($sql) && !empty($sql)) ? $sql : $this->getFullQuery();
				$Rs = $this->Connection->Execute($this->query, $this->params);
				$Rs->last_query = $this->getSQL($this->query); // full query with parameter
				//$this->writeQueryInLog($Rs->last_query, debug_backtrace());

				// $this->query_debugger($Rs->last_query); // Debug queries
				
				$this->resetVariables(); // reset all variables				
			} catch (Exception $e) {
				$newex = new Exception($e->getMessage());
				//echo 'ex :: '.$this->getSQL($this->getFullQuery());exit;
				$this->displayException($newex);
			}
		} 
		else {
			$this->query = (isset($sql) && !empty($sql)) ? $sql : $this->getFullQuery();
			$Rs = $this->Connection->Execute($this->query, $this->params);
			$Rs->last_query = $this->getSQL($this->query); // full query with parameter
			$this->resetVariables();			
		}
		
		$this->query = trim($this->query);
		if(stripos($this->query, 'INSERT') === 0) {
			return $this->Connection->getLastInsertedId();
		} 
		elseif(stripos($this->query, 'UPDATE') === 0) {
			return $Rs;
		} 
		else {
			if($Rs->RecordCount() == 0)
				return false;
            return $Rs;
		}
	}
	
	public function getSQL($sql) {
		if (sizeof($this->params)) {
			foreach ($this->params as $key => $param) {
				$value = $this->params[$key];
				if (!is_null($this->types[$key])) {
					$value = type_cast($value, $this->types[$key]);
				}
				$key = "/$key\b/";
				if (!is_null($value) && is_string($value)) {
					$sql = preg_replace($key, RDataConnection::$DATABASE_CONN_OBJ->quote($value), $sql);
				} elseif (!is_null($value)) {
					$sql = preg_replace($key, ($value), $sql);
				} else {
					$sql = preg_replace($key, 'NULL', $sql);
				}
			}
		}
		return $sql;
	}
	
	public function reset($var = null){ $this->resetVariables($var); }
	private function resetVariables($var = null) {
		$this->logDebugInfo(debug_backtrace());
		if(is_null($var)) {
			$this->buildQuery = array(
					'query'  	=> array(),
					'select'  	=> array(),
					'where'   	=> array(),
					'insert'   	=> array(),
					'update'   	=> array(),
					'join'    	=> array(),
					'orderBy' 	=> array(),
					'limit'		=> array(),
					'groupBy' 	=> array(),
					'having'  	=> array(),
					'from'   	=> array(),
					'delete'   	=> array()
			);
			$this->params = array();
			$this->types = array();
			$this->customQuery = array();
			$this->customParams = array();
			$this->customTypes = array();
			$this->addFoundRows = false;
			$this->queryType = self::SELECT;
			//self::$inputIdx = 0;
		}
		else {
			$this->buildQuery[$var] = array();
		}
	}
	
	private function checkNull() {
		for ($i = 0; $i < func_num_args(); $i++) {
			if(is_null(func_get_arg($i))) {
				$t = end(debug_backtrace());
				$this->displayException("Error: (Parameter should not be null) - " . $t["file"] . "(" . $t["line"] . "): " . $t['function']);
			}
		}
	}
	
	public function setInsert($table_name, $sql, $value){
		$this->logDebugInfo(debug_backtrace());
		$this->checkNull($table_name, $sql, $value);
		if(!is_array($value))
			$this->displayException('Parameter $value should be array');
		$this->queryType = self::INSERT;
		$this->setFrom($table_name);
		preg_match_all("/:\w+/i", $sql, $matches);
		if(count($matches[0]) == count($value)) {
			$this->buildQuery['insert'][] = $sql;
			$this->params = array_merge($this->params, $value);
		}
		else {
			$this->displayException("count of named parameter & value array is mismatch");
		}
		return $this;
	}
	
	private function getInsert(){
		return $this->buildQuery['insert'][0];
	}
	
	public function setUpdate($table_name, $sql, $value){
		$this->logDebugInfo(debug_backtrace());
		$this->checkNull($table_name, $sql, $value);
		if(!is_array($value))
			$this->displayException('Parameter $value should be array');
		$this->queryType = self::UPDATE;
		$this->setFrom($table_name);
		preg_match_all("/:\w+/i", $sql, $matches);
		if(count($matches[0]) == count($value)) {
			$this->buildQuery['update'][] = $sql;
			$this->params = array_merge($this->params, $value);
		}
		else {
			$this->displayException("count of named parameter & array is mismatch");
		}
		return $this;
	}
	
	private function getUpdate(){
		return $this->buildQuery['update'][0];
	}
	
	private function addQuery($sql, $container) {
		$this->checkNull($sql, $container);
		if($container == 'customQuery') {
			if(count($this->buildQuery) != count($this->buildQuery, COUNT_RECURSIVE))
				$this->displayException('Do not mix customQuery & modelQuery');
			$this->customQuery[] = $sql;
		}
		else {
			if(!empty($this->customQuery))
				$this->displayException('Do not mix customQuery & modelQuery');
			$this->buildQuery[$container][] = $sql;
		}
	}
	
	private function addParams($value, $type) {
		$this->checkNull($value, $type);
		if((is_array($value) && is_assoc($value)) && (is_array($type) && is_assoc($type))) {
			foreach ($value as $k => $v) {
				$this->checkNull($v, $type[$k]);
				if(array_key_exists($k, $this->params) || array_key_exists($k, $this->types))
					$this->displayException("$k named parameter is already exists please rename to something else");
				if(preg_match("/@\w+/i", $k)) {
					print_r($k);
					$this->displayException("It should not come here");
					$this->types[$k] = $type[$k]; $this->params[$k] = $v;
				}
				else {
					$this->types[$k] = $type[$k];
					$this->params[$k] = isset($type[$k]) ? type_cast($v, $type[$k]) : $v;
				}
			}
		}
		else {
			$this->displayException('Function[addParams] - $value and $type Parameter is not array');
		}
	}

	public function setCustomParams($values, $types) {
		$this->logDebugInfo(debug_backtrace());
		$this->checkNull($values, $types);
		if((is_array($values) && is_assoc($values)) && (is_array($types) && is_assoc($types))) {
			$this->customParams = array_merge($this->customParams, $values);
			$this->customTypes = array_merge($this->customTypes, $types);
		}
	}
	
	public function getCustomParams() {
		$this->logDebugInfo(debug_backtrace());
		return $this->customParams;
	}
	public function getCustomTypes() {
		$this->logDebugInfo(debug_backtrace());
		return $this->customTypes;
	}
	public function getParamsCount() {
		$this->logDebugInfo(debug_backtrace());
		return count($this->params);
	}
		
	private function set($sql, $value, $type, $container, $check_blank = false, $throw_exception = false){
		$this->checkNull($sql);
		if(is_array($value) && !is_assoc($value) && is_array($type) && !is_assoc($type)){
			if(preg_match("/(:\w+|@\w+)/i", $sql, $matches)){
				preg_match_all("/(:\w+|@\w+)/i", $sql, $matches);
				$value = rxArrayToAssoc($value, $matches[0]);
				$type = rxArrayToAssoc($type, $matches[0]);
			}
		}
		if(preg_match("/:\w+/i", $sql)) {
			if(!$check_blank)
				$this->checkNull($value, $type, $container);
			preg_match_all("/:\w+/i", $sql, $matches);
			$matches = $matches[0];
			foreach ($matches as $match) {
				if(is_array($value) && is_assoc($value)) {
					if($check_blank && (is_null($value[$match]) || is_null($type[$match]) || is_blank(type_cast($value[$match], $type[$match]), $type[$match]))) return $this; 
					$this->addParams(array($match => $value[$match]), array($match => $type[$match]));
				}
				else {
					if($check_blank && (is_null($value) || is_null($type) || is_blank(type_cast($value, $type), $type))) return $this;
					$this->addParams(array($match => $value), array($match => $type));
				}
			}
		}
		if (preg_match("/@\w+/i", $sql)) {
			if(!$check_blank)
				$this->checkNull($value, $type, $container);
			preg_match_all("/@\w+/i", $sql, $matches);
			$matches = $matches[0];
			$match_array = $replace_array = array();
			foreach ($matches as $match) {
				if(is_array($value) && is_assoc($value)) {
					if(!$check_blank)
						$this->checkNull($value[$match], $type[$match]);
					$in_value = $value[$match]; unset($this->params[$match]);
					$in_type = $type[$match]; unset($this->types[$match]);
					if(isset($in_value) && is_array($in_value)) $in_value = array_unique($in_value);
				}
				else {
					$in_value = $value;
					$in_type = $type;
				}
				if($check_blank && is_blank($in_value, 'array')) return $this;
				$named_param = array();
				foreach ($in_value as $v){
					$named_param[] = ":input_" . RMasterModel::$inputIdx;
					if($check_blank && (is_null($v) || is_null($type) || is_blank(type_cast($v, $type), $type))) return $this;
					$this->addParams(array(":input_" . RMasterModel::$inputIdx => $v), array(":input_" . RMasterModel::$inputIdx => $type));
					RMasterModel::$inputIdx++;
				}
				$match_array[] = "/$match/";
				$replace_array[] = "(" . implode(", ", $named_param) . ")";
			}
			$sql = preg_replace($match_array, $replace_array, $sql);
		}
		$this->addQuery($sql, $container);
		return $this;
	}

	private function get($container) {
		$return_value = "";
		if($container == 'customQuery') {
			return implode(" ", $this->customQuery) . " ";
		}
		if(empty($this->buildQuery[$container])) {
			return $return_value;
		}
		switch ($container) {
			case 'where':
				$return_value .= "WHERE ";
				if (preg_match("/^(AND |OR )/i", trim($this->buildQuery['where'][0])) > 0) {
					$this->buildQuery['where'][0] = preg_replace("/^(AND |OR )/i", '', trim($this->buildQuery['where'][0]));
				}
				$return_value .= implode(" ", $this->buildQuery['where']);
				break;
			case 'having':
				$return_value .= "HAVING ";
				if (preg_match("/^(AND |OR )/i", trim($this->buildQuery['having'][0])) > 0) {
					$this->buildQuery['having'][0] = preg_replace("/^(AND |OR )/i", '', trim($this->buildQuery['having'][0]));
				}
				$return_value .= implode(" ", $this->buildQuery['having']);
				break;
			case 'select':
				if($this->addFoundRows)
					$return_value .= "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $this->buildQuery['select']);
				else  
					$return_value .= "SELECT " . implode(", ", $this->buildQuery['select']);
				break;
			case 'join':
				$return_value .= implode(" ", $this->buildQuery['join']);
				break;
			case 'orderBy':
				$return_value .= "ORDER BY " . implode(", ", $this->buildQuery['orderBy']);
				break;
			case 'groupBy':
				$return_value .= "GROUP BY " . implode(", ", $this->buildQuery['groupBy']);
				break;
			case 'limit':
				$return_value = end($this->buildQuery['limit']);
				break;
			case 'from':
				$return_value = "FROM " . end($this->buildQuery['from']);
				break;
			default:
				$return_value .= implode(" ", $this->buildQuery['select']);
				break;
			break;
		}
		return $return_value . " ";
	}
	
	/**
	 * set parameter
	 * used to set parameter for build query using Rx framework using PDO
	 * @param string $sql - sql statement or sql condition using unique named parameter e.g. 'product_id = :product_id' or '(product_id = :product_id_one or product_id = :product_id_two)' or 'product_id IN @product_ids' where :product_id, :product_id_one, :product_id_two is named parameter of simple query and @product_ids is named parameter for in query, which is going to replace/bind with passed value.
	 * @param mixed $value - value for passed named parameter, it may be int, string or array. for qurey which contains only one named parameter like 'product_id = :product_id' then it should be int or string and for multiple named parameters and in query it should be array of values.
	 * @param string $type - type of passed value, it may be 'int', 'string', or 'boolean' (by defalut as it is). for qurey which contains only one named parameter like 'product_id = :product_id' then it should be one of  'int' or 'string' or 'boolean' and for multiple named parameters and in query it should be array of types.
	 *
	 *
	 * @return void
	 *
	 * @example
	 * for single parameter $objProduct->setParam(" products.product_id = :product_id ", $product_id, 'int');
	 * for multipe parameters $objProduct->setParam(" products.product_id = :product_id AND products.category_id = :category_id", array($product_id, $category_id), array('int', 'int'));
	 * for in query $objProduct->setParam(" products.category_id IN @category_ids ", array($category_id1, $category_id2, ...));
	 * @todo: need to check $type is one of int, string & boolean
	 *
	 **/
	public function setWhere($sql, $value, $type, $check_blank = false, $throw_exception = false) {
		$this->logDebugInfo(debug_backtrace());
		return $this->set($sql, $value, $type, 'where', $check_blank, $throw_exception);
	}
	private function getWhere() {
		return $this->get('where');
	}
	
	public function setHaving($sql, $value, $type, $check_blank = false, $throw_exception = false) {
		$this->logDebugInfo(debug_backtrace());
		return $this->set($sql, $value, $type, 'having', $check_blank, $throw_exception);
	}
	private function getHaving() {
		return $this->get('having');
	}
	
	public function setDelete($table_name) {
		$this->logDebugInfo(debug_backtrace());
		if(isset($table_name)) {
			$this->queryType = self::DELETE;
			$this->setFrom($table_name);
		}
		return $this;
	}
	private function getDelete() {
		return $this->get('delete');
	}
	
	public function setCustomQuery($sql, $value, $type, $check_blank = false) {
		$this->logDebugInfo(debug_backtrace());
		return $this->set($sql, $value, $type, 'customQuery', $check_blank);
	}
	private function getCustomQuery() {
		return $this->get('customQuery');
	}
	
	public function setSelect($sql, $value = null, $type = null, $check_blank = false) {
		$this->logDebugInfo(debug_backtrace());
		$this->checkNull($sql);
		if(is_array($sql)) {
			foreach ($sql as $s)  {
				if(preg_match("/:\w+/i", $s) || preg_match("/@\w+/i", $s)) {
					$this->set($s, $value, $type, 'select', $check_blank);
				}
				else {
					$this->addQuery($s, 'select');
				}
			}
		}
		else if(is_string($sql)) {
			if(preg_match("/:\w+/i", $sql) || preg_match("/@\w+/i", $sql)) {
				$this->set($sql, $value, $type, 'select', $check_blank);
			}
			else {
				$this->addQuery($sql, 'select');
			}
		}
		return $this;
	}
	private function getSelect() {
		if(empty($this->buildQuery['select'])) {
			$this->setSelect("*");
		}
		return $this->get('select');
	}
	
	public function setFrom($table_name) {
		$this->logDebugInfo(debug_backtrace());
		if(isset($table_name)) {
			$this->addQuery($table_name, 'from');
		}
		return $this;
	}
	private function getFrom() {
		return $this->get('from');
	}
	
	public function setJoin($sql, $value = null, $type = null, $check_blank = false) {
		$this->logDebugInfo(debug_backtrace());
		if(preg_match("/:\w+/i", $sql) || preg_match("/@\w+/i", $sql)) {
			$this->set($sql, $value, $type, 'join', $check_blank);
		}
		else {
			$this->addQuery($sql, 'join');
		}
		return $this;
	}
	private function getJoin() {
		return $this->get('join');
	}
	
	public function setOrderBy($sortArr) {
		$this->logDebugInfo(debug_backtrace());
		if(!empty($sortArr)) {
			if(is_array($sortArr)) {
				foreach ($sortArr as $sort)  {
					$this->addQuery($sort, 'orderBy');
				}
			}
			else if(is_string($sortArr)) {
				$this->addQuery($sortArr, 'orderBy');
			}
		}
		return $this;
	}
	private function getOrderBy() {
		return $this->get('orderBy');
	}
	
	public function setLimit($start=null, $total=null) {
		$this->logDebugInfo(debug_backtrace());
		if(!is_null($total)) {
			if(is_null($start)) {
				$start = 0;
			}
			$this->addQuery(" LIMIT ".type_cast($total, "int")." OFFSET ". type_cast($start, "int"), 'limit');
		}
		return $this;
	}
	private function getLimit() {
		return $this->get('limit');
	}
	
	public function setGroupBy($fieldArr) {
		$this->logDebugInfo(debug_backtrace());
		if(!empty($fieldArr)) {
			if(is_array($fieldArr)) {
				foreach ($fieldArr as $groupBy)  {
					$this->addQuery($groupBy, 'groupBy');
				}
			}
			else if(is_string($fieldArr)) {
				$this->addQuery($fieldArr, 'groupBy');
			}
		}
		return $this;
	}
	private function getGroupBy() {
		return $this->get('groupBy');
	}
	
	public function getFullQuery() {
		if(!empty($this->customQuery)) {
			return $this->get('customQuery');
		}
		else {
			switch ($this->queryType) {
				case self::INSERT:
					return "INSERT INTO " . end($this->buildQuery['from']) . " " . $this->getInsert();
				break;
				case self::UPDATE:
					return "UPDATE " . end($this->buildQuery['from']) . " SET " . $this->getUpdate() . " " . $this->getWhere();
				break;
				case self::DELETE:
					return "DELETE FROM " . end($this->buildQuery['from']) . " " . $this->getWhere();
				break;
				default:
					return $this->getSelect() . $this->getFrom() . $this->getJoin() . $this->getWhere() . $this->getGroupBy() . $this->getHaving() . $this->getOrderBy() . $this->getLimit();
				break;
			}
		}
		
	}
	
	public function setFoundRows(){
		$this->logDebugInfo(debug_backtrace());
		$this->addFoundRows = true;
	}
	
	public function getCloneObject($container='where') {
		$this->logDebugInfo(debug_backtrace());
		switch ($container) {
			case 'where':
			case 'having':
			case 'join':
				$query = implode(" ", $this->buildQuery[$container]);
				break;
			case 'select':
			case 'orderBy':
			case 'groupBy':
				$query = implode(", ", $this->buildQuery[$container]);
				break;
			case 'limit':
			case 'from':
				$query = end($this->buildQuery[$container]);
				break;
			default:
				$query = implode(" ", $this->buildQuery[$container]);
				break;
		}
		preg_match_all("/(:\w+|@\w+)/i", $query, $matches);
		$params = array_intersect_key($this->params, array_flip($matches[0]));
		$types = array_intersect_key($this->types, array_flip($matches[0]));
		return array($query, $params, $types);
	}
	
	private function logDebugInfo($data) {
		$data = $data[0];
		if($this->debug) {
			$str = "";
			if(isset($data['file'])) {
				$str .= $data['file'] . '(' . $data['line'] . '):';
			}
			if(isset($data['class'])) {
				$str.= " " . $data['class'] . $data['type'];
			}
			$str.= $data['function'];
			if(isset($data['args']) && sizeof($data['args']) > 0) {
				$tmp = array();
				foreach ($data['args'] as $val) {
					if(is_bool($val))
						$tmp[] = ($val) ? "true" : "false";
					elseif(is_array($val) && !empty($val))
						$tmp[]  = "Array";
					elseif(is_int($val) || is_bool($val))
						$tmp[] = $val;
					elseif(is_string($val))
						$tmp[] = "'" . $val . "'";
					else 	
						$tmp[] = $val;
				}
				$str.= '(' . implode(", ", $tmp) . ')';
			}
			else {
				$str.= '()';
			}
			$this->backtrace[$str] = $this->displayCode($data['file'], $data['line']);
		}
	}
	
	private function displayException($msg = null) {
		if($this->debug) {
			$str = "";
			$index = 0;
			$backtrace = array_reverse($this->backtrace);
			foreach ($backtrace as $key => $value) {
				$str .= "<b>#". $index++ . " $key</b>";
				$str .= $value;
			}
			
			echo "<div><h4>Last Query : </h4>" . $this->getSQL($this->query) . "</div>";
			
			echo "<div><h4>Prepared Statement : </h4>".preg_replace_callback("/(:\w+|@\w+)/i",
			function($matches){
				return "<span style='color:red'>".$matches[0]."</span>";
            },$this->query)."</div>";
			echo "<pre><b>Params: </b>"; print_r($this->params); echo "</pre>";
			
			if(isset($msg)) {
				throw new Exception($msg);
			}
		}
	}
	
	private function displayCode($errfile, $errline) {
		// print code lines
		$msg = "";
		$file_lines = @file($errfile);
		if (is_array($file_lines)) {
			if (isset($file_lines[$errline])) {
				$msg.= "<pre style='background-color: #FFFFCC;'>";
				for ($i=$errline-1; $i<=$errline+1; $i++ ) {
					if (isset($file_lines[$i-1])) 
						$line = htmlspecialchars($file_lines[$i-1]);
					else
						continue;
					$msg.= $i.': ';
					if( $i==$errline )
						$msg.= "<span style='color: #CC0000;font-weight: bold;'>".$line.'</span>';
					else
						$msg.= $line;
				}
				$msg.= '</pre>';
			}
		}
		return $msg;
	}
	
	// Log query and quory execution time in debugger 
	private function query_debugger($Query) {
		// Debug queries
		if($this->debug) {
			static $query_cnt = 0;
			static $query_dup = 0;
			global $queries_debug, $queries_debug_arr;
			
			$queryTime = "<span class='badge pull-right'>".$this->Connection->queryTime." sec</span>";
						
			$q = $Query;
			$paterns = '/(SELECT|FROM|ORDER BY|ASC|ASC\,|DESC|WHERE|AND|\sOR|JOIN|LEFT JOIN|IN| ON|GROUP BY|GROUP_CONCAT\(|CONCAT\(|CONVERT\(|COUNT\(|AS|LIMIT|OFFSET)\s/i';
			$q = preg_replace($paterns, '<span>'."$1 ".'</span>', $q);
			$q = str_replace("\r", "", str_replace("\n", "<br/>", $q));
			$q = str_replace("<span>",  "<span style='color:#990099; font-weight:bold;'>", $q);
			
			$duplicate = '';
			if(in_array($Query, $queries_debug_arr)) {
				$query_dup++;
				$dup_key = array_search($Query, $queries_debug_arr)+1;
				//$q = "Duplicate ($dup_key) : $q";
				$duplicate = " <code title='Duplicate'><i class='fa fa-copy'></i> ".$dup_key."</code>";
			} else {
				$queries_debug_arr[] = $Query;
			}
			
			$q = "<div class='trace' style='cursor:pointer;'><b>".++$query_cnt . $duplicate . "</b>. ". $q . $queryTime . "</div>";
			$q .= "<div style='display:none;margin:15px 15px 0 15px;border-left: 2px solid #000000;'>";
			$trace = debug_backtrace(false);
			for($i=count($trace)-1;$i>=0;$i--) {
				$q .= str_repeat("&nbsp;",(count($trace)-$i)*3);
				$q .= "↳";
				$q .= preg_replace("/^\S+public_html\//","",$trace[$i]['file']);
				$q .= " : ".$trace[$i]['line']." -> ";
				$q .= isset($trace[$i]['class'])?$trace[$i]['class']."::":"";
				$q .= $trace[$i]['function'];
				if($trace[$i]['function']=='render' && $trace[$i]['class']=='Twig_Environment') {
					if($trace[$i]['args']['0']=='mainpage.tpl' && !isset($queries_debug['-1'])) $queries_debug['-1'] = '*******************************Render TPL Start*********************************<br/>';
					$q .= ' (<b>'.$trace[$i]['args']['0'].'</b>)';
				}
				$q .= "<br/>";
			}
			$q .= "</div>";
			$GLOBALS['query_cnt'] = $query_cnt;
			$GLOBALS['query_dup'] = $query_dup;
			$queries_debug[] = $q;
		}
	}
	
	function writeQueryInLog($query, $backtrace) {
		$myFile = array_pop($backtrace);
		$myFile = $myFile['file'];
		$myFile = preg_replace("/public_html/", "public_html/images/query_log", $myFile);
		if (!file_exists(dirname($myFile))) {
			mkdir(dirname($myFile));
		}
		$time = '['.date('Y-m-d H:i:s').'] ';
		error_log("\n" . preg_replace('/\s\s+/', ' ', $time.$query), 3, $myFile . ".txt");
	}
}
?>