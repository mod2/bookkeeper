<?php
class Database
{
	private function dbConnect() {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or $this->throwException(mysql_error());
		mysql_select_db(DB_DATABASE, $conn) or $this->throwException(mysql_error()); 
		return $conn;
	}

	private function dbClose($db) {
		mysql_close($db);
	}

	private function prepareSql($sql, $params, $db) {
		$prepared = $sql;
		$preparedParams = array();
		foreach ($params as $param) {
			$preparedParams[] = mysql_real_escape_string($param, $db);
		}
		foreach($preparedParams as $param) {
			$prepared = preg_replace('/\?/', $param, $prepared, 1);
		}
		return $prepared;
	}

	public function insert($sql, $params) {
		$db = $this->dbConnect();
		$preparedSql = $this->prepareSql($sql, $params, $db);
		$rs = mysql_query($preparedSql, $db) or $this->throwException(mysql_error()); 
		$id = mysql_insert_id($db);
		$this->dbClose($db);
		return $id;
	}

	public function query($sql, $params) {
		$db = $this->dbConnect();
		$preparedSql = $this->prepareSql($sql, $params, $db);
		$rs = mysql_query($preparedSql, $db) or $this->throwException(mysql_error()); 
		$results = array();
		while (($line = mysql_fetch_assoc($rs)) != null) {
			$results = $line;
		}
		$this->dbClose($db);
		return $results;
	}

	public function run($sql) {
		$db = $this->dbConnect();
		mysql_query($sql, $db) or $this->throwException(mysql_error()); 
		$this->dbClose($db);
	}

	private function throwException($message) {
		throw new Exception($message);
	}
}
