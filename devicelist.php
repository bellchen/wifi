<html>
<head>
<title>设备列表</title>
<link rel="stylesheet" href="./resources/css/jqpagination.css" />
<script src="./resources/js/jquery-1.8.2.js"></script>
<script src="./resources/js/jquery.jqpagination.min.js"></script>
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
	var allcount=0;
	var pageSize=10;
	var deviceList={};
	var shopID=$_GET["shopID"];
	$("#addDevice").click(function (){
		location.href="addDevice.php?shopID="+shopID;
	});
	ajaxRequest("api/devicecount.php?shopID="+shopID,false,"GET","",function(json){
		if(json!=false){
			//right obj
			if(json.ret_code==2){
				alert(json.err_msg);
				return false;
			}else if(json.ret_code==0){
				allcount=parseInt(json.result);
			}
		}
	});
	if(allcount>0){
		ajaxRequest("api/devicelist.php?shopID="+shopID,false,"POST","page="+0+"&pagesize="+pageSize,function(json){
			if(json!=false){
				//right obj
				if(json.ret_code==2){
					alert(json.err_msg);
					return false;
				}else if(json.ret_code==0){
					deviceList= json.result;
					reloadTable(deviceList);
				}
			}
		});
		
		if(allcount>pageSize){
			$('.pagination').jqPagination({
				link_string	: '/?page={page_number}',
				max_page	: Math.ceil(allcount/pageSize),
				paged		: function(page) {
					page=page-1;
					console.info('Requested page ' + page);
					ajaxRequest("api/devicelist.php?shopID="+shopID,false,"POST","page="+page+"&pagesize="+pageSize,function(json){
						if(json!=false){
							//right obj
							if(json.ret_code==2){
								console.info(json.err_msg+":"+json.result);
								return false;
							}else if(json.ret_code==0){
								shopList= json.result;
								reloadTable(deviceList);
							}
						}
					});
				}
			});
		}else{
			$('.pagination').hide();
		}
	}else{
		//empty tip to dispatch mp account
		$("#listTable").hide();
		$("body").append("<div>请先为这个商家添加设备</div>");
	}
	function reloadTable(deviceList){
		for ( var i = 0, l = deviceList.length; i < l; i++) {
			console.info(deviceList[i]["name"]);
			var shopItem=$('<tr><td>'
					+deviceList[i]["shopID"]
					+'</td><td>'
					+deviceList[i]["wifiName"]
					+'</td><td><a href="editWifi.php?shopID='
					+deviceList[i]["wifiID"]
					+'">修改设备</a>|<a href="delWifi.php?shopID='
					+deviceList[i]["wifiID"]
					+'">删除设备</a></td></tr>');
			$("#Maintbody").append(shopItem);
		}
	}
	function ajaxRequest(requrl,isasync,method,sendData,cb){
		$.ajax({
		    url: requrl,
			dataType : 'text',
			async:isasync,
			type:method,
			headers: {
	            "dafan_auth":$.cookie('dafan_auth')
	        },
	        data:sendData,
	        success: function(json, textStatus, jqXHR) {
	        	dafan_auth=jqXHR.getResponseHeader("dafan_auth");
	            dafan_auth_timeout=jqXHR.getResponseHeader("dafan_auth_timeout");
	            if(dafan_auth!=null){
		            console.info("set auth");
	            	document.cookie = "dafan_auth="+dafan_auth+";expires="+new Date(dafan_auth_timeout*1000);
	            }
	            console.info(json);
	            try{
	            	json=eval('(' + json + ')');
		        }catch  (e)   {
		        	 console.info("resultJson:"+json);
		        	 cb(false);
		        	 return false;
		        }
	            if(json==null || json.toString()==""){
	                console.warn("data received is null");
	                cb(false);
	                return false;
	            }
				if (typeof json == 'object') {
					if (json.length == 0) {
						console.warn("data received is null object");
						cb(false);
						return false;
					}
					if(json.ret_code==1){
						$.removeCookie('dafan_auth');
						location.href="login.php";
						cb(false);
						return false;
					}
					cb(json);
				} else {
					console.warn("data received can not convert into json object");
					cb(false);
					return false;
				}
	        },
			error : function(e) {
				cb(false);
				return false;
			}
		});
	}
});
</script>
<style>
</style>
</head>
<body>
	<table id="listTable">
		<thead>
			<tr>
				<td>shopID</td>
				<td>wifiName</td>
				<td>Operation</td>
			</tr>
		</thead>
		<tbody id="Maintbody">
			<!-- <tr>
				<td>a</td>
				<td>b</td>
				<td>c</td>
				<td><a href="addDevice.php?shopID=d">添加设备</a>｜<a href="devicelist.php?shopID=d">查看设备</a></td>
			</tr> -->
		</tbody>
		<tfoot>
			<tr>
				<td>
					<div class="pagination">
						<a href="#" class="first" data-action="first">&laquo;</a> <a
							href="#" class="previous" data-action="previous">&lsaquo;</a> <input
							type="text" readonly="readonly" data-max-page="40" /> <a href="#"
							class="next" data-action="next">&rsaquo;</a> <a href="#"
							class="last" data-action="last">&raquo;</a>
					</div>
				</td>
			</tr>
		</tfoot>

	</table>
	<div id="addDevice" style="">insert by myself</div>
</body>
</html>
