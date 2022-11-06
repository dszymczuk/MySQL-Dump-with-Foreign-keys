<?php

// Original file here:
// https://github.com/danielmarschall/prepend/blob/master/php_auto_pre/001-mysql_replacement.php

// TODO: test everything
// TODO: return values?
// TODO: check if we matched all stuff mentioned here: https://www.phpclasses.org/blog/package/9199/post/3-Smoothly-Migrate-your-PHP-Code-using-the-Old-MySQL-extension-to-MySQLi.html

$vts_mysqli = null;

// Liefert die Anzahl betroffener Datensätze einer vorhergehenden MySQL Operation
function mysql_affected_rows($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_affected_rows(). No valid connection to server.");

	return $li->affected_rows;
}

// Liefert den Namen des Zeichensatzes
function mysql_client_encoding($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_client_encoding(). No valid connection to server.");

	return $li->character_set_name();
}

// Schließt eine Verbindung zu MySQL
function mysql_close($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_close(). No valid connection to server.");

	return $li->close();
}

// Öffnet eine Verbindung zu einem MySQL-Server
function mysql_connect($server=null, $username=null, $password=null, $new_link=false, $client_flags=0) {
	global $vts_mysqli;
        $ary = explode(':', $server);
	$host = $ary[0];
	$ini_port = ini_get("mysqli.default_port");
	$port = isset($ary[1]) ? (int)$ary[1] : ($ini_port ? (int)$ini_port : 3306);
	if (is_null($server)) $port = ini_get("mysqli.default_host");
	if (is_null($username)) $port = ini_get("mysqli.default_user");
	if (is_null($password)) $port = ini_get("mysqli.default_password");
	$vts_mysqli = new mysqli($host, $username, $password, /*dbname*/'', $port, ini_get("mysqli.default_socket"));
	return (empty($vts_mysqli->connect_error) && ($vts_mysqli->connect_errno == 0)) ? $vts_mysqli : false;
}

// Anlegen einer MySQL-Datenbank
function mysql_create_db($database_name, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_create_db(). No valid connection to server.");

	return mysql_query("CREATE DATABASE `$database_name`", $li) !== false;
}

// Bewegt den internen Ergebnis-Zeiger
function mysql_data_seek($result, $row_number) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_data_seek() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->data_seek($row_number) !== false;
}

// Liefert Schema Namen vom Aufruf von mysql_list_dbs
function mysql_db_name($result, $row, $field=NULL) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_db_name() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	$result->data_seek($row);
	return mysql_fetch_array($result)[is_null($field) ? 0 : $field];
}

// Selektiert ein Schema und führt in ihm Anfrage aus
function mysql_db_query($database, $query, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_db_query(). No valid connection to server.");

	mysql_select_db($database, $li);
	return mysql_query($query, $li);
	// Note: The mysql_*() implementation defines, that we will not jump back to our original DB
}

// Löschen eines Schemas
function mysql_drop_db($database_name, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_drop_db(). No valid connection to server.");

	return mysql_query("DROP DATABASE `$database_name`", $li) !== false;
}

// Liefert die Nummer einer Fehlermeldung einer zuvor ausgeführten MySQL Operation
function mysql_errno($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_errno(). No valid connection to server.");

	return !empty($li->connect_errno) ? $li->connect_errno : $li->errno;
}

// Liefert den Fehlertext der zuvor ausgeführten MySQL Operation
function mysql_error($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_error(). No valid connection to server.");

	return !empty($li->connect_error) ? $li->connect_error : $li->error;
}

// Maskiert einen String zur Benutzung in einer MySQL Abfrage
function mysql_escape_string($unescaped_string) {
	global $vts_mysqli;
	return $vts_mysqli->real_escape_string($unescaped_string);
}

