<?php

require_once ('Request/BaseRequest.php');
class ImageRequest extends BaseRequest {
	private $PicUrl;
	public function __construct($postObj) {
		parent::__construct ( $postObj );
		$this->PicUrl = $this->postObj->PicUrl;
	}
	function __destruct() {
	}
	public function handle() {
		if ($this->msgType != parent::IMAGE) {
			return;
		}
		$value = Wechat::update ( $this->getFromUserName (), 4, "{PicUrl:" . $this->PicUrl . "}" );
		
		$value = 7 - $value;
		$str="";
		
		if($value==0){
			$str="感谢您的提交，我们会尽快审核并展示的";
		}else if ($value == 1) {
			$str="说点什么吧(至少5个字，否则被当做指令来处理)，重复提交相同类型信息会被覆盖，回复end结束编辑";
		} else {
			$str="方便发送你的位置给我嘛？位置信息只会用于帮助他人，重复提交相同类型信息会被覆盖,回复end结束编辑";
		}
		$textResponse = new TextResponse ();
		$textResponse->setContent ($str);
		$textResponse->setFromUserName ( $this->getToUserName () );
		$textResponse->setToUserName ( $this->getFromUserName () );
		$textResponse->send ();
	}
}

?>