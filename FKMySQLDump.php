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
 * @link http://dszymczuk.pl
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
    * @param boolean $hexValue It defines if the output values are base-16 or not
    */
    function __construct($db = null, $filepath = 'dump.sql', $compress = false, $hexValue = false){
        parent::__construct($db,$filepath,$compress,$hexValue);
        
        $this->_dbname = $db;
        $this->_fileName = parent::getOutputFile();
    }
    
    /**
     * Writes to file the selected database dump
     * 
     * @return bool
     */
    function doFKDump($params = array()) {
        parent::doDumpWithoutClosing($params);
        $this->getForeignKeys();
        $sql_file  = "-- ------------\n";
        $sql_file .= "-- FOREIGN KEYS\n";
        $sql_file .= "-- ------------\n";
        $sql_file .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        $sql_file .= $this->getForeignKeysRules();
        $sql_file .= "SET FOREIGN_KEY_CHECKS = 1;\n\n";
        $this->saveToFile($this->file, $sql_file);
		$this->closeFile($this->file);
		return true;
    }
    
    
    /**
     * Gets Foreign Keys names to array
     *
     * Select CONSTRAINT_NAME from Information Schema
     *
     * @return void
     */
    public function getForeignKeys() {
        $sql = "select * from information_schema.TABLE_CONSTRAINTS
            where  CONSTRAINT_TYPE = 'foreign key' 
            and CONSTRAINT_SCHEMA ='{$this->_dbname}'";
        $result = mysql_query($sql);
        while($row = mysql_fetch_assoc($result)) {
            array_push($this->_fk_names, $row['CONSTRAINT_NAME']);
        }
    }

    /**
     * Return SQL command with foreign keys as string
     *
     * Function select some columns from Information Schema and write informations about foreign keys to string.
     *
     * @return string
     */
    public function getForeignKeysRules(){
        $FK_to_sql_file = "";
        foreach($this->_fk_names as $fk_name){

            $sql = "select KEY_COLUMN_USAGE.TABLE_NAME, KEY_COLUMN_USAGE.CONSTRAINT_NAME, COLUMN_NAME,
                    REFERENCED_COLUMN_NAME, KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME, UPDATE_RULE, DELETE_RULE
                    from information_schema.KEY_COLUMN_USAGE, information_schema.REFERENTIAL_CONSTRAINTS
                    where KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA = '{$this->_dbname}'
                    and KEY_COLUMN_USAGE.CONSTRAINT_NAME = '{$fk_name}'
                    and KEY_COLUMN_USAGE.CONSTRAINT_NAME = REFERENTIAL_CONSTRAINTS.CONSTRAINT_NAME
                    and KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA = REFERENTIAL_CONSTRAINTS.CONSTRAINT_SCHEMA";

            $result = mysql_query($sql);

            while($row = mysql_fetch_assoc($result)){
                $FK_to_sql_file .= "ALTER TABLE `".$row['TABLE_NAME']."` ADD CONSTRAINT `".$row['CONSTRAINT_NAME']."` FOREIGN KEY (`".$row['COLUMN_NAME']."`) REFERENCES `".$row['REFERENCED_TABLE_NAME']."` (`".$row['REFERENCED_COLUMN_NAME']."`) ON DELETE {$row['DELETE_RULE']} ON UPDATE {$row['UPDATE_RULE']};";
                $FK_to_sql_file .= "\r\n\r\n";
            }
        }
        return $FK_to_sql_file;
    }

}
