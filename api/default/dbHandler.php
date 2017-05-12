<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
    }
    /**
     * Fetching single record
     */
    public function runQuery($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        return $result = $this->conn->affected_rows;  
    }

    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }

    /**
     * Fetching multiple records
     */
    public function getRecordset($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if($r->num_rows > 0){
            $result = array();
            while($row = $r->fetch_assoc()){
                $result[] = $row;
            }
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Delete record(s)
     */
    public function deleteFromTable($table, $idcol, $value) {
        $r = $this->conn->query("DELETE FROM $table WHERE $idcol = '$value'") or die($this->conn->error.__LINE__);
        return $result = $this->conn->affected_rows;    
    }
    
    /**
     * Creating new record using array (instead of object)
     */
    public function insertToTable($supplied_values, $column_names, $table_name) {
        
        $columns = '';
        $values = '';
        //column names
        foreach ($column_names as $col) {
            $columns .= "`".$col . "`,";
        }
        //values
        foreach ($supplied_values as $val) {
            // $values .= "'".$val."',";
            $values .= empty($val) ? "NULL," : "'".$val."',";
        }

        $query = "INSERT INTO `".$table_name."` (".trim($columns,',').") VALUES(".trim($values,',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            return $this->conn->insert_id;
            } else {
            return NULL;
        }
    }
    public function updateInTable($table, $columnsArray, $where){ 
        $a = array();
        $w = "";
        $c = "";
        //where clause
        foreach ($where as $key => $value) {
            $w .= " AND " .$key. " = '".$value."'";
        }
        //set columns
        foreach ($columnsArray as $key => $value) {
            $c .= $key. " = '".$value."', ";
        }
        $c = rtrim($c,", ");

        //run update query
        $query = "UPDATE `$table` SET $c WHERE 1=1 ".$w;
        //return ($query);
        $r = $this->conn->query($query); //or die($this->conn->error.__LINE__);

        if ($r) {
            //u can try to get affected rows, not so necessary
            $affected_rows = $this->conn->affected_rows;
            return $affected_rows;
            //return "OK";
            } else {
            return $this->conn->error;
        }
        
    }

    public function updateToNull($table, $column, $where){ 
        $a = array();
        $w = "";
        //where clause
        foreach ($where as $key => $value) {
            $w .= " AND " .$key. " = '".$value."'";
        }

        //run update query
        $query = "UPDATE `$table` SET $column = NULL WHERE 1=1 ".$w;
        //return ($query);
        $r = $this->conn->query($query); //or die($this->conn->error.__LINE__);

        if ($r) {
            //u can try to get affected rows, not so necessary
            $affected_rows = $this->conn->affected_rows;
            return $affected_rows;
            //return "OK";
            } else {
            return $this->conn->error;
        }
        
    }




public function getSession(){
    if (!isset($_SESSION)) {
        session_start();
    }
    $sess = array();
    if(isset($_SESSION['tat_id']))
    {
        $sess["tat_id"] = $_SESSION['tat_id'];
        $sess["tat_name"] = $_SESSION['tat_name'];
        $sess["tat_email"] = $_SESSION['tat_email'];
        $sess["tat_type"] = $_SESSION['tat_type'];
        $sess["tat_is_admin"] = $_SESSION['tat_is_admin'];
    }
    else
    {
        $sess["tat_id"] = '';
        $sess["tat_name"] = 'Guest';
        $sess["fta-email"] = '';
        $sess["tat_type"] = '';
        $sess["tat_is_admin"] = '';
    }
    return $sess;
}
public function destroySession(){
    if (!isset($_SESSION)) {
    session_start();
    }
    if(isSet($_SESSION['tat_id']))
    {
        unset($_SESSION['tat_id']);
        unset($_SESSION['tat_name']);
        unset($_SESSION['fta-email']);
        unset($_SESSION['tat_type']);
        unset($_SESSION['tat_is_admin']);
        $info='info';
        if(isSet($_COOKIE[$info]))
        {
            setcookie ($info, '', time() - $cookie_time);
        }
        $msg="Logged Out Successfully...";
    }
    else
    {
        $msg = "Not logged in...";
    }
    return $msg;
}

public function purify($raw_value) {
    return $this->conn->real_escape_string($raw_value);
}

//function generates a random password
public function randomPassword() {
  $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
  $pass = array(); //remember to declare $pass as an array
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < 12; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
  }
  
return implode($pass); //turn the array into a string
}

//function generates a random pin
public function randomPin() {
  $alphabet = "ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
  $pass = array(); //remember to declare $pass as an array
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < 12; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
  }
  
return implode($pass); //turn the array into a string
}

//ensures pin isnt repeated
public function checkPin($pin, $pins) {
        if (!in_array($pin, $pins)) {
            return $pin;
        } else {
            $pin = $db->randomPin();
            checkPin($pin, $pins);
        }
   }

//function generates a random numeric password
public function randomNumericPassword() {
  $alphabet = "0123456789";
  $pass = array(); //remember to declare $pass as an array
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < 12; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
  }
  
return implode($pass); //turn the array into a string
}

//log action
public function logAction($action) {
    $session = $this->getSession();
    $log_result = $this->insertToTable( 
        [
            $session['tat_id'],
            $session['tat_name'], 
            $action,
            date("Y-m-d h:i:s")
        ], 
        ['log_admin_id', 'log_admin_name', 'log_details', 'log_time'], 
        'admin_log'
    );
    return true;   
}
 
}



?>
