<?php
require_once dirname ( __FILE__ ) . "/" . 'IBaseObj.php';
class WifiAdmin implements IBaseObj{
	public $adminID;
	public $admin;
	public $passwd;
	public $identify;
	public $timeout;
	public $token;
	public function __construct($obj){
		$this->adminID=$obj["adminID"];
		$this->admin=$obj["admin"];
		$this->passwd=$obj["passwd"];
		$this->identify=$obj["identify"];
		$this->timeout=$obj["timeout"];
		$this->token=$obj["token"];
	}
	private static $loginStr="select * from wifiAdmin where admin='%s' limit 1";
	public static function login($admin,$passwd,$pubKey){
		AlertMySQL::con();
		$query = sprintf ( self::$loginStr, mysql_real_escape_string ( $admin ) );
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				$wifiAdmin= new WifiAdmin( $row );
				if ($passwd==md5($wifiAdmin->passwd.$pubKey)) {
					return $wifiAdmin;
				}else{
					return false;
				}
			}
		} while ( $row );
		return false;
	}
	private static $queryByIdentifyStr="select * from wifiAdmin where identify='%s'";
	public static function queryByIdentify($identify){
		AlertMySQL::con();
		$query=sprintf(self::$queryByIdentifyStr,mysql_real_escape_string($identify));
		$ret=mysql_query($query,AlertMySQL::con());
		if ($ret === false) {
			return false;
		}
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return new WifiAdmin( $row );
			}
		} while ( $row );
		return false;
	}

	public function setIdentifier($identify) {
		$this->identify=$identify;
	}

	public function setToken($token) {
		$this->token=$token;
	}

	public function setTimeOut($timeOut) {
		$this->timeout=$timeOut;
	}
	public function getTimeOut(){
		return $this->timeout;
	}
	public function getIdentifier(){
		return $this->identify;
	}
	public function getToken(){
		return $this->token;
	}
	private static $updateIdentifyStr="update wifiAdmin set identify='%s',token='%s',timeout='%s' where admin='%s' ";
	public static function updateIdentify($baseObj) {
		AlertMySQL::con();
		$query=sprintf(self::$updateIdentifyStr,mysql_real_escape_string($baseObj->identify),
												mysql_real_escape_string($baseObj->token),
												mysql_real_escape_string($baseObj->timeout),
												mysql_real_escape_string($baseObj->admin));
		$ret=mysql_query($query,AlertMySQL::con());
		if ($ret === false) {
			die ( mysql_error () );
			return false;
		} else {
			return true;
		}
	}

}