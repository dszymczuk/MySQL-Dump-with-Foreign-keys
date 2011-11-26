<?php
/**
 * Dump MySQL databes with Foreign keys
 * 
 * FKMySQLDump class extends MySQL class written by Daniele Viganň
 * @link http://www.phpclasses.org/package/3498-PHP-Dump-a-MySQL-database-in-a-backup-file.html
 * 
 * @name FKMySQLDump
 * @author Damian Szymczuk - damian.szymczuk@gmail.com
 * @version 1.0 - 27/11/2011
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

require_once 'MySQLDump.php';

class FKMySQLDump extends MySQLDump{
    
    
    /**
    * Class constructor
    * @param string $db The database name
    * @param string $filepath The file where the dump will be written
    * @param boolean $compress It defines if the output file is compress (gzip) or not
    * @param boolean $hexValue It defines if the outup values are base-16 or not
    */
    function FKMYSQLDump($db = null, $filepath = 'dump.sql', $compress = false, $hexValue = false){
        parent::MYSQLDump($db,$filepath,$compress,$hexValue);
    }

}