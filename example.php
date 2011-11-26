<?php
//MySQL connection parameters
$dbhost = 'localhost';
$dbuser = 'root';
$dbpsw = 'qweasd';
$dbname = 'komunikator_test';

//Connects to mysql server
$connessione = @mysql_connect($dbhost,$dbuser,$dbpsw);
mysql_query("SET CHARSET utf8");
mysql_query("SET NAMES 'utf8' COLLATE 'utf8_bin'");

//Includes class
require_once('FKMySQLDump.php');

//Creates a new instance of FKMySQLDump: it exports a compressed and base-16 file
$dumper = new FKMySQLDump($dbname,'filename.sql',false,false);
$dumper->getForeignKeys();
$dumper->doDump();


//$dumper->getForeignKeys();

//$dumper->saveToFile();
//
//
//Dumps all the database with foreign keys
//$dumper->doDump();



/*$current = file_get_contents('filename.sql');
echo $current;*/
?>