<?php

require_once ('Request/BaseRequest.php');
class LinkRequest extends BaseRequest {
	public function __construct($postObj) {
		parent::__construct ( $postObj );
	}
	function __destruct() {
	}
	public function handle() {
		if ($this->msgType != parent::LINK) {
			return;
		}
	}
}

?>