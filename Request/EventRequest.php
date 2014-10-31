<?php

require_once (BASE_PATH.'/Request/BaseRequest.php');
require_once (BASE_PATH.'/Response/TextResponse.php');
class EventRequest extends BaseRequest {
	private $event;
	private $eventKey;
	public function __construct($postObj) {
		parent::__construct ( $postObj );
		$this->event=$this->postObj->Event;
		$this->eventKey=$this->postObj->EventKey;
	}
	function __destruct() {
	}
	private function echoMsg($msg){
		$textResponse = new TextResponse ();
		$textResponse->setContent ( $msg );
		$textResponse->setFromUserName ( $this->getToUserName () );
		$textResponse->setToUserName ( $this->getFromUserName () );
		$textResponse->send ();
	}
	public function handle() {
		if ($this->msgType != parent::EVENT) {
			return;
		}
		switch($this->event){
			case "subscribe":
				$this->echoMsg('欢迎订阅微硬件-生活好助手\n直接回复任意字母获取WiFi列表');
				break;
			case "unsubscribe":
				$this->echoMsg("unsubscribe");
				break;
			case "CLICK":
// 				//handle in TextRequest.php
				break;
			default:
				$this->echoMsg($this->event);
				break;
		}
		return;
	}

}

?>