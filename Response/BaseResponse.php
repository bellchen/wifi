<?php
class BaseResponse {
	const TEXT="text";
	const MUSIC="music";
	const NEWS="news";
	private $toUserName;
	private $fromUserName;
	private $msgType;
	private $funcFlag;
	protected $tpl;
	function __construct() {
	}
	function __destruct() {
	}
	public function send(){
		//TODO update user info
	}
	/**
	 * @return the $toUserName
	 */
	public function getToUserName() {
		return $this->toUserName;
	}

	/**
	 * @return the $fromUserName
	 */
	public function getFromUserName() {
		return $this->fromUserName;
	}

	/**
	 * @return the $msgType
	 */
	public function getMsgType() {
		return $this->msgType;
	}

	/**
	 * @return the $funcFlag
	 */
	public function getFuncFlag() {
		return $this->funcFlag;
	}

	/**
	 * @param field_type $toUserName
	 */
	public function setToUserName($toUserName) {
		$this->toUserName = $toUserName;
	}

	/**
	 * @param field_type $fromUserName
	 */
	public function setFromUserName($fromUserName) {
		$this->fromUserName = $fromUserName;
	}

	/**
	 * @param field_type $msgType
	 */
	public function setMsgType($msgType) {
		$this->msgType = $msgType;
	}

	/**
	 * @param field_type $funcFlag
	 */
	public function setFuncFlag($funcFlag) {
		$this->funcFlag = $funcFlag;
	}	
}

?>