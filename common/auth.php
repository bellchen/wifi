<?php
require_once dirname ( __FILE__ ) . "/" . '../common/MysqlConfig.php';
require_once dirname ( __FILE__ ) . "/" . '../obj/IBaseObj.php';
require_once dirname ( __FILE__ ) . "/" . '../common/HttpHelper.php';
require_once dirname ( __FILE__ ) . "/" . '../common/StringHelper.php';
require_once dirname ( __FILE__ ) . "/" . '../obj/WifiToken.php';
class Auth {
	public static function getAuthMsg($user, $userClass, $fixPara = "dafan",$timeout=604800) {
		do {
			$identify = StringHelper::generateString ();
			$tmp = $userClass::queryByIdentify ( $identify );
		} while ( $tmp != false );
		$user->setIdentifier ( $identify );
		$user->setToken ( StringHelper::generateString () );
		$timeout = time () + $timeout;
		$user->setTimeOut ( $timeout );
		$cookies = $user->getIdentifier () . ":" . md5 ( $user->getToken () . $timeout . $fixPara );
		$userClass::updateIdentify ( $user );
		return array (
				$user,
				$cookies,
				$timeout 
		);
	}
	public static function authMsg($cookies, $userClass, $dbidentify, $dbtoken, $dbtimeout, $fixPara = "dafan") {
		if ($cookies==null) {
			return false;
		}
		$exparray = explode ( ':', $cookies );
		if (is_array ( $exparray ) && count ( $exparray ) == 2) {
			$identify = $exparray [0];
			$token = $exparray [1];
			$user=false;
			if ($dbidentify == "" || $dbidentify != $identify) {
				$user = $userClass::queryByIdentify ( $identify );
				if ($user != false) {
					$dbidentify = $user->getIdentifier ();
					$dbtoken = $user->getToken ();
					$dbtimeout = $user->getTimeOut ();
				}
			}
			if ($token == md5 ( $dbtoken . $dbtimeout . $fixPara )) {
				if (time () <= $dbtimeout) {
					return $user?$user:true;
				}
			}
		}
		return false;
	}
	private static function getToken(){
		$token="";
		do {
			$token=StringHelper::generateToken();
			$tmp = WifiToken::queryByTokenTimeout($token, time());
		} while ( $tmp != false );
		return $token;
	}
	public static function getNextToken($wifiID,$wifiIdentify){
		$token=Auth::getToken();
		$isUse=0;
		$wifiToken=new WifiToken(array(	"token"=>$token,
				"tokenTimeout"=>0,
				"tokenWifi"=>$wifiID,
				"isUsed"=>$isUse,
				"cookies"=>""));
		if (WifiToken::insert($wifiToken)==false) {
			WifiToken::delByToken($wifiToken->token);
			WifiToken::insert($wifiToken);
		}
		return $token;
	}
}
?>