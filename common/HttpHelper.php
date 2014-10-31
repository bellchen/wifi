<?php
define("RET_CODE", "ret_code");
define("ERR_MSG","err_msg");
define("RESULT","result");
class HttpHelper {
	function __construct() {
	}
	function __destruct() {
	}
	/**
	 * Respose A Http Request
	 *
	 * @param string $url
	 * @param array $post        	
	 * @param string $method        	
	 * @param bool $returnHeader        	
	 * @param string $cookie        	
	 * @param bool $bysocket        	
	 * @param string $ip        	
	 * @param integer $timeout        	
	 * @param bool $block        	
	 * @return string Response
	 */
	public static function httpRequest($url, $post = '', $method = 'GET', $limit = 0, $returnHeader = FALSE, $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
		$return = '';
		$matches = parse_url ( $url );
		
		! isset ( $matches ['host'] ) && $matches ['host'] = '';
		! isset ( $matches ['path'] ) && $matches ['path'] = '';
		! isset ( $matches ['query'] ) && $matches ['query'] = '';
		! isset ( $matches ['port'] ) && $matches ['port'] = '';
		
		$host = $matches ['host'];
		$path = $matches ['path'] ? $matches ['path'] . ($matches ['query'] ? '?' . $matches ['query'] : '') : '/';
		$port = ! empty ( $matches ['port'] ) ? $matches ['port'] : 80;
		
		if (strtolower ( $method ) == 'post') {
			$post = (is_array ( $post ) and ! empty ( $post )) ? http_build_query ( $post ) : $post;
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			// $out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: ' . strlen ( $post ) . "\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			// $out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		
		$fp = fsockopen ( ($ip ? $ip : $host), $port, $errno, $errstr, $timeout );
		
		if (! $fp)
			return '';
		else {
			$header = $content = '';
			
			stream_set_blocking ( $fp, $block );
			stream_set_timeout ( $fp, $timeout );
			fwrite ( $fp, $out );
			$status = stream_get_meta_data ( $fp );
			
			if (! $status ['timed_out']) { // 未超时
				while ( ! feof ( $fp ) ) {
					$header .= $h = fgets ( $fp );
					if ($h && ($h == "\r\n" || $h == "\n"))
						break;
				}
				
				$stop = false;
				while ( ! feof ( $fp ) && ! $stop ) {
					$data = fread ( $fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit) );
					$content .= $data;
					if ($limit) {
						$limit -= strlen ( $data );
						$stop = $limit <= 0;
					}
				}
			}
			fclose ( $fp );
			
			return $returnHeader ? array (
					$header,
					$content 
			) : $content;
		}
	}
	public static function post($domain, $port, $url, $post_data, $wait = true, $timeout = 30) {
		$errno = '';
		$errstr = '';
		// 要post的数据
		if (is_string ( $post_data ) == false) {
			$post_data = json_encode ( $post_data );
		}
		$length = strlen ( $post_data );
		// 创建socket连接
		$fp = fsockopen ( $domain, $port, $errno, $errstr, 10 ) or exit ( $errstr . "--->" . $errno );
		// 构造post请求的头
		$header = "POST " . "/" . $url . " HTTP/1.1\r\n";
		$header .= "Host:" . $domain . ":" . $port . "\r\n";
		$header .= "Content-Length: " . $length . "\r\n";
		$header .= "Connection: Close\r\n\r\n";
		// 添加post的字符串
		$header .= $post_data . "\r\n";
		// 发送post的数据
		fputs ( $fp, $header );
		if ($wait == false) {
			fclose ( $fp );
			return;
		}
		$inheader = 1;
		$result = "";
		$content="";
		while ( ! feof ( $fp ) ) {
			$line = fgets ( $fp, 1024 ); // 去除请求包的头只显示页面的返回数据
			$result .= $line;
			if ($inheader && ($line == "\n" || $line == "\r\n")) {
				$inheader = 0;
			}
			if ($inheader == 0) {
				$content.= $line;
			}
		}
		fclose ( $fp );
		$re=array();
		$re[]=$result;
		$re[]=$content;
		return $re;
	}
	public static function GoURL($url) {
		echo "<script language='javascript' type='text/javascript'>";
		echo "window.location.href='$url'";
		echo "</script>";
	}
	public static function alertBack($str) {
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('$str');";
		echo "history.go(-1);";
		echo "</script>";
	}
	public static function alertAndGoTo($str, $url) {
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('$str');";
		//echo "window.location.href='$url'";
		echo "</script>";
	}
	public static function echoJson($result=array(RET_CODE=>0,ERR_MSG=>"ok",RESULT=>array("status"=>0))){
		echo json_encode($result);
		exit(0);
	}
	public static function getParam($param,$default=-1,$order=array()){
		if (count($order)==0) {
			$order=array($_COOKIE,$_SESSION,$_SERVER,$_POST,$_GET);
		}
		foreach ($order as $o){
			if (!is_array($o)) {
				continue;
			}
			if (isset($o[$param])){
				return $o[$param];
			}
		}
		return $default;
	}
	
}

?>