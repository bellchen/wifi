<?php
class WifiShop {
	public $shopID;
	public $shopOpenID;
	public $identify;
	public $name;
	public $adminID;
	function __construct($obj) {
		$this->shopID=isset($obj["shopID"])?$obj["shopID"]:-1;
		$this->shopOpenID=isset($obj["shopOpenID"])?$obj["shopOpenID"]:"";
		$this->identify=isset($obj["identify"])?$obj["identify"]:"";
		$this->name=isset($obj["name"])?$obj["name"]:"";
		$this->adminID=isset($obj["adminID"])?$obj["adminID"]:0;
	}
	function __destruct() {
	}
	private static $queryByAdminIDStr="select * from wifiShop where adminID=%d limit %d,%d";
	public static function queryByAdminID($adminID,$page=0,$pageSize=10){
		AlertMySQL::con();
		$query = sprintf ( self::$queryByAdminIDStr, intval($adminID), 
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
				array_push($result, new WifiShop($row));
			}
		} while ( $row );
		if (count($result)>0) {
			return $result;
		}
		return false;
	}
	private static $getCountByAdminIDStr="select count(*) as retcount from wifiShop where adminID=%d";
	public static function getCountByAdminID($adminID){
		AlertMySQL::con();
		$query = sprintf ( self::$getCountByAdminIDStr, intval($adminID));
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
	private static $insertStr="insert into wifiShop(shopOpenID,identify,name,adminID) values('%s','%s','%s','%d')";
	public static function insert($obj){
		AlertMySQL::con();
		$query = sprintf ( self::$insertStr, mysql_real_escape_string ( $obj->shopOpenID ),
											 mysql_real_escape_string ( $obj->identify),
											 mysql_real_escape_string ( $obj->name ),
				 							 intval($obj->adminID) );
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			die ( mysql_error () );
			return false;
		} else {
			return mysql_insert_id ();
		}
	}
	private static $queryByOpenIDStr="select * from wifiShop where shopOpenID='%s' limit 1";
	public static function queryByOpenID($openID){
		AlertMySQL::con();
		$query = sprintf ( self::$queryByOpenIDStr, mysql_real_escape_string($openID));
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}
		$result=array();
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return new WifiShop($row);
			}
		} while ( $row );
		return false;
	}
	private static $checkShopIDStr="select * from wifiShop where shopID=%d and adminID=%d";
	public static function checkShopID($shopID,$adminID){
		AlertMySQL::con();
		$query = sprintf ( self::$checkShopIDStr, intval($shopID),intval($adminID));
		$ret = mysql_query ( $query, AlertMySQL::con() );
		if ($ret === false) {
			return false;
		}
		do {
			$row = mysql_fetch_assoc ( $ret );
			if ($row) {
				return new WifiShop($row);
			}
		} while ( $row );
		return false;
	}
	public function setShopID($shopID){
		if ($this->shopID==-1) {
			$this->shopID=$shopID;
			return true;
		}else{
			return false;
		}
	}
	private static $updateIdentifyStr="update wifiShop set identify='%s' where shopOpenID='%s'";
	public static function updateIdentify($obj){
		AlertMySQL::con();
		$query=sprintf(self::$updateIdentifyStr,mysql_real_escape_string($obj->identify),
				mysql_real_escape_string($obj->shopOpenID));
		$ret=mysql_query($query,AlertMySQL::con());
		if ($ret === false) {
			die ( mysql_error () );
			return false;
		} else {
			return true;
		}
	}
	
}

?>