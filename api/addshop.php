<?php
require_once dirname ( __FILE__ ) . "/../" . 'common/WebAuth.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/HttpHelper.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiShop.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiAdmin.php';
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
$name=HttpHelper::getParam("name","undefined name",array($_POST,$_GET,$_SERVER,$_COOKIE));
if ($name=="undefined name") {
	HttpHelper::echoJson(array(RET_CODE=>2,ERR_MSG=>"name param is required!"));
}
$openID=HttpHelper::getParam("openID","undefined openid",array($_POST,$_GET,$_SERVER,$_COOKIE));
if ($openID=="undefined openid") {
	HttpHelper::echoJson(array(RET_CODE=>3,ERR_MSG=>"openID param is required!"));
}
$wifiShop=new WifiShop(array("shopOpenID"=>$openID,
		"identify"=>"admin ".$adminID." handle wifishop","adminID"=>$adminID,
		"name"=>$name));
$shopID=WifiShop::insert($wifiShop);
if ($shopID==false) {
	HttpHelper::echoJson(array(RET_CODE=>4,ERR_MSG=>"error in server"));
}else{
	HttpHelper::echoJson(array(RET_CODE=>0,ERR_MSG=>"success"));
}
?>