<?php

class common_mysql {

	var $cn;
	var $dbname;

	// Constructor
	function __construct($host, $dbname, $user, $pass, $port) {

		// Connection
		$cn = $this->connect($host, $dbname, $user, $pass, $port);
   		// Set members
		$this->cn = $cn;
		$this->dbname = $dbname;
	}

	// Connect
	function connect($host, $dbname, $user, $pass, $port=3306) {
		$cn = mysqli_connect($host, $user, $pass, $dbname, $port)
				or die('Failed to connect to database');

		// Encode to utf8
		mysqli_set_charset($cn , 'utf8');

		return $cn;
	}

	/*
	 * Escape query
	 */
	function execute($query, $data=array()){

		// Format the given query
		if (count($data) > 0) {
			$query = vsprintf(str_replace('?', '%s', $query), $data);
		}

		// print $query;
		if (!($rs = mysqli_query($this->cn, $query))){
			$_SESSION['error_query'] = $query;

		}

		return $rs;
	}

	/*
	 * Retrieve a single row of data
	 * Return as an associative array
	 */
	function select_one($query, $data=array()){

		$result = $this->execute($query, $data);

		// if (pg_num_rows($result)) {
		if (mysqli_num_rows($result)) {
			$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
			return $data;

		} else {
			return false;
		}

	}

	/*
	 * Save data
	 * If $where is specified, update the matching data,
	 * otherwise execute as a new data insertion.
	 */
	function save($table, $data=array(), $where=null, $return_sql_only=null){

		// Record the update time and updater ID
		$id = (int)$id;

		$now = date('Y-m-d H:i:s');
		$data['update_dt'] = $now;
		$data['update_id'] = $id;

		// UPDATE
		if ((boolean)$where) {
			$set = null;
			foreach ($data as $key => $value) {
				$set .= ((boolean)$set) ? ", $key = $value" : "$key = $value";
			}

			foreach ($where as $key => $value) {
				$where_s .= ((boolean)$where_s) ? " And $key = $value" : "$key = $value";
			}

			$query = "UPDATE $table SET $set WHERE $where_s ;";

		// INSERT
		} else {
			// When new, also save the creation time and creator
			$data['insert_dt'] = $now;
			$data['insert_id'] = $id;

			$column = null;
			$values = null;
			foreach ($data as $key => $value) {
				$column .= ((boolean)$column) ? ', ' . $key : $key;
				$values .= ($values . '' != '') ? ', ' . $value : $value;
			}

			$query = "INSERT INTO $table ($column) VALUES($values);";
		}

		$_SESSION['query'] = $query;

        // echo $query . '<br />';

		// If only returning the SQL
		if ($return_sql_only + 0 == 1) {
			return $query;
		}

		return mysqli_query($this->cn, $query);
	}

	/*
	 * Delete data
	 */
	function delete($table, $data=array()) {
		if ((boolean)$data) {
			foreach ($data as $key => $value) {
				$where .= ((boolean)$where) ? " And $key = $value" : "$key = $value";
			}
			$where = ' WHERE ' . $where;
		}
		$query = "DELETE FROM $table$where ;";
		$_SESSION['query'] = $query;

		return mysqli_query($this->cn, $query);
	}

	// Convert to numeric
	function set_numeric($str) {
		$str = str_replace(",", "", $str);
		$str = str_replace("%", "", $str);
		return $str + 0;
	}

	function set_integer($str) {
		return (int)$str;
	}

	// String (escape and add quotes)
	function set_string($str) {
		return "'" . mysqli_real_escape_string($this->cn, $str) . "'";
	}
}
?>
