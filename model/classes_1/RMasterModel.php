<?php
class RMasterModel extends PDO {
    protected $conn;
    protected $error;
    private $selectFields = array();
    private $where = array();
    private $join = array();
    private $tablename;
    private $querymode;
    private $statement;
    private $fullQuery;
    protected $fields;
    private $updateParams;
    private $syncFields;
    private $orderBy;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=".DB_HOSTNAME.";dbname=".DB_DBNAME, DB_USERNAME, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            $this->error = "Connection failed: " . $e->getMessage();
            echo $this->error;
            exit;
        }
    }

    public function setSyncFields($fields = array()) {
        $this->syncFields = $fields;
    }

    public function setUpdate($tablename) {
        $this->querymode = 'update';
        $this->tablename = $tablename;
    }

    public function setInsert($tablename) {
        $this->querymode = 'insert';
        $this->tablename = $tablename;
    }

    public function setOrderBy($orderby) {
        $this->orderBy = $orderby;
    }

    public function setFrom($tablename) {
        $this->querymode = 'select';
        $this->tablename = $tablename;

        if($this->tablename) {
            switch ($this->querymode) {
                case 'select':
                    $fromTable = $this->tablename;
                    $field_str = '';
                    if(empty($this->selectFields)) {
                        $field_str = '*';
                    } elseif(is_array($this->selectFields)) {
                        $field_str = implode(',', $this->selectFields);
                    } else {
                        $field_str = $this->selectFields;
                    }
                    $condition_str = ' WHERE 1 ';
                    if($this->where) {
                        foreach ($this->where as $value) {
                            $cond = $value['condition'];
                            $condition_str .= " ".$cond." ";
                        }
                    }
                    $join_str = '';
                    if($this->join) {
                        foreach ($this->join as $value) {
                            $join_str .= " ".$value." ";
                        }
                    }
                    $this->fullQuery = "SELECT $field_str FROM ".$fromTable.$join_str.$condition_str;
                    if($this->where) {
                        foreach ($this->where as $key => $value) {
                            $cond = $value['condition'];
                            if (($pos = strpos($cond, ":")) !== FALSE) {
                                $param_name = substr($cond, $pos + 1);
                                $this->where[$key]['param'] = ':'.$param_name;
                            }
                        }
                    }
                    break;
            }
        }
    }

    public function setWhere($condition, $value, $type) {
        $value = getTypeVal($value, $type);
        $this->where[] = array(
            'condition' => $condition,
            'value' => $value
        );
    }

    public function setJoin($join) {
        $this->join[] = $join;
    }

    public function setSelect($fields = null) {
        if(!empty($fields)) {
            if(!is_array($fields)) {
                $fields = explode(',', $fields);
            }
            $this->selectFields = array_merge($this->selectFields, $fields);
        }
    }

    public function setUpdateParams($Data) {
        $this->updateParams = $Data;
    }

    public function getFullQuery() {
        $params = array_column($this->where, 'param');
        $value = array_column($this->where, 'value');
        echo str_replace($params, $value, $this->fullQuery);
    }

    public function exec_query($query='') {
        if($this->tablename) {
            if(!empty($this->updateParams->fields)) {
                $allowedColumns = array_intersect($this->syncFields, $this->updateParams->fields);
            }
            switch ($this->querymode) {
                case 'select':
                    $fromTable = $this->tablename;
                    $field_str = '';
                    if(empty($this->selectFields)) {
                        $field_str = '*';
                    } elseif(is_array($this->selectFields)) {
                        $field_str = implode(',', $this->selectFields);
                    } else {
                        $field_str = $this->selectFields;
                    }
                    $condition_str = ' WHERE 1 ';
                    $params_arr = array();
                    if($this->where) {
                        foreach ($this->where as $value) {
                            $cond = $value['condition'];
                            $condition_str .= " ".$cond." ";
                            if (($pos = strpos($cond, ":")) !== FALSE) {
                                $param_name = substr($cond, $pos + 1);
                                $param_name = preg_replace('/[^a-zA-Z0-9_\']/', '', $param_name);
                                if(!empty($param_name)) {
                                    $value['param'] = $param_name;
                                    $params_arr[':'.$param_name] = $value['value'];
                                }
                            }
                        }
                    }
                    $join_str = '';
                    if($this->join) {
                        foreach ($this->join as $value) {
                            $join_str .= " ".$value." ";
                        }
                    }
                    $orderby_str = '';
                    if(!empty($this->orderBy)) {
                        $orderby_str = ' ORDER BY '.$this->orderBy;
                    }
                    $fullQuery = "SELECT $field_str FROM ".$fromTable.$join_str.$condition_str.$orderby_str;
                    $stmt = $this->conn->prepare($fullQuery);
                    foreach ($params_arr as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    $stmt->execute();
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $result = $stmt->fetchAll();
                    $this->reset();
                    return $result;
                    break;
                case 'update':
                    $updateTable = $this->tablename;
                    $field_str = '';
                    $allObjectVars = get_object_vars($this->updateParams);
                    
                    $set_str = $params_arr = array();
                    $condition_str = ' WHERE 1 ';
                    if($this->where) {
                        foreach ($this->where as $value) {
                            $cond = $value['condition'];
                            $condition_str .= " ".$cond." ";
                            if (($pos = strpos($cond, ":")) !== FALSE) {
                                $param_name = substr($cond, $pos + 1);
                                $param_name = preg_replace('/[^a-zA-Z0-9_\']/', '', $param_name);
                                if(!empty($param_name)) {
                                    $value['param'] = $param_name;
                                    $params_arr[':'.$param_name] = $value['value'];
                                }
                            }
                        }
                    }
                    foreach ($allObjectVars as $key => $value) {
                        if(in_array($key, $allowedColumns)) {
                            $set_str[] = " ".$key." = :".$key." ";
                            $params_arr[':'.$key] = $value;
                        }
                    }
                    $set_str = implode(',', $set_str);
                    $join_str = '';
                    if($this->join) {
                        foreach ($this->join as $value) {
                            $join_str .= " ".$value." ";
                        }
                    }
                    $fullQuery = "UPDATE $updateTable SET $set_str ".$join_str.$condition_str;
                    $stmt = $this->conn->prepare($fullQuery);

                    foreach ($params_arr as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    echo '<pre>'; print_r($fullQuery); echo '</pre>';
                    exit;
                    $result = $stmt->execute();
                    $this->reset();
                    return $result;
                    break;
                case 'insert':
                    $insertTable = $this->tablename;
                    $field_str = '';
                    $allObjectVars = get_object_vars($this->updateParams);
                    // echo '<pre>'; print_r($allObjectVars); echo '</pre>';
                    
                    $set_str = $params_arr = array();
                    foreach ($allObjectVars as $key => $value) {
                        if(in_array($key, $allowedColumns) && !array_key_exists(':'.$key, $params_arr)) {
                            $set_str[] = ":".$key;
                            $params_arr[':'.$key] = $value;
                        }
                    }
                    $set_str = implode(',', $set_str);
                    
                    $fullQuery = "INSERT INTO $insertTable(".implode(',', $allowedColumns).") values(".$set_str.")";
                    $stmt = $this->conn->prepare($fullQuery);

                    foreach ($params_arr as $key => $value) {
                        $stmt->bindValue($key, $value);
                    }
                    var_dump($fullQuery);
                    print_r($params_arr);
                    // break;
                    $result = $stmt->execute();
                    $this->reset();
                    return $this->conn->lastInsertId();
                    break;
            }
        } elseif(!empty($query)) {
            var_dump($query);
            $this->conn->exec($query);
            return $this->conn->lastInsertId();
        }
    }

    public function reset() {
        $this->selectFields = array();
        $this->where = array();
        $this->join = array();
        $this->tablename = null;
        $this->querymode = null;
        $this->statement = null;
        $this->fullQuery = null;
    }

}
?>