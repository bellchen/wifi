<?php
header ( 'Content-type: text/html; charset=utf-8' );
define("MYSQLHOST","115.29.33.65:3306");
define("MYSQLUSER","admin");
define("MYSQLPWD","admin");
define("MYSQLDB","wifi");
// define ( "MYSQLHOST", "sqld.duapp.com:4050" );
// define ( "MYSQLUSER", "Z43BxmbS4blpEb3SNnhEojNh" );
// define ( "MYSQLPWD", "j2WdKAPxLrpyGGKtoaWgBDltrXU7ac6q" );
// define ( "MYSQLDB", "PrwzaTdsInVvSzSNdgwV" );

/* 至此连接已完全建立，就可对当前数据库进行相应的操作了 */
/*！！！注意，无法再通过本次连接调用mysql_select_db来切换到其它数据库了！！！*/
/* 需要再连接其它数据库，请再使用mysql_connect+mysql_select_db启动另一个连接*/
class AlertMySQL {
	private static $mysqlLink = false;
	public static function con() {
		if (AlertMySQL::$mysqlLink == false) {
			AlertMySQL::$mysqlLink = @mysql_connect ( MYSQLHOST, MYSQLUSER, MYSQLPWD, true );
			if (! AlertMySQL::$mysqlLink) {
				die ( "Connect Server Failed: " . mysql_error () );
			}
			/* 连接成功后立即调用mysql_select_db()选中需要连接的数据库 */
			if (! mysql_select_db ( MYSQLDB, AlertMySQL::$mysqlLink )) {
				die ( "Select Database Failed: " . mysql_error ( AlertMySQL::$mysqlLink ) );
			}
		}
		return AlertMySQL::$mysqlLink;
	}
}
?>