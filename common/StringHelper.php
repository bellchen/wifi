<?php
class StringHelper {
	public static function generateString($length = 64) {
		$chars = array ();
		for($i = 0; $i <= 9; $i ++) {
			$chars [$i] = chr ( $i + 48 );
		}
		for($i = 10; $i <= 36; $i ++) {
			$chars [$i] = chr ( $i + 55 );
		}
		for($i = 36; $i <= 61; $i ++) {
			$chars [$i] = chr ( $i + 61 );
		}
		$result = "";
		for($i = 0; $i < $length; $i ++) {
			$result .= $chars [mt_rand ( 0, count ( $chars ) - 1 )];
		}
		return $result;
	}
	public static function object_to_array($obj){
		$arr = is_object($obj)? get_object_vars($obj) :$obj;
		foreach ($arr as $key => $val){
			$val=(is_array($val)) || is_object($val) ? self::object_to_array($val) :$val;
			$arr[$key] = $val;
		}
		return $arr;
		 
	}
	public static function generateToken($c=6){
		//返回6位整数
		srand((double)microtime()*1000000);//create a random number feed.
		$ychar="0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		$list=explode(",",$ychar);
		$authnum="";
		for($i=0;$i<$c;$i++){
			$randnum=rand(0,9); // 10+26;
			$authnum.=$list[$randnum];
		}
		return $authnum;
	}
}

?>