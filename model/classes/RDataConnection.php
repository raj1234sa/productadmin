<?php
define('DATABASE_USERNAME', 'root');
define('DATABASE_PASSWORD', '');
define('DATABASE_NAME', 'productadmin');
define('DATABASE_HOST', 'localhost');
define('DATABASE_PORT', '3306');
//require_once('DB.php'); OLD
require_once('PDO.php');
require_once('RDataRecordSet.php');
require_once('RDataRecord.php');

class RDataConnection
{
	static $cnt = 1;
	/**
	 * @var DB_common
	 */
	private $Connection;
	public static $DATABASE_CONN_OBJ;
	public $queryTime;
	private $debug = false;
	
	/**
	 * Destructor
	 */
	public function __destruct(){
		//dprint_callstack();
		// close connection
		//if (DB::isConnection($this->Connection)) {
		//	$this->Connection->disconnect(); //Commented by meghna on 6th March 2006 11:37 
		//}
		//$this->Connection = null;
	}
	
	public function GetConnection() {
		return $this->Connection;
	}
	
	public function __construct() {
		//dprint_callstack();
		$this->debug = (defined('DEV_MODE') && constant('DEV_MODE') === true);
	}

	/**
	 * Connect to database
	 *
	 * @param string $Type
	 * @param string $User
	 * @param string $Password
	 * @param string $Database
	 * @param string $Host
	 * @param string $Port0
	 */
	public function Connect($Type, $User, $Password, $Database, $Host = null, $Port = null) {
		//dprint_callstack();
		try {
			$ConnectionString = "$Type:host=$Host;dbname=$Database";

			if(!empty(self::$DATABASE_CONN_OBJ)) {
    			$this->Connection = self::$DATABASE_CONN_OBJ;
    			return ;
    		}
    		$this->Connection = new PDO($ConnectionString, $User, $Password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$this->Connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			self::$DATABASE_CONN_OBJ = $this->Connection;
			
		} catch(PDOException $e) {
			die('can not connect to db :'.$e->getMessage());
	    	//throw new RDataException('can not connect to db',  $e->getMessage);
	    }
	}
	
	/**
	 * Execute query
	 *
	 * @param string $Query
	 * @return RDataRecordSet
	 */
	public function Execute($Query, $query_params) {	
		/*if (!DB::isConnection($this->Connection)) {
			return;
		}*/
		if (!PDO_OPS::isConnection($this->Connection)) {
			return;
		}

		// DEBUG: Log query time 
		if ( defined('DEV_MODE') && constant('DEV_MODE')===true ) {
			$page_time_start = array_sum(explode(' ', microtime()));
			static $total_query_time = 0;
		}
		
		$Result = $this->Connection->prepare($Query);
        $Result->execute($query_params);
        //$Result = $this->Connection->query($Query);

        // DEBUG: Log query time
        if ( defined('DEV_MODE') && constant('DEV_MODE')===true ) {
	        $page_time_end = array_sum(explode(' ', microtime()));
	        
	        $this->queryTime = ROUND($page_time_end - $page_time_start,4);
	        
	        $total_query_time += ($page_time_end - $page_time_start);
	        $GLOBALS['total_query_time'] = $total_query_time;
        }
        
		/*if (DB::isError($Result)) {
			throw new RDataException('Execute failed', $Result);
		}*/
		
		$found_rows = -1;
		if(strpos($Query, 'SQL_CALC_FOUND_ROWS') !== false) {
		    $Rs = $this->Connection->query("SELECT found_rows() as count");
		    //$Rs = $Rs->fetchRow(DB_FETCHMODE_ASSOC);
		    $Rs = $Rs->fetch(PDO::FETCH_ASSOC);
		    $found_rows = $Rs['count'];
		}		
		//$RecordSet = new RDataRecordSet($Result, $this->Connection->affectedRows(), $found_rows); OLD
		$RecordSet = new RDataRecordSet($Result, $Result->rowCount(), $found_rows);
		return $RecordSet;
	}
	
	public function getLastInsertedId() {
		return $this->Connection->lastInsertId();
	}
	
	protected function reconnect() {
	    $this->Connection = null;
	    $this->Connect('mysql', DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_HOST, DATABASE_PORT, true);
	}
}