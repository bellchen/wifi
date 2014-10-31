<?php
require_once dirname ( __FILE__ ) . "/../" . 'common/WebAuth.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/HttpHelper.php';
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
$shopID=HttpHelper::getParam("shopID",-1,array($_GET,$_POST,$_SERVER,$_COOKIE,$_SESSION));
//check shopID
$wifiShop=WifiShop::checkShopID($shopID, $adminID);
if ($wifiShop==false) {
	HttpHelper::echoJson(array(RET_CODE=>2,ERR_MSG=>"not your shop,please check!"));
}
//return count
$retCount=WifiDevice::getCountByShopID($shopID);
if ($retCount<0) {
	HttpHelper::echoJson(array(RET_CODE=>3,ERR_MSG=>"error in server",RESULT=>$retCount));
}else{
	HttpHelper::echoJson(array(RET_CODE=>0,ERR_MSG=>"success",RESULT=>$retCount));
}
?>