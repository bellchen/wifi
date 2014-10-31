<?php
/*
 * get 参数获得identifity，对应商家和wifi设备，返回token set devcie's cookies is used,
 */
require_once dirname ( __FILE__ ) . "/" . 'obj/WifiDevice.php';
require_once dirname ( __FILE__ ) . "/" . 'common/HttpHelper.php';
require_once dirname ( __FILE__ ) . "/" . 'common/StringHelper.php';
require_once dirname ( __FILE__ ) . "/" . 'common/auth.php';
$identify = HttpHelper::getParam ( "identify", "", array (
		$_GET,
		$_POST 
) );
$wifiDevice = WifiDevice::queryByIdentify ( $identify );
$result = array ();
if ($wifiDevice == false) {
	$result = array (
			RET_CODE => 1,
			ERR_MSG => '不合法请求，请通过微信重新生成链接' 
	);
} else {
	if ($wifiDevice->isUsed == 0) {
		// 生成cookies，写入数据库，设置isUsed为1
		global $_SERVER;
		$ua = $_SERVER ['HTTP_USER_AGENT'];
		$fixpara = "dafan";
		$cookies = md5 ( StringHelper::generateString () . $ua . $fixpara . time () );
		setcookie ( "wifiToken" . $wifiDevice->wifiID, $cookies, time () + 60 * 60 * 24 * 30 );
		$wifiDevice->cookies = $cookies;
		$wifiDevice->isUsed = 1;
		WifiDevice::updateIdentifyIsUsedCookies ( $wifiDevice );
	} else if ($wifiDevice->isUsed == 1) {
		// 校验cookies,如果不匹配，则返回不合法
		$cookies = isset ( $_COOKIE ["wifiToken" . $wifiDevice->wifiID] ) ? $_COOKIE ["wifiToken" . $wifiDevice->wifiID] : "";
		if ($cookies != $wifiDevice->cookies) {
			$result = array (
					RET_CODE => 2,
					ERR_MSG => '不合法请求，请通过微信进入页面' 
			);
		}
	}
	if (count($result)==0) {
		$token = Auth::getNextToken ( $wifiDevice->wifiID, $wifiDevice->wifiIdentify );
		$result = array (
				RET_CODE => 0,
				ERR_MSG => 'success',
				RESULT => $token 
		);
	}
}
?>
<html>
<head>
<title>获取token</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css"
	href="resources/css/jqm/jquery.mobile.flatui.css" />
<script src="resources/js/jquery-1.8.2.js"></script>
<script src="resources/js/jquery.mobile-1.4.0-rc.1.js"></script>
</head>
<body>
	<div data-role="page">
		<div data-role="content" style="text-align: center;">
			<h1><?php 
			if ($result[RET_CODE]==0){
				echo $result[RESULT];
			}else{
				echo $result[ERR_MSG];
			}
			?></h1>
			<?php if ($result[RET_CODE]==0){?>
			<input type="button" value="下一个验证码" onclick="window.location.reload()">
			<?php }?>
		</div>
	</div>

</body>
</html>
