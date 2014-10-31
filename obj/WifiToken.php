<?php
class WifiToken {
	public $token;
	public $tokenTimeout;
	public $tokenWifi;
	public $isUsed;
	public $cookies;
	function __construct($obj) {
		$this->token=isset($obj["token"])?$obj["token"]:"";
		$this->tokenTimeout=isset($obj["tokenTimeout"])?$obj["tokenTimeout"]:"";
		$this->tokenWifi=isset($obj["tokenWifi"])?$obj["tokenWifi"]:"";
		$this->isUsed=isset($obj["isUsed"])?$obj["isUsed"]:"";
		$this->cookies=isset($obj["cookies"])?$obj["cookies"]:"";
	}
	function __destruct() {
	}
	private static $queryByTokenTimeoutStr = "select * from WifiToken where token='%d' and tokenTimeout >'%d'";
	public static function queryByTokenTimeout($token,$timeout) {
		AlertMySQL::con();
		$query = sprintf ( self::$queryByTokenStr, $token, $timeout);
		$ret = mysql_query ( $query,AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return new WifiToken ( $row );
			}
		} while ( $row );
		return false;
	}
	private static $queryByTokenStr = "select * from wifiToken where token='%d'";
	public static function queryByToken($token) {
		AlertMySQL::con();
		$query = sprintf ( self::$queryByTokenStr, $token);
		$ret = mysql_query ( $query,AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return new WifiToken ( $row );
			}
		} while ( $row );
		return false;
	}
	private static $delByTokenStr="delete from wifiToken where token=%d";
	public static function delByToken($token){
		AlertMySQL::con();
		$query = sprintf ( self::$queryByTokenStr, intval($token));
		$ret = mysql_query ( $query,AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}else{
			return true;
		}
	}
	private static $insertStr="insert into wifiToken (token,tokenTimeout,tokenWifi,isUsed,cookies) values (%d,%d,%d,%d,'%s')";
	public static function insert($obj){
		AlertMySQL::con();
		$query = sprintf ( self::$insertStr, 
				intval($obj->token),
				intval($obj->tokenTimeout),
				intval($obj->tokenWifi),
				intval($obj->isUsed),
				mysql_real_escape_string($obj->cookies));
		$ret = mysql_query ( $query,AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}else{
			return true;
		}
	}
	private static $updateStr="update wifiToken set isUsed=%d , tokenTimeout=%d , cookies='%s' where token=%d ";
	public static function update($obj){
		AlertMySQL::con();
		$query=sprintf(self::$updateStr,
						intval($obj->isUsed),
						intval($obj->tokenTimeout),
						mysql_real_escape_string($obj->cookies),
						intval($obj->token));
		$ret = mysql_query ( $query,AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}else{
			return true;
		}
	}
	public static function setToken($wifiToken){
		
	}
	
}

?>