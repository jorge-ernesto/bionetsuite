<?php

/*
 * -------------------------------------
 * Database.php
 * -------------------------------------
 */

class Database
{
    private static $Connection;
	private static $Connection1;
	private static $Connection2;
	private static $Connection3;
	private static $Connection4;
	
	public function __construct() {

		include_once BASE_ROOT.'application/config.php';
		include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';

		/*self::$Connection = ADONewConnection('odbc_oracle');
		self::$Connection->curmode = SQL_CUR_USE_DRIVER;
		self::$Connection->Connect(DB_NAME,DB_USER,DB_PASS);*/
		
		$this->open_Connection();
		$this->open_Connection1();
		$this->open_Connection2();
		$this->open_Connection3();
		$this->open_Connection4();
		 
	}
	
	/* INICIO ORACLE NETSUITE */
	public static function open_Connection(){
		if(!isset(self::$Connection)){
			try{

				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';

				self::$Connection = ADONewConnection('odbc_oracle');
				self::$Connection->curmode = SQL_CUR_USE_DRIVER;
				self::$Connection->Connect(DB_NAME,DB_USER,DB_PASS);

			} catch (Exception $ex) {
				print "ERROR: ".$ex->getMessage(). "<br>";
				die();
			}
		}
	}
	
	public static function close_Connection() {
		if(isset(self::$Connection)){
			self::$Connection = null;
		}
	}
	
	public static function get_Connection() {
		return self::$Connection;
	}
	
	
	/* INICIO MYSQL CANTIDADES LISTA MATERIALES */
	public static function open_Connection1(){
		if(!isset(self::$Connection1)){
			try{
	
				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';
	
				self::$Connection1 = ADONewConnection(DB_ENGINE_mysql_1);
				self::$Connection1->Connect(DB_HOST_mysql_1,DB_USER_mysql_1,DB_PASS_mysql_1,DB_NAME_mysql_1);
		
			} catch (Exception $ex) {
				print "ERROR: ".$ex->getMessage(). "<br>";
				die();
			}
		}
	}
	
	public static function close_Connection1() {
		if(isset(self::$Connection1)){
			self::$Connection1 = null;
		}
	}
	
	public static function get_Connection1() {
		return self::$Connection1;
	}
	
	/* INICIO MYSQL NUMERO DE ANALISIS */
	public static function open_Connection2(){
		if(!isset(self::$Connection2)){
			try{
	
				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';
	
				self::$Connection2 = ADONewConnection(DB_ENGINE_mysql_2);
				self::$Connection2->Connect(DB_HOST_mysql_2,DB_USER_mysql_2,DB_PASS_mysql_2,DB_NAME_mysql_2);
		
			} catch (Exception $ex) {
				print "ERROR: ".$ex->getMessage(). "<br>";
				die();
			}
		}
	}

	public static function close_Connection2() {
		if(isset(self::$Connection2)){
			self::$Connection2 = null;
		}
	}
	
	public static function get_Connection2() {
		return self::$Connection2;
	}
	
	/* INICIO MYSQL EDICION REVISION LISTA DE MATERIALES */
	public static function open_Connection3(){
		if(!isset(self::$Connection3)){
			try{
	
				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';
	
				self::$Connection3 = ADONewConnection(DB_ENGINE_mysql_3);
				self::$Connection3->Connect(DB_HOST_mysql_3,DB_USER_mysql_3,DB_PASS_mysql_3,DB_NAME_mysql_3);
		
			} catch (Exception $ex) {
				print "ERROR: ".$ex->getMessage(). "<br>";
				die();
			}
		}
	}

	public static function close_Connection3() {
		if(isset(self::$Connection3)){
			self::$Connection3 = null;
		}
	}
	
	public static function get_Connection3() {
		return self::$Connection3;
	}
	
	/* INICIO MYSQL CORRELATIVO ARTICULOS */
	public static function open_Connection4(){
		if(!isset(self::$Connection4)){
			try{
	
				include_once BASE_ROOT.'application/config.php';
				include_once BASE_ROOT.'libs/adodb5/adodb.inc.php';
	
				self::$Connection4 = ADONewConnection(DB_ENGINE_mysql_3);
				self::$Connection4->Connect(DB_HOST_mysql_4,DB_USER_mysql_4,DB_PASS_mysql_4,DB_NAME_mysql_4);
		
			} catch (Exception $ex) {
				print "ERROR: ".$ex->getMessage(). "<br>";
				die();
			}
		}
	}

	public static function close_Connection4() {
		if(isset(self::$Connection4)){
			self::$Connection4 = null;
		}
	}
	
	public static function get_Connection4() {
		return self::$Connection4;
	}

}