<?php
//MySQL connection parameters
$dbhost = 'localhost';
$dbuser = 'root';
$dbpsw = 'qweasd';
$dbname = 'komunikator';

//Connects to mysql server
$connessione = @mysql_connect($dbhost,$dbuser,$dbpsw);
mysql_query("SET CHARSET utf8");
mysql_query("SET NAMES 'utf8' COLLATE 'utf8_bin'");

//Includes class
require_once('FKMySQLDump.php');

//Creates a new instance of MySQLDump: it exports a compressed and base-16 file
$dumper = new FKMySQLDump($dbname,'filename.sql',false,false);

//Use this for plain text and not compressed file
//$dumper = new MySQLDump($dbname,'filename.sql',false,false);

//Dumps all the database
$dumper->doDump();
  
//Dumps all the database structure only (no data)
//$dumper->getDatabaseStructure();

//Dumps all the database data only (no structure)
//$dumper->getDatabaseData();

//Dumps "mytable" table structure only (no data)
//$dumper->getTableStructure('mytable');

//Dumps "mytable" table data only (no structure)
//$dumper->getTableData('mytable');
?>