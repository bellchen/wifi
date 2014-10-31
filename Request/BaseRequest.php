<?php

class BaseRequest {
	const TEXT="text";
	const IMAGE="image";
	const LOCATION="location";
	const LINK="link";
	const EVENT="event";
	protected $msgType;
	protected $fromUserName;
	protected $toUserName;
	protected $msgId;
	protected $postObj;
	function __construct($postObj) {
		$this->postObj =$postObj;
		$this->fromUsername = $this->postObj->FromUserName;
		$this->toUsername = $this->postObj->ToUserName;
		$this->msgId=$this->postObj->MsgId;
		$this->msgType=$this->postObj->MsgType;
	}
	function __destruct() {
	}
	
	protected function handle(){
		
	}
	
	/**
	 * @return the $msgId
	 */
	public final function getMsgId() {
		return $this->msgId;
	}

	/**
	 * @param field_type $msgId
	 */
	public final function setMsgId($msgId) {
		$this->msgId = $msgId;
	}	

	/**
	 * @return the $toUserName
	 */
	public final function getToUserName() {
		return  $this->postObj->ToUserName;
	}

	/**
	 * @param field_type $toUserName
	 */
	public final function setToUserName($toUserName) {
		$this->toUserName = $toUserName;
	}
	/**
	 * @return the $fromUserName
	 */
	public final function getFromUserName() {
		return $this->postObj->FromUserName;
	}

	/**
	 * @param field_type $fromUserName
	 */
	public final function setFromUserName($fromUserName) {
		$this->fromUserName = $fromUserName;
	}	
}

?>