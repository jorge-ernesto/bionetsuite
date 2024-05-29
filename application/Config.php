<?php

/*
 * -------------------------------------
 *  Config.php
 * -------------------------------------
 */


define('BASE_URL', 'https://192.168.1.207:8080/bionetsuite/');
define('BASE_ROOT',$_SERVER['DOCUMENT_ROOT'].'/bionetsuite/');
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_METHOD', 'index');
define('DEFAULT_LAYOUT', 'default');

define("LAST_VERSION_SOURCE",strtotime("2022-09-04 09:24:11"));

/* Conexion envio de correo */
define('MAIL_APP_HOST','outlook.office365.com');
define('MAIL_APP_USER','notificaciones@biomont.com.pe');
define('MAIL_APP_PASSWORD','Notifi2020');

/* Conexion oracle */
/*define('DB_USER', 'jpena@biomont.com.pe');
define('DB_PASS', 'Pena140721');
define('DB_NAME', 'NetSuite');*/
/*define('DB_USER', 'fcastro@biomont.com.pe');
define('DB_PASS', 'Biomont2022#');*/
define('DB_USER', 'gcrisolo@biomont.com.pe');
define('DB_PASS', 'NetSuite01');
define('DB_NAME', 'NetSuite');

/* Conexion mysql cantidades Lista de Materiales */
define('DB_HOST_mysql_1', 'localhost');
define('DB_USER_mysql_1', 'root');
define('DB_PASS_mysql_1', '');
define('DB_NAME_mysql_1', 'bd_cant_gen');
define('DB_ENGINE_mysql_1','mysqli');

/* Conexion mysql Numero de Analisis */
define('DB_HOST_mysql_2', 'localhost');
define('DB_USER_mysql_2', 'root');
define('DB_PASS_mysql_2', '');
define('DB_NAME_mysql_2', 'bd_rec_num_analisis');
define('DB_ENGINE_mysql_2','mysqli');

/* Conexion mysql edicion revision lista de materiales */
define('DB_HOST_mysql_3', 'localhost');
define('DB_USER_mysql_3', 'root');
define('DB_PASS_mysql_3', '');
define('DB_NAME_mysql_3', 'bd_revision_lm');
define('DB_ENGINE_mysql_3','mysqli');

/* Conexion mysql correlativos articulos */
define('DB_HOST_mysql_4', 'localhost');
define('DB_USER_mysql_4', 'root');
define('DB_PASS_mysql_4', '');
define('DB_NAME_mysql_4', 'bd_articulos');
define('DB_ENGINE_mysql_4','mysqli');

/* Conexion sqlserver */
define('DB_HOST_mssql_5', 'SRV-SQL\SQLEXPRESS');
define('DB_USER_mssql_5', 'sa');
define('DB_PASS_mssql_5', 'Bi0m0nT1#S3r$2023');
define('DB_NAME_mssql_5', 'NETSUITE');
define('DB_ENGINE_mssql_5','mssqlnative');