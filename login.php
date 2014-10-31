<?php
require_once dirname ( __FILE__ ) . "/" . 'common/StringHelper.php';
session_start ();
$_SESSION ["pubKey"] = StringHelper::generateString ();
?>
<html>
<head>
<title>登陆</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="format-detection" content="telephone=no" />
<script src="resources/js/jquery-1.8.2.js"></script>
<script src="resources/js/jquery.md5.js"></script>
<script>
$(document).ready(function() {
	$("#btLogin").click(function(){
		submitLogin();
	});
});
function submitLogin(){
	var username=$("#username").val();
	var passwd=$("#passwd").val();
	var pubKey=$("#pubKey").val();
	passwd=$.md5($.md5(passwd)+pubKey);
	console.info("username="+username+"&passwd="+passwd);
	$.ajax({
	    url: 'api/login.php?action=login',
		dataType : 'text',
		type:"POST",
		data:"username="+username+"&passwd="+passwd,
		headers: {
//             "Access-Control-Allow-Origin":"http://example.edu",
//             "Access-Control-Allow-Headers":"X-Requested-With"
        },
        success: function(json, textStatus, jqXHR) {
            console.info(jqXHR.getAllResponseHeaders());
            console.info("receive:"+json +"status:"+ textStatus);
            dafan_auth=jqXHR.getResponseHeader("dafan_auth");
            dafan_auth_timeout=jqXHR.getResponseHeader("dafan_auth_timeout");
            document.cookie = "dafan_auth="+dafan_auth+";expires="+new Date(dafan_auth_timeout*1000);
			json=eval('(' + json + ')');
            if(json==null || json.toString()==""){
                console.warn("data received is null");
                return false;
            }
			if (typeof json == 'object') {
				if (json.length == 0) {
					console.warn("data received is null object");
					return false;
				}
				//right obj
				if(json.ret_code==11){
					alert("用户名和密码不能为空");
					return false;
				}else if(json.ret_code==12){
					alert("用户名和密码不匹配");
					return false;
				}else if(json.ret_code==0){
					location.href="list.php";
					//alert("success");
				}
			} else {
				console.warn("data received can not convert into json object");
				return false;
			}
        },
		error : function(e) {
			alert("网络错误，请重试！");
			console.warn("error cause when submit data");
			return false;
		}
	});
}
</script>
<style>
.main {
	width:100%;
	
}
</style>
</head>
<body>
	<div class="main">
		<form>
			<input type="hidden" name="pubKey" id="pubKey"
				value="<?php echo $_SESSION['pubKey']; ?>" /> <input type="text"
				name="username" id="username" /> <br /> <input type="password"
				name="passwd" id="passwd" /> <br /> <input type="button"
				id="btLogin" value="login" />
		</form>
		</div>
</body>
</html>