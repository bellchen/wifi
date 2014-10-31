<?php

require_once ('Request/BaseRequest.php');
class LocationRequest extends BaseRequest {
	private $Location_X;
	private $Location_Y;
	private $Scale;
	private $Label;
	private $base_url="http://api.map.baidu.com/geocoder?output=json&key=fe6f2ea954e8b1e64d4e37dca6bf735d&location=";
	public function __construct($postObj) {
		parent::__construct ( $postObj );
		$this->Location_X=$this->postObj->Location_X;
		$this->Location_Y=$this->postObj->Location_Y;
		$this->Scale=$this->postObj->Scale;
		$this->Label=$this->postObj->Label;
	}
	function __destruct() {
	}
	public function handle() {
		if ($this->msgType != parent::LOCATION) {
			return;
		}
		$json=file_get_contents($this->base_url.$this->Location_X.",".$this->Location_Y);
		$data=json_decode($json, true);
		$result="-";
		if($data["status"]!="OK"){
			$result=404;
		}else{
			$result= $data["result"]["formatted_address"]."，附近：".$data["result"]["business"];
		}		
		$value = Wechat::update( $this->getFromUserName (), 2, "{localtion:".$result."}");
		$value = 7 - $value;
		$str="";
		if($value==0){
			$str="感谢您的提交，我们会尽快审核并展示的";
		}else if($value==1){
			$str="说点什么吧(至少5个字，否则被当做指令来处理)，重复提交相同类型信息会被覆盖，回复end结束编辑";
		}else {
			$str="点击菜单中的“+”发送一张图片给我吧,重复提交相同类型信息会被覆盖，回复end结束编辑";
		}
		$textResponse = new TextResponse ();
		$textResponse->setContent ($str);
		$textResponse->setFromUserName ( $this->getToUserName () );
		$textResponse->setToUserName ( $this->getFromUserName () );
		$textResponse->send ();
	}
	//http://api.map.baidu.com/geocoder?output=json&location=39.983424,116.322987&key=fe6f2ea954e8b1e64d4e37dca6bf735d
}
/*
{
    "status":"OK",
    "result":{
        "location":{
            "lng":116.322987,
            "lat":39.983424
        },
        "formatted_address":"北京市海淀区中关村大街27号1101-08室",
        "business":"人民大学,中关村,苏州街",
        "addressComponent":{
            "city":"北京市",
            "district":"海淀区",
            "province":"北京市",
            "street":"中关村大街",
            "street_number":"27号1101-08室"
        },
        "cityCode":131
    }
}



*/
?>