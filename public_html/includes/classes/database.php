<?php
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 120);

class databaseConnection {

	var $host;
	var $db_name;
	var $username;
	var $password;
    var $res;
    var $query;

	function __construct($host,$db_name,$username,$password){
           $this->host =$host;
           $this->password = $password;
           $this->username = $username;
           $this->db_name = $db_name;
	 }


	function connect(){
		$this->res = mysql_pconnect($this->host,$this->username,$this->password);
		if(!$this->res)
			die($this->debug("connect"));
            $db = mysql_select_db($this->db_name, $this->res);
            if (!$db)
			die($this->debug("database"));
       }

      function rowcount($query){
		$this->query = $query;
		$result = mysql_query($query) or die($this->debug("query"));
		return mysql_num_rows($result);
       }

      function debug($type) {
	   switch ($type) {
		   case "connect":
			   $message = "MySQL Error Occured";
			   $result = mysql_errno() . ": " . mysql_error();
			   $output = "Could not connect to the database. Be sure to check that your database connection settings are correct and that the MySQL server in running.";
               break;

			case "database":

				$message = "MySQL Error Occurred";
				$result =  mysql_errno($this->res) . ": " .  mysql_error($this->res);
				$output = "Sorry an error has occured accessing the database. Be sure to check that your database connection settings are correct and that the MySQL server in running.";

			case "query":

		           $message = "MySQL Error Occurred";
		           $result =  mysql_errno() . ": " .  mysql_error();
		           $query = $this->query;
        }

	 //$output = '<script>document.location.href="error.php"</script>';
       return $result.$query;
	}



	function Execute($type, $query){
	    $this->query = $query;
	    if($type=="select"){
			$result = mysql_query($query) or die($this->debug("query"));
			if(mysql_num_rows($result) > 0){
				while($record = mysql_fetch_array($result)){
					$data[] = $record;
			    }
				return $data;
			 }else{
				return false;
			   }
		}else if($type=="insert") {
	           $result = mysql_query($query) or die($this->debug("query"));
	           return mysql_insert_id();
		 }else if($type=="update"){
	           $result = mysql_query($query) or die($this->debug("query"));
	           return $result;
	        }else if($type == "delete"){
                $result = mysql_query($query) or die($this->debug("query"));
	           return $result;
	       }
		return false;
     }
}

?>