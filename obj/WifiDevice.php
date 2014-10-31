<?php
class WifiDevice {
	public $wifiID;
	public $wifiIdentify;
	public $shopID;
	public $wifiName;
	public $isUsed;
	public $cookies;
	function __construct($obj) {
		$this->wifiID=isset($obj["wifiID"])?$obj["wifiID"]:-1;
		$this->wifiIdentify=isset($obj["wifiIdentify"])?$obj["wifiIdentify"]:"";
		$this->shopID=isset($obj['shopID'])?$obj['shopID']:"1";
		$this->wifiName=isset($obj["wifiName"])?$obj["wifiName"]:"";
		$this->isUsed=isset($obj["isUsed"])?$obj["isUsed"]:"0";
		$this->cookies=isset($obj["cookies"])?$obj["cookies"]:"";
	}
	function __destruct() {
	}
	private static $getCountByShopIDStr="select count(*) as retcount from wifiDevice where shopID=%d";
	public static function getCountByShopID($shopID){
		AlertMySQL::con();
		$query = sprintf ( self::$getCountByShopIDStr, intval($shopID));
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			return -1;
		}
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return $row["retcount"];
			}
		} while ( $row );
		return 0;
	}
	private static $queryByShopIDStr="select * from wifiDevice where shopID=%d limit %d,%d";
	public static function queryByShopID($shopID,$page=0,$pageSize=10){
		AlertMySQL::con();
		$query = sprintf ( self::$queryByShopIDStr, intval($shopID), 
													 intval($page*$pageSize),
													 intval($pageSize));
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}
		$result=array();
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				array_push($result, new WifiDevice($row));
			}
		} while ( $row );
		if (count($result)>0) {
			return $result;
		}
		return false;
	}
	private static $insertStr="insert into wifiDevice (wifiIdentify,shopID,wifiName)values('%s',%d,'%s')";
	public static function insert($obj){
		AlertMySQL::con();
		$query = sprintf ( self::$insertStr, mysql_real_escape_string ( $obj->wifiIdentify ),
				intval ( $obj->shopID),
				mysql_real_escape_string ( $obj->wifiName ) );
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			die ( mysql_error () );
			return false;
		} else {
			return mysql_insert_id ();
		}
	}
	private static $updateIdentifyIsUsedStr="update wifiDevice set isUsed='%d', wifiIdentify='%s', cookies='%s' where wifiID=%d ";
	public static function updateIdentifyIsUsedCookies($obj){
		AlertMySQL::con();
		$query = sprintf ( self::$updateIdentifyIsUsedStr, 
				intval ( $obj->isUsed ),
				mysql_real_escape_string($obj->wifiIdentify),
				mysql_real_escape_string($obj->cookies),
				intval ( $obj->wifiID));
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			die ( mysql_error () );
			return false;
		} else {
			return true;
		}
	}
	private static $queryByIdentifyStr="select * from wifiDevice where wifiIdentify='%s'";
	public static function queryByIdentify($wifiIdentify){
		AlertMySQL::con();
		$query = sprintf ( self::$queryByIdentifyStr, mysql_real_escape_string($wifiIdentify));
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			return false;
		} 
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return new WifiDevice($row);
			}
		} while ( $row );
		return false;
	}
	
	private static $queryBywifiIDStr="select * from wifiDevice where wifiID='%d'";
	public static function queryBywifiID($wifiID){
		AlertMySQL::con();
		$query = sprintf ( self::$queryBywifiIDStr, intval($wifiID));
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return new WifiDevice($row);
			}
		} while ( $row );
		return false;
	}
}

?>