<?php
require_once dirname ( __FILE__ ) . "/../" . 'common/MysqlConfig.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/StringHelper.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/auth.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/HttpHelper.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiAdmin.php';
$action = "";
if (is_array ( $_GET ) && count ( $_GET ) > 0) {
	if (isset ( $_GET ['action'] )) {
		$action = $_GET ['action'];
	}
}
session_start ();
if ($action == "login") {
	$username = "";
	$passwd = "";
	if (is_array ( $_POST ) && count ( $_POST ) > 0) {
		if (isset ( $_POST ['username'] )) {
			$username = strval ( $_POST ['username'] );
		}
		if (isset ( $_POST ['passwd'] )) {
			$passwd = strval ( $_POST ['passwd'] );
		}
	}
	$url = 'http://' . $_SERVER ['SERVER_NAME'] . $_SERVER ["REQUEST_URI"];
	if ($username == "" || $passwd == "") {
		HttpHelper::echoJson ( array (
				RET_CODE => 11,
				ERR_MSG => "username and passwd can not be null" 
		) );
	}
	
	if (! isset ( $_SESSION ["pubKey"] )) {
		$pubKey = "dafan";
		$passwd = md5 ( "bellchen" . $pubKey );
	} else {
		$pubKey = $_SESSION ["pubKey"];
	}
	$wifiAdmin = WifiAdmin::login ( $username, $passwd, $pubKey );
	if ($wifiAdmin == false) {
		HttpHelper::echoJson ( array (
				RET_CODE => 12,
				ERR_MSG => "username or passwd wrong" 
		) );
	}
	global $_SERVER;
	$fixPara = $_SERVER ['HTTP_USER_AGENT'];
	list ( $wifiAdmin, $token, $timeout ) = Auth::getAuthMsg ( $wifiAdmin, "WifiAdmin", $fixPara );
// 	setcookie ( "auth", $token, $timeout );
	//($token.":".$timeout);
	header("dafan_auth:".$token);
	header("dafan_auth_timeout:".$timeout);
	$_SESSION["dbIdentify"]=$wifiAdmin->identify;
	$_SESSION["dbToken"]=$wifiAdmin->token;
	$_SESSION["dbTimeout"]=$wifiAdmin->timeout;
	$_SESSION["adminID"]=$wifiAdmin->adminID;
	HttpHelper::echoJson ( array (
			RET_CODE => 0 
	) );
}
?>