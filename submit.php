<?php
/*
 * submit token,通过token，查询超时时间，检查是否超时 检查该token对应的商家和wifi，如果不超时 todo：wifi断开后要怎么处理 set cookies set token is use true
 */
require_once dirname ( __FILE__ ) . "/" . 'common/auth.php';
require_once dirname ( __FILE__ ) . "/" . 'common/HttpHelper.php';
require_once dirname ( __FILE__ ) . "/" . 'obj/WifiToken.php';
require_once dirname ( __FILE__ ) . "/" . 'obj/WifiDevice.php';
require_once dirname ( __FILE__ ) . "/" . 'common/StringHelper.php';
session_start ();
$result = array ();
$action = HttpHelper::getParam ( "action", "", array (
		$_GET,
		$_POST,
		$_COOKIE,
		$_SERVER 
) );
if ($action == "submit") {
	$result = submitToken ();
} else {
	$lastToken = HttpHelper::getParam ( "lastToken", "", array (
			$_COOKIE,
			$_GET,
			$_POST 
	) );
}
function submitToken() {
	$token = HttpHelper::getParam ( "token", "", array (
			$_GET,
			$_POST,
			$_SERVER,
			$_COOKIE 
	) );
	if ($token == "") {
		return array (
				RET_CODE => 1,
				ERR_MSG => 'token is required' 
		);
	}
	$wifiToken = WifiToken::queryByToken ( $token );
	if ($wifiToken == false) {
		return array (
				RET_CODE => 2,
				ERR_MSG => 'token is illegal' 
		);
	}
	$wifiDevice = WifiDevice::queryBywifiID ( $wifiToken->tokenWifi );
	if ($wifiDevice == false) {
		return array (
				RET_CODE => 4,
				ERR_MSG => "no wifi device for this token" 
		);
	}
	if ($wifiToken->isUsed == 1) {
		if (time () > $wifiToken->tokenTimeout) {
			return array (
					RET_CODE => 5,
					ERR_MSG => 'token is timeout' 
			);
		}
		$cookies = HttpHelper::getParam ( "token" . $token, "", array (
				$_COOKIE,
				$_GET,
				$_POST,
				$_SERVER 
		) );
		$pass = false;
		if ($cookies != "") {
			$dbCookies = md5 ( $token . $wifiToken->tokenTimeout . $wifiDevice->wifiIdentify );
			if ($cookies == $dbCookies) {
				setcookie ( "lastToken", $token, $wifiToken->tokenTimeout );
				return array (
						RET_CODE => 0,
						ERR_MSG => "success" 
				);
			}
		}
		return array (
				RET_CODE => 3,
				ERR_MSG => '重复提交验证码时请勿切换浏览器' 
		);
	} else if ($wifiToken->isUsed == 0) {
		$wifiToken->isUsed = 1;
		$wifiToken->tokenTimeout = time () + 24 * 60 * 60;
		$wifiToken->cookies = md5 ( $token . $wifiToken->tokenTimeout . $wifiDevice->wifiIdentify );
		WifiToken::update ( $wifiToken );
		setcookie ( "token" . $token, $wifiToken->cookies, $wifiToken->tokenTimeout );
		setcookie ( "lastToken", $token, $wifiToken->tokenTimeout );
		return array (
				RET_CODE => 0,
				ERR_MSG => "success" 
		);
	}
}
?>
<html>
<head>
<title>提交验证码</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css"
	href="resources/css/jqm/jquery.mobile.flatui.css" />
<script src="resources/js/jquery-1.8.2.js"></script>
<script src="resources/js/jquery.mobile-1.4.0-rc.1.js"></script>
<script type="text/javascript"
				src="http://service.rippletek.com/ext/js/oAuth.js"></script>
<?php 
if(count($result)!=0){
	if (isset($result[RET_CODE]) && $result[RET_CODE]==0) {
		?>
		<script type="text/javascript"
				src="http://service.rippletek.com/ext/js/oAuth.js"></script>
		<?php 
	}
}
?>
</head>
<body>
	<div data-role="page">
		<div data-role="content" style="text-align: center;">
			<?php
			
			if (count ( $result ) == 0) {
				?>
			
			<form id="submit-form" method="post" action="submit.php">
				<input type="hidden" name="action" value="submit" /> <input
					type="text" name="token" id="token" placeholder="请输入你的验证码"
					value="<?php echo $lastToken;?>" /> <input type="submit"
					id="btSubmit" value="提交" />
			</form>
			<?php
			
} else {
				echo "success";
				if (isset ( $result [RET_CODE] ) && $result [RET_CODE] == 0) {
					?>
				<script>
					$(document).ready(function() {
						rptk_oneclick();
// 						window.location.href="success.php";
					});
				</script>
				<?php }}?>
		</div>
	</div>

</body>
</html>