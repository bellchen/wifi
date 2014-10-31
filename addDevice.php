<html>
<head>
<title>添加设备</title>
<script src="./resources/js/jquery-1.8.2.js"></script>
<script src="./resources/js/jquery.cookie.js"></script>
<script>
var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();
$(document).ready(function() {
	$("#btSubmit").click(function(){
		submitMsg();
	});
});
function submitMsg(){
	var wifiName=$("#wifiName").val();
	$.ajax({
	    url: 'api/adddevice.php?wifiName='+wifiName+'&shopID='+$_GET["shopID"],
		dataType : 'text',
		type:"GET",
		headers: {
			"dafan_auth":$.cookie('dafan_auth')
        },
        success: function(json, textStatus, jqXHR) {
        	dafan_auth=jqXHR.getResponseHeader("dafan_auth");
            dafan_auth_timeout=jqXHR.getResponseHeader("dafan_auth_timeout");
            if(dafan_auth!=null){
	            console.info("set auth");
            	document.cookie = "dafan_auth="+dafan_auth+";expires="+new Date(dafan_auth_timeout*1000);
            }
            try{
            	json=eval('(' + json + ')');
	        }catch  (e)   {
	        	 console.info("resultJson:"+json);
	        	 return false;
	        }
            if(json==null || json.toString()==""){
                console.warn("data received is null");
                return false;
            }
			if (typeof json == 'object') {
				if (json.length == 0) {
					console.warn("data received is null object");
					return false;
				}
				if(json.ret_code==1){
					$.removeCookie('dafan_auth');
					location.href="login.php";
					return false;
				}
				if(json.ret_code==0){
					$("#wifiName").val("");
					var r=confirm("提交成功，是否继续添加？")
					if (r==false){
						location.href="devicelist.php?shopID="+$_GET["shopID"];
					}
				}else{
					alert(json.err_msg);
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
	//
}
</script>
</head>

<body>
	<div class="main">
		<form>
			<input type="text" name="wifiName" id="wifiName" /> <br />
			<input type="button" id="btSubmit" value="Submit" />
		</form>
	</div>
</body>
</html>
