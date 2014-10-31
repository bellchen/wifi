<?php
class AuthSession {
	public function isAuth($newOne=true){
		$sessionAuth="";
		if(isset($_GET["sessionauth"])){
			$sessionAuth=$_GET["sessionauth"];
		}
		if (isset($_POST["sessionauth"])) {
			$sessionAuth=$_POST["sessionauth"];
		}
		if(isset($_COOKIE["sessionauth"])){
			$sessionAuth=$_COOKIE["sessionauth"];
		}
		if ($sessionAuth=="") {
			if(!$newOne){
				return false;
			}
		}
		
	}
}