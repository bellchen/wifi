<html>
<head>
<title>添加商家</title>
<script src="./resources/js/jquery-1.8.2.js"></script>
<script src="./resources/js/jquery.cookie.js"></script>
<script>
$(document).ready(function() {
	$("#btSubmit").click(function(){
		submitMsg();
	});
});
function submitMsg(){
	var shopName=$("#shopName").val();
	var shopOpenID=$("#shopOpenID").val();
	$.ajax({
	    url: 'api/addshop.php?name='+shopName+"&openID="+shopOpenID,
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
					$("#shopName").val("");
					$("#shopOpenID").val("");
					var r=confirm("提交成功，是否继续添加？")
					if (r==false){
						location.href="list.php";
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
}
</script>
</head>
<body>
	<div class="main">
		<form>
			<input type="text" name="shopName" id="shopName" /> <br /> <input
				type="text" name="shopOpenID" id="shopOpenID" /> <br />
			<input type="button" id="btSubmit" value="Submit" />
		</form>
	</div>
</body>
</html>
