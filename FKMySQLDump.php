<?php
/**
 * Dump MySQL databes with Foreign keys
 * 
 * FKMySQLDump extends only doDump method to create dump all database with foreign keys.
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
     * Database name
     * @var type string
     */
    private $_dbname;
    
    /**
     * Array of FK names
     * 
     * @var type array
     */
    private $_fk_names = array();
    
    /**
    * Class constructor
    * @param string $db The database name
    * @param string $filepath The file where the dump will be written
    * @param boolean $compress It defines if the output file is compress (gzip) or not
    * @param boolean $hexValue It defines if the outup values are base-16 or not
    */
    function FKMYSQLDump($db = null, $filepath = 'dump.sql', $compress = false, $hexValue = false){
        parent::MYSQLDump($db,$filepath,$compress,$hexValue);
        
        $this->_dbname = $db;
    }
    
    function doDump() {
        echo "Robię dumpa";
    }
    
    
    public function getForeignKeys() {
        $sql = "select * from information_schema.TABLE_CONSTRAINTS  
            where  CONSTRAINT_TYPE = 'foreign key' 
            and CONSTRAINT_SCHEMA ='{$this->_dbname}'";
            echo $sql;
        $result = mysql_query($sql);
        while($row = mysql_fetch_assoc($result)) {
            array_push($this->_fk_names, $row['CONSTRAINT_NAME']);
        }
        
        var_dump($this->_fk_names);
    }

}