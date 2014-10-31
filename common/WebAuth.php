<?php
require_once dirname ( __FILE__ ) . "/../" . 'common/auth.php';
require_once dirname ( __FILE__ ) . "/../" . 'common/HttpHelper.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiShop.php';
require_once dirname ( __FILE__ ) . "/../" . 'obj/WifiAdmin.php';
class WebAuth{
	public static function auth(){
		session_start();
		$dafan_auth=HttpHelper::getParam("dafan_auth","",array($_COOKIE,$_SERVER,$_POST,$_GET));
		$dbIdentify=isset($_SESSION["dbIdentify"])?$_SESSION["dbIdentify"]:"";
		$dbToken= isset($_SESSION["dbToken"])?$_SESSION["dbToken"]:"";
		$dbTimeout=isset($_SESSION["dbTimeout"])?$_SESSION["dbTimeout"]:"";
		global $_SERVER;
		$fixPara = $_SERVER ['HTTP_USER_AGENT'];
		$hasAuth=Auth::authMsg($dafan_auth, "WifiAdmin",$dbIdentify ,$dbToken, $dbTimeout,$fixPara);
		if ($hasAuth===false) {
			return false;
		}
		if ($hasAuth!==true) {
			$_SESSION["dbIdentify"]=$hasAuth->identify;
			$_SESSION["dbToken"]=$hasAuth->token;
			$_SESSION["dbTimeout"]=$hasAuth->timeout;
		}
		return true;
	}
}