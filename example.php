<?php
//MySQL connection parameters
$dbhost = 'localhost';
$dbuser = 'root';
$dbpsw = 'qweasd';
$dbname = 'komunikator_test';

//Connects to mysql server
$connessione = @mysql_connect($dbhost,$dbuser,$dbpsw);
mysql_query("SET CHARSET utf8");
mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");

//Includes class
require_once('FKMySQLDump.php');

//Creates a new instance of FKMySQLDump: it exports a compressed and base-16 file
/*$dumper = new FKMySQLDump($dbname,'filename.sql',false,false);
$dumper->getForeignKeys();
$dumper->dropKeys();
//$dumper->getForeignKeysRules();
$dumper->doFKDump();
*/

$dumper = new FKMySQLDump($dbname,'ostateczne.sql',false,false);
$dumper->doFKDump();
 
/*$file = file_get_contents("filenamedataFKu2.sql");
$file = str_replace("\\r", "", $file);
file_put_contents("filenamedataFKu2.sql", $file);*/



//$dumper->getDatabaseData();
/*$dumper->getForeignKeys();
$dumper->getForeignKeysRules();*/
//$dumper->saveToFile();
//
//
//Dumps all the database with foreign keys
//$dumper->doDump();



/*$current = file_get_contents('filename.sql');
echo $current;*/
?>