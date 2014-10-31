<?php
require_once dirname ( __FILE__ ) . "/../" . 'common/WebAuth.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/HttpHelper.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/StringHelper.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiShop.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiAdmin.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiDevice.php';
$hasAuth=WebAuth::auth();
if (!$hasAuth) {
	HttpHelper::echoJson(array(RET_CODE=>1,ERR_MSG=>'auth error'));
}
$adminID=HttpHelper::getParam("adminID",-1,array($_SESSION));
if ($adminID==-1) {
	$exparray=explode ( ':', $_COOKIE["dafan_auth"] );
	$adminIdentify = $exparray[0];
	$adminID=WifiAdmin::queryByIdentify($adminIdentify)->adminID;
}
if ($adminID==-1) {
	HttpHelper::echoJson(array(RET_CODE=>1,ERR_MSG=>'auth error when adminID==-1'));
}
//check shopID
$shopID=HttpHelper::getParam("shopID",-1,array($_GET,$_POST,$_SERVER,$_COOKIE,$_SESSION));
$wifiShop=WifiShop::checkShopID($shopID, $adminID);
if ($wifiShop==false) {
	HttpHelper::echoJson(array(RET_CODE=>2,ERR_MSG=>"not your shop,please check!"));
}
//add wifi for this shopid
$wifiName=HttpHelper::getParam("wifiName","undefined name",array($_POST,$_GET,$_SERVER,$_COOKIE));
if ($wifiName=="undefined name") {
	HttpHelper::echoJson(array(RET_CODE=>2,ERR_MSG=>"name param is required!"));
}
$shopID=HttpHelper::getParam("shopID","undefined shopID",array($_POST,$_GET,$_SERVER,$_COOKIE));
if ($shopID=="undefined shopID") {
	HttpHelper::echoJson(array(RET_CODE=>3,ERR_MSG=>"shopID param is required!"));
}
//
$identify="";
do {
	$identify = md5(StringHelper::generateString ().time());
	$tmp = WifiDevice::queryByIdentify ( $identify );
} while ( $tmp != false );
$wifiDevice=new WifiDevice(array("wifiIdentify"=>$identify,
								 "shopID"=>$shopID,
								 "wifiName"=>$wifiName));
$deviceID=WifiDevice::insert($wifiDevice);
if ($deviceID==false) {
	HttpHelper::echoJson(array(RET_CODE=>4,ERR_MSG=>"error in server"));
}else{
	HttpHelper::echoJson(array(RET_CODE=>0,ERR_MSG=>"success"));
}
?>