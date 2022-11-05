<?php
/**
* Dump MySQL database
*
* Here is an inline example:
* <code>
* $connection = @mysql_connect($dbhost,$dbuser,$dbpsw);
* $dumper = new MySQLDump($dbname,'filename.sql',false,false);
* $dumper->doDump();
* </code>
*
* Special thanks to:
* - Andrea Ingaglio <andrea@coders4fun.com> helping in development of all class code
* - Dylan Pugh for precious advices halfing the size of the output file and for helping in debug
*
* @name    MySQLDump
* @author  Daniele Viganň - CreativeFactory.it <daniele.vigano@creativefactory.it>
*          Daniel Marschall - www.daniel-marschall.de (continued work in 2022)
* @version 3.00 - 5 November 2022
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

class MySQLDump {
	/**
	* @access private
	*/
	protected $database = null;

	/**
	* @access private
	*/
	protected $compress = false;

	/**
	* @access private
	*/
	protected $hexValue = false;

  	/**
	* The output filename
	* @access private
	*/
	protected $filename = null;

	/**
	* The pointer of the output file
	* @access private
	*/
	protected $file = null;

	/**
	* @access private
	*/
	protected  $isWritten = false;

	/**
	* Class constructor
	* @param string $db The database name
	* @param string $filepath The file where the dump will be written
	* @param boolean $compress It defines if the output file is compress (gzip) or not
	* @param boolean $hexValue It defines if the outup values are base-16 or not
	*/
	function __construct($db = null, $filepath = 'dump.sql', $compress = false, $hexValue = false){
		$this->compress = $compress;
		$this->hexValue = $hexValue;
		if (!$this->setOutputFile($filepath)) throw new Exception("Set output file failed");
		if (!$this->setDatabase($db)) throw new Exception("Set database failed");
	}

	/**
	* Sets the database to work on
	* @param string $db The database name
	* @return boolean
	*/
	public function setDatabase($db){
		$this->database = $db;
		if ( !@mysql_select_db($this->database) )
			return false;
		return true;
	}

	/**
	* Returns the database where the class is working on
	* @return string
	*/
	public function getDatabase(){
		return $this->database;
	}

	/**
	* Sets the output file type (It can be made only if the file hasn't been already written)
	* @param boolean $compress If it's true, the output file will be compressed
	* @return boolean
	*/
	public function setCompress($compress){
		if ( $this->isWritten )
			return false;
		$this->compress = $compress;
		$this->openFile($this->filename);
		return true;
	}

	/**
	* Returns if the output file is or not compressed
	* @return boolean
	*/
	public function getCompress(){
		return $this->compress;
	}

	/**
	* Sets the output file
	* @param string $filepath The file where the dump will be written
	* @return resource|false
	*/
	public function setOutputFile($filepath){
		if ( $this->isWritten )
			return false;
		$this->filename = $filepath;
		$this->file = $this->openFile($this->filename);
		return $this->file;
	}

	/**
	* Returns the output filename
	* @return string
	*/
	public function getOutputFile(){
		return $this->filename;
	}

	/**
	* Writes to file the $table's structure
	* @param string $table The table name
	* @return boolean
	*/
	protected function getTableStructure($table){
		if ( !$this->setDatabase($this->database) )
			return false;
		// Structure Header
		$structure = "-- \n";
		$structure .= "-- Table structure for table `{$table}` \n";
		$structure .= "-- \n\n";
		// Dump Structure
		$structure .= 'DROP TABLE IF EXISTS `'.$table.'`;'."\n";
		$structure .= "CREATE TABLE `".$table."` (\n";
		$records = @mysql_query('SHOW FIELDS FROM `'.$table.'`');
		if ( @mysql_num_rows($records) == 0 )
			return false;
		while ( $record = mysql_fetch_assoc($records) ) {
			$null = ' NULL';
			$structure .= '`'.$record['Field'].'` '.$record['Type'];
			if ( @strcmp($record['Null'],'YES') != 0 ){
				$structure .= ' NOT NULL';
				$null = '';
			}

			if ( !empty($record['Default']) || @strcmp($record['Null'],'YES') == 0)
				$structure .= $null.' DEFAULT '.(is_null($record['Default']) ? 'NULL' : (($record['Default'] == 'CURRENT_TIMESTAMP') ? "{$record['Default']}" : "'{$record['Default']}'"));
			if ( !empty($record['Extra']) )
				$structure .= ' '.$record['Extra'];
			$structure .= ",\n";
		}
		$structure = @preg_replace("@,\n$@", "", $structure);

		// Save all Column Indexes
		$structure .= $this->getSqlKeysTable($table);
		$structure .= "\n)";

		//Save table engine
		$records = @mysql_query("SHOW TABLE STATUS LIKE '".$table."'");
		// echo $query; - ???
		if ( $record = @mysql_fetch_assoc($records) ) {
			if ( !empty($record['Engine']) )
				$structure .= ' ENGINE='.$record['Engine'];
			if ( !empty($record['Auto_increment']) )
				$structure .= ' AUTO_INCREMENT='.$record['Auto_increment'];
		}

		$structure .= ";\n\n-- --------------------------------------------------------\n\n";
		$this->saveToFile($this->file,$structure);
		return true;
	}

	/**
	* Writes to file the $table's data
	* @param string $table The table name
	* @param boolean $hexValue It defines if the output is base 16 or not
	* @return boolean
	*/
	protected function getTableData($table,$hexValue = true) {
		if ( !$this->setDatabase($this->database) )
			return false;
		// Header
		$data = "-- \n";
		$data .= "-- Dumping data for table `$table` \n";
		$data .= "-- \n\n";

		$records = mysql_query('SHOW FIELDS FROM `'.$table.'`');
		$num_fields = @mysql_num_rows($records);
		if ( $num_fields == 0 )
			return false;
		// Field names
		$selectStatement = "SELECT ";
		$insertStatement = "INSERT INTO `$table` (";
		$hexField = array();
		for ($x = 0; $x < $num_fields; $x++) {
			$record = @mysql_fetch_assoc($records);
			if ( ($hexValue) && ($this->isTextValue($record['Type'])) ) {
				$selectStatement .= 'HEX(`'.$record['Field'].'`)';
				$hexField [$x] = true;
			}
			else
				$selectStatement .= '`'.$record['Field'].'`';
			$insertStatement .= '`'.$record['Field'].'`';
			$insertStatement .= ", ";
			$selectStatement .= ", ";
		}
		$insertStatement = @substr($insertStatement,0,-2).') VALUES';
		$selectStatement = @substr($selectStatement,0,-2).' FROM `'.$table.'`';

		$records = @mysql_query($selectStatement);
		$num_rows = @mysql_num_rows($records);
		$num_fields = @mysql_num_fields($records);
		// Dump data
		if ( $num_rows > 0 ) {
			$data .= $insertStatement;
			for ($i = 0; $i < $num_rows; $i++) {
				$record = @mysql_fetch_assoc($records);
				$data .= ' (';
				for ($j = 0; $j < $num_fields; $j++) {
					$field_name = @mysql_field_name($records, $j);
					if ( isset($hexField[$j]) && $hexField[$j] && (@strlen($record[$field_name]) > 0) )
						$data .= "0x".$record[$field_name];
					else if ( is_null($record[$field_name]) )
						$data .= "NULL";
					else
						$data .= "'".@str_replace('\"','"',@mysql_escape_string($record[$field_name]))."'";
					$data .= ',';
				}
				$data = @substr($data,0,-1).")";

				//if data in greater than 127KB save and add new INSERT
				if (strlen($data) > 130000) {
					$data .= ";\n";
					$this->saveToFile($this->file,$data);
					$data = $insertStatement;
				}else{
				    $data .= ( $i < ($num_rows-1) ) ? ',' : ';';
				    $data .= "\n";
				}
			}
			$data .= "\n-- --------------------------------------------------------\n\n";
			$this->saveToFile($this->file,$data);
		}
		return true;
	}

	/**
	* Writes to file all the selected database tables structure
	* @return boolean
	*/
	protected function getDatabaseStructure(){
		$records = @mysql_query('SHOW TABLES');
		if ( @mysql_num_rows($records) == 0 )
			return false;
		$structure = '';
		while ( $record = @mysql_fetch_row($records) ) {
			$structure .= $this->getTableStructure($record[0]);
		}
		return true;
	}

	/**
	* Writes to file all the selected database tables data
	* @param boolean $hexValue It defines if the output is base-16 or not
	* @return boolean
	*/
	protected function getDatabaseData($hexValue = true){
		$records = @mysql_query('SHOW TABLES');
		if ( @mysql_num_rows($records) == 0 )
			return false;
		while ( $record = @mysql_fetch_row($records) ) {
			$this->getTableData($record[0],$hexValue);
		}
		return true;
	}

	/**
	* Writes to file the selected database dump
	* @return boolean
	*/
	public function doDump($params = array(), $close_file = true) {
		$ok = $this->doDumpWithoutClosing($params);
		if ($close_file) $this->closeFile($this->file);
		return $ok;
	}

	/**
	* Writes to file the selected database dump
	* @return boolean
	*/
	protected function doDumpWithoutClosing($params = array()) {
		$this->saveToFile($this->file,"SET FOREIGN_KEY_CHECKS = 0;\n\n");
		if (!isset($params['skip_structure']))
			$this->getDatabaseStructure();
		if (!isset($params['skip_data']))
			$this->getDatabaseData($this->hexValue);
		$this->saveToFile($this->file,"SET FOREIGN_KEY_CHECKS = 1;\n\n");
		return true;
	}

	/**
	* @deprecated Look at the doDump() method
	* @return boolean
	*/
	public function writeDump($filename) {
		if ( !$this->setOutputFile($filename) )
			return false;
		$this->doDump();
		$this->closeFile($this->file);
		return true;
	}

	/**
	* @access private
	* @return string|false
	*/
	protected function getSqlKeysTable ($table) {
		$primary = "";
		$unique = array();
		$index = array();
		$fulltext = array();
		$results = mysql_query("SHOW KEYS FROM `{$table}`");
		if ( @mysql_num_rows($results) == 0 )
			return false;
		while($row = mysql_fetch_object($results)) {
			if (($row->Key_name == 'PRIMARY') && ($row->Index_type == 'BTREE')) {
				if ( $primary == "" )
					$primary = "  PRIMARY KEY  (`{$row->Column_name}`";
				else
					$primary .= ", `{$row->Column_name}`";
			}
			if (($row->Key_name != 'PRIMARY') && ($row->Non_unique == '0') && ($row->Index_type == 'BTREE')) {
				if (!isset($unique[$row->Key_name]))
					$unique[$row->Key_name] = "  UNIQUE KEY `{$row->Key_name}` (`{$row->Column_name}`";
				else
					$unique[$row->Key_name] .= ", `{$row->Column_name}`";
			}
			if (($row->Key_name != 'PRIMARY') && ($row->Non_unique == '1') && ($row->Index_type == 'BTREE')) {
				if (!isset($index[$row->Key_name]))
					$index[$row->Key_name] = "  KEY `{$row->Key_name}` (`{$row->Column_name}`";
				else
					$index[$row->Key_name] .= ", `{$row->Column_name}`";
			}
			if (($row->Key_name != 'PRIMARY') && ($row->Non_unique == '1') && ($row->Index_type == 'FULLTEXT')) {
				if (!isset($fulltext[$row->Key_name]))
					$fulltext[$row->Key_name] = "  FULLTEXT `{$row->Key_name}` (`{$row->Column_name}`";
				else
					$fulltext[$row->Key_name] .= ", `{$row->Column_name}`";
			}
		}
		$sqlKeyStatement = '';
		// generate primary, unique, key and fulltext
		if ( $primary != "" ) {
			$sqlKeyStatement .= ",\n";
			$primary .= ")";
			$sqlKeyStatement .= $primary;
		}
		foreach ($unique as $keyName => $keyDef) {
			$sqlKeyStatement .= ",\n";
			$keyDef .= ")";
			$sqlKeyStatement .= $keyDef;

		}
		foreach ($index as $keyName => $keyDef) {
			$sqlKeyStatement .= ",\n";
			$keyDef .= ")";
			$sqlKeyStatement .= $keyDef;
		}
		foreach ($fulltext as $keyName => $keyDef) {
			$sqlKeyStatement .= ",\n";
			$keyDef .= ")";
			$sqlKeyStatement .= $keyDef;
		}
		return $sqlKeyStatement;
	}

	/**
	* @access private
	* @return boolean
	*/
	protected function isTextValue($field_type) {
		switch ($field_type) {
			case "tinytext":
			case "text":
			case "mediumtext":
			case "longtext":
			case "binary":
			case "varbinary":
			case "tinyblob":
			case "blob":
			case "mediumblob":
			case "longblob":
				return true;
			default:
				return false;
		}
	}

	/**
	* @access private
	* @return resource
	*/
	protected function openFile($filename) {
		$file = false;
		if ( $this->compress )
			$file = @gzopen($filename, "w9");
		else
			$file = @fopen($filename, "w");
		if ($file === false) throw new Exception("Reading '$filename' failed");
		return $file;
	}

	/**
	* @access private
	* @return void
	*/
	protected function saveToFile($file, $data) {
		if ( $this->compress )
			$ok = @gzwrite($file, $data) !== false;
		else
			$ok = @fwrite($file, $data) !== false;
		if (!$ok) throw new Exception("Write to '$file' failed");
		$this->isWritten = true;
	}

	/**
	* @access private
	* @return void
	*/
	protected function closeFile($file) {
		if ( $this->compress )
			@gzclose($file);
		else
			@fclose($file);
	}
}