// Liefert einen Datensatz als assoziatives Array, als numerisches Array oder beides
define('MYSQL_ASSOC', MYSQLI_ASSOC);
define('MYSQL_NUM',   MYSQLI_NUM);
define('MYSQL_BOTH',  MYSQLI_BOTH);
function mysql_fetch_array($result, $result_type=MYSQL_BOTH) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_fetch_array() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_array($result_type);
}

// Liefert einen Datensatz als assoziatives Array
function mysql_fetch_assoc($result) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_fetch_assoc() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_assoc();
}

// Liefert ein Objekt mit Feldinformationen aus einem Anfrageergebnis
function mysql_fetch_field($result, $field_offset=0) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_fetch_field() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_field();
}

// Liefert die Länge eines jeden Feldes in einem Ergebnis
function mysql_fetch_lengths($result) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_fetch_lengths() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->lengths;
}

// Liefert eine Ergebniszeile als Objekt
function mysql_fetch_object($result, $class_name="stdClass", $params=null) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_fetch_object() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	if ($params) {
		return $result->fetch_object($class_name, $params);
	} else {
		return $result->fetch_object($class_name);
	}
}

// Liefert einen Datensatz als indiziertes Array
function mysql_fetch_row($result) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_fetch_row() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_row();
}

// Liefert die Flags des spezifizierten Feldes in einem Anfrageergebnis
function mysql_field_flags($result, $field_offset) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_field_flags() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_field_direct($field_offset)->flags;
}

// Liefert die Länge des angegebenen Feldes
function mysql_field_len($result, $field_offset) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_field_len() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_field_direct($field_offset)->length;
}

// Liefert den Namen eines Feldes in einem Ergebnis
function mysql_field_name($result, $field_offset) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_field_name() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_field_direct($field_offset)->name; //or "orgname"
}

// Setzt den Ergebniszeiger auf ein bestimmtes Feldoffset
function mysql_field_seek($result, $field_offset) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_field_seek() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->field_seek($field_offset);
}

// Liefert den Namen der Tabelle, die das genannte Feld enthält
function mysql_field_table($result, $field_offset) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_field_table() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_field_direct($field_offset)->table; // or "orgtable"
}

// Liefert den Typ des spezifizierten Feldes in einem Ergebnis
function mysql_field_type($result, $field_offset) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_field_type() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->fetch_field_direct($field_offset)->type;
}

// Gibt belegten Speicher wieder frei
function mysql_free_result($result) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_free_result() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->free();
}

// Liefert MySQL Clientinformationen
function mysql_get_client_info() {
	global $vts_mysqli;
	return $vts_mysqli->get_client_info();
}

// Liefert MySQL Host Informationen
function mysql_get_host_info($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_get_host_info(). No valid connection to server.");

	return $li->host_info;
}

// Liefert MySQL Protokollinformationen
function mysql_get_proto_info($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_get_proto_info(). No valid connection to server.");

	return $li->protocol_version;
}

// Liefert MySQL Server Informationen
function mysql_get_server_info($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_get_server_info(). No valid connection to server.");

	return $li->server_info;
}

// Liefert Informationen über die zuletzt ausgeführte Anfrage zurück
function mysql_info($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_info(). No valid connection to server.");

	return $li->info;
}

// Liefert die ID, die in der vorherigen Abfrage erzeugt wurde
function mysql_insert_id($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_insert_id(). No valid connection to server.");

	return $li->insert_id;
}

// Auflistung der verfügbaren Datenbanken (Schemata) auf einem MySQL Server
function mysql_list_dbs($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_list_dbs(). No valid connection to server.");

	return mysql_query('SHOW DATABASES', $li);
}

// Listet MySQL Tabellenfelder auf
function mysql_list_fields($database_name, $table_name, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_list_fields(). No valid connection to server.");

	return mysql_query("SHOW COLUMNS FROM `$database_name`.`$table_name`", $li);
}

// Zeigt die MySQL Prozesse an
function mysql_list_processes($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_list_processes(). No valid connection to server.");

	return $li->thread_id;
}

// Listet Tabellen in einer MySQL Datenbank auf
function mysql_list_tables($database, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_list_tables(). No valid connection to server.");

	return mysql_query("SHOW TABLES FROM `$database`", $li);
}

// Liefert die Anzahl der Felder in einem Ergebnis
function mysql_num_fields($result) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_num_fields() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	global $vts_mysqli;
	return $vts_mysqli->field_count;
}

// Liefert die Anzahl der Zeilen im Ergebnis
function mysql_num_rows($result) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_num_rows() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	return $result->num_rows;
}

// Öffnet eine persistente Verbindung zum MySQL Server
function mysql_pconnect($server=null, $username=null, $password=null, $client_flags=0) {
	global $vts_mysqli;
        $ary = explode(':', $server);
	$host = $ary[0];
	$ini_port = ini_get("mysqli.default_port");
	$port = isset($ary[1]) ? (int)$ary[1] : ($ini_port ? (int)$ini_port : 3306);
	if (is_null($server)) $port = ini_get("mysqli.default_host");
	if (is_null($username)) $port = ini_get("mysqli.default_user");
	if (is_null($password)) $port = ini_get("mysqli.default_password");
	$vts_mysqli = new mysqli('p:'.$host, $username, $password, /*dbname*/'', $port, ini_get("mysqli.default_socket"));
	return (empty($vts_mysqli->connect_error) && ($vts_mysqli->connect_errno == 0)) ? $vts_mysqli : false;
}

// Ping a server connection or reconnect if there is no connection
function mysql_ping($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_ping(). No valid connection to server.");

	return $li->ping();
}

// Sendet eine Anfrage an MySQL
function mysql_query($query, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_query(). No valid connection to server.");

	return $li->query($query, $resultmode=MYSQLI_STORE_RESULT);
}

// Maskiert spezielle Zeichen innerhalb eines Strings für die Verwendung in einer SQL-Anweisung
function mysql_real_escape_string($unescaped_string, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_real_escape_string(). No valid connection to server.");

	return $li->escape_string($unescaped_string);
}

// Liefert Ergebnis
function mysql_result($result, $row, $field=0) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_result() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
	$result->data_seek($row);
	return mysql_fetch_array($result)[$field];
}

// Auswahl einer MySQL Datenbank
function mysql_select_db($database_name, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_select_db(). No valid connection to server.");

	return $li->select_db($database_name);
}

// Setzt den Verbindungszeichensatz
function mysql_set_charset($charset, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_set_charset(). No valid connection to server.");

	return $li->set_charset($charset);
}

// Zeigt den momentanen Serverstatus an
function mysql_stat($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_stat(). No valid connection to server.");

	return $li->stat();
}

// Liefert den Namen einer Tabelle
function mysql_tablename($result, $i) {
	if (!$result) {
		$err = mysql_error();
		throw new Exception("Called mysql_tablename() with an erroneous argument.".($err == '' ? '' : " Possible cause: $err"));
	}
        $result->data_seek($i);
        return mysql_fetch_array($result)[0];
}

// Zeigt die aktuelle Thread ID an
function mysql_thread_id($link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_thread_id(). No valid connection to server.");

	return $li->thread_id;
}

// Sendet eine SQL Anfrage an MySQL, ohne Ergebniszeilen abzuholen und zu puffern
function mysql_unbuffered_query($query, $link_identifier=NULL) {
	global $vts_mysqli;
	$li = is_null($link_identifier) ? $vts_mysqli : $link_identifier;
	if (is_null($li)) throw new Exception("Cannot execute mysql_unbuffered_query(). No valid connection to server.");

	// http://php.net/manual/de/mysqlinfo.concepts.buffering.php
	// https://stackoverflow.com/questions/1982016/unbuffered-query-with-mysqli?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
	$li->real_query($query);
	$li->use_result();
}

