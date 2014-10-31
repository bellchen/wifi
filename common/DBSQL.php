<?php
require_once ('configure.php');
class DBSQL {
	private $conn;
	public function __construct() {
		try {
			$this->conn=mysql_connect(ServerName,UserName,PassWord,true);
			mysql_select_db ( DBName, $this->conn );
		} catch (Exception $e) {
			echo $e;
			$msg=$e;
			include(ERRFILE);
		}
	}
	function __destruct() {
	}
	private function query($query){
		if(empty($query) or empty($this->conn)){			
			return false;
		}
		try {
			$result=mysql_query ( $query, $this->conn );					
			if(!$result){
				@mysql_free_result($result);
				return false;
			}
			return $result;
		} catch (Exception $e) {
			echo $e;
			$msg=$e;
			include(ERRFILE);
		}
		return false;
	}
	public function select($query){
		$result=$this->query($query);
		$count=0;
		$data=array();
		if (($result) and (!empty($result))){
			while (($row=mysql_fetch_array($result))!=false) {
				$data[$count++]=$row;
			}
		}
		@mysql_free_result($result);
		return $data;
	}
	public function insert($query){
		if(($this->query($query))){
			return 0;
		}else{
			return mysql_insert_id($this->conn);
		}
	}
	public function update($query){
		return $this->query($query);
	}
	public function delete($query){
		return $this->query($query);
	}
	public function begintransaction(){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("BEGIN");
	}
	public function roolback(){
		mysql_query("ROOLBACK");
	}
	public function commit(){
		mysql_query("COMMIT");
	}
}

?>