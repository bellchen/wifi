<?php

require_once ('Response/BaseResponse.php');
class TextResponse extends BaseResponse {
	
	private $content;
	
	public function __construct() {
		parent::__construct ();
		$this->tpl='<xml>
<ToUserName>%s</ToUserName>
<FromUserName>%s</FromUserName>
<CreateTime>%d</CreateTime>
<MsgType>text</MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0</FuncFlag>
</xml>';
	}
	public function send(){
		parent::send();
		$resultStr = sprintf($this->tpl, $this->getToUserName(), $this->getFromUserName(), time(), $this->getContent());
		echo $resultStr;
		exit(0);
	}
	function __destruct() {
	}
	/**
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param field_type $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

}

?>