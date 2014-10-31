<?php
interface  IBaseObj{
	public static function queryByIdentify($identify);
	public function setIdentifier($identify);
	public function setToken($token);
	public function setTimeOut($timeOut);
	public function getIdentifier();
	public function getToken();
	public function getTimeOut();
	public static function updateIdentify($baseObj);
}