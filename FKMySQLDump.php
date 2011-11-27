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
    function doFKDump() {
        parent::doDump();
        $this->getForeignKeys();
        $sql_file = file_get_contents($this->_fileName);
        $sql_file .= "-- ------------\n";
        $sql_file .= "-- FOREIGN KEYS\n";
        $sql_file .= "-- ------------\n";
        $sql_file .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        $sql_file .= $this->getForeignKeysRules();
        $sql_file .= "SET FOREIGN_KEY_CHECKS = 1;\n\n";
        file_put_contents($this->_fileName,$sql_file);
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

    public function getForeignKeysRules(){
        /*$fk_name = $this->_fk_names[0];
        echo $fk_name;*/
        $FK_to_sql_file = "";
        foreach($this->_fk_names as $fk_name){
            //echo $fk_name."<br />";

            /*$sql = "select * from information_schema.REFERENTIAL_CONSTRAINTS
                    where CONSTRAINT_SCHEMA = '{$this->_dbname}'
                    and CONSTRAINT_NAME = '{$fk_name}'";*/

            $sql = "select KEY_COLUMN_USAGE.TABLE_NAME, KEY_COLUMN_USAGE.CONSTRAINT_NAME, COLUMN_NAME,
                    REFERENCED_COLUMN_NAME, KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME, UPDATE_RULE, DELETE_RULE
                    from information_schema.KEY_COLUMN_USAGE, information_schema.REFERENTIAL_CONSTRAINTS
                    where KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA = '{$this->_dbname}'
                    and KEY_COLUMN_USAGE.CONSTRAINT_NAME = '{$fk_name}'
                    and KEY_COLUMN_USAGE.CONSTRAINT_NAME = REFERENTIAL_CONSTRAINTS.CONSTRAINT_NAME
                    and KEY_COLUMN_USAGE.CONSTRAINT_SCHEMA = REFERENTIAL_CONSTRAINTS.CONSTRAINT_SCHEMA";

            $result = mysql_query($sql);

            while($row = mysql_fetch_assoc($result)){
                //echo $row['CONSTRAINT_NAME']."<br />";
                $FK_to_sql_file .= "ALTER TABLE `".$row['TABLE_NAME']."` ADD CONSTRAINT `".$row['CONSTRAINT_NAME']."` FOREIGN KEY (`".$row['COLUMN_NAME']."`) REFERENCES `".$row['REFERENCED_TABLE_NAME']."` (`".$row['REFERENCED_COLUMN_NAME']."`) ON DELETE {$row['DELETE_RULE']} ON UPDATE {$row['UPDATE_RULE']};";
                //$FK_to_sql_file .= "<br/><br/>";




                $FK_to_sql_file .= "\r\n\r\n";

                //echo "FK name: ".$fk_name." Table name: ".$row['TABLE_NAME']." UR: ".$row['UPDATE_RULE']."<br />";
                //$FK_to_sql_file = "a";
            }
        }
        return $FK_to_sql_file;
    }

    /*public function dropKeys()
    {
        $sql = "show TABLES from {$this->_dbname}";
        $result = mysql_query($sql);
        while($row = mysql_fetch_assoc($result)) {

            //$sql_index = "show index from `".$row['Tables_in_'.$this->_dbname]."`";

            echo "Nazwa tabelki to: ".$row['Tables_in_'.$this->_dbname]."<br/>";
            $sql_index = "show index from `".$row['Tables_in_'.$this->_dbname]."` where Key_name != 'primary'
                        and Key_name not like '%unique' and Key_name != 'Full text' ";
            $result_index = mysql_query($sql_index);
            while($row_index = mysql_fetch_assoc($result_index)){
                echo $row_index['Key_name']."<br />";
            }

            //echo $row['Tables_in_'.$this->_dbname]."<br />";
        }
    }*/

}