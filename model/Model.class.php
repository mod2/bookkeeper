<?php
abstract class Model
{
	protected function dbConnect() {
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or throw new Exception(mysql_error());
		mysql_select_db($conn, DB_DATABASE) or throw new Exception(mysql_error());
		return $conn;
	}

	protected function dbClose($db) {
		mysql_close($db);
	}

	protected function prepareSql($sql, $params) {
		$db = $this->dbConnect();
		$preparedSql = mysql_real_escape_string($sql, $db);
		$preparedParams = array();
		foreach ($params as $param) {
			$preparedParams[] = mysql_real_escape_string($param, $db);
		}
		foreach($preparedParams as $param) {
			$preparedSql = str_replace('?', $param, $preparedSql, 1);
		}
		$this->dbClose($db);
		return $preparedSql;
	}

	protected function insert($sql) {
		$db = $this->dbConnect();
		$rs = mysql_query($sql, $db) or throw new Exception(mysql_error());
		$id = mysql_insert_id($db);
		$this->dbClose($db);
		return $id;
	}

	protected function query($sql) {
		$db = $this->dbConnect();
		$rs = mysql_query($sql, $db) or throw new Exception(mysql_error());
		$results = array();
		while (($line = mysql_fetch_assoc($rs)) != null) {
			$results = $line;
		}
		$this->dbClose($db);
		return $results;
	}

	// Get the vaules of the object
	public function toArray()
	{
		return($this->processArray(get_object_vars($this)));
	}

	// Get the values of the array
	private function processArray($array)
	{
		foreach($array as $key => $value)
		{
			if (is_object($value))
			{
				if(is_a($value, 'DateTime'))
				{
					// there is no __toString() function for the PHP DateTime object
					// Return the value of the date time as YYYY-MM-DD HH:MM:SS
					$array[$key] = $value->format('Y-m-d H:i:s');
				}
				else
				{
					$array[$key] = $value->toArray();
				}
			}
			if (is_array($value))
			{
				$array[$key] = $this->processArray($value);
			}
		}
		// If the property isn't an object or array, leave it untouched
		return($array);
	}

	// Return a JSON string which represents this object by default
	public function __toString()
	{
		return($this->getJson());
	}

	// Return a JSON string which represents this object
	public function getJson()
	{
		return(json_encode($this->toArray()));
	}
}
