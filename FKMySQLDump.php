<?php
/**
 * Dump MySQL databes with Foreign Keys
 * 
 * FKMySQLDump extends only doDump method to create dump all database with Foreign Keys.
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
     * @var type array
     */
    private $_fk_names = array();
    
    /**
     * Name of file to wrtie
     * @var type string
     */
    private $_fileName;
    
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
        $this->_fileName = parent::getOutputFile();
    }
    
    /**
    * Writes to file the selected database dump
    */
    function doDump() {
        parent::doDump();
        //echo $this->_fileName;
        $sql_file = file_get_contents($this->_fileName);
        $sql_file .= "--------------\n";
        $sql_file .= "--FOREIGN KEYS\n";
        $sql_file .= "--------------\n";
        $sql_file .= "SET FOREIGN_KEY_CHECKS = 0\n\n";
        $sql_file .= "SET FOREIGN_KEY_CHECKS = 1";

        /*$cur = file_get_contents($this->_fileName);
        $cur .= "Dopisuje linijkę\n";
        $cur .= "I kolejna linijkę\n";
        $cur .= "I jeszcze jedna linijkę\n";*/
        file_put_contents($this->_fileName,$sql_file);
    }
    
    
    /**
     * Gets Foreign Keys names to array
     */
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