<?php
class Entry extends Model {
	protected $entryId;
	protected $bookId;
	protected $pageNumber;
	protected $entryDate;

	public static function getAllEntries($bookId) {
		$sql = "SELECT entryId FROM Entry WHERE bookId=? ORDER BY entryDate";
		$db = new Database();
		$rs = $db->query($sql, array($bookId));
		$array = array();
		foreach ($rs as $entry) {
			$array[] = new Entry(intval($entry['entryId']));
		}
		return $array;
	}

	public static function getEntryFromDate($bookId, $date) {
		$sql = "SELECT entryId FROM Entry WHERE bookId=? AND entryDate='?' LIMIT 1";
		$params = array($bookId, $date);
		$db = new Database();
		$results = $db->query($sql, $params);
		$entry = new Entry();
		if (count($results) > 0) {
			$entry = new Entry($results[0]['entryId']);
		}
		return $entry;
	}

	public function __construct($id = 0) {
		$this->setEntryId(0);
		$this->setBookId(0);
		$this->setPageNumber(0);
		$this->setEntryDate(date('Y-m-d'));
		if (intval($id) != 0) {
			$sql = "SELECT * FROM Entry WHERE entryId=? LIMIT 1";
			$params = array($id);
			$db = new Database();
			$results = $db->query($sql, $params);
			if (count($results) > 0) {
				$this->setEntryId($results[0]['entryId']);
				$this->setBookId($results[0]['bookId']);
				$this->setPageNumber($results[0]['pageNumber']);
				$this->setEntryDate($results[0]['entryDate']);
			}
		}
	}

	public function save() {
		if ($this->getEntryId() > 0) { // update entry
			$sql = "UPDATE Entry SET bookId=?, pageNumber=?, entryDate='?' WHERE entryId=? LIMIT 1";
			$params = array($this->getBookId(), $this->getPageNumber(), $this->getMYSQLEntryDate(), $this->getEntryId());
			$db = new Database();
			$db->insert($sql, $params);
		} else { // new entry
			$sql = "INSERT INTO Entry (bookId, pageNumber, entryDate) VALUES (?, ?, '?')";
			$params = array($this->getBookId(), $this->getPageNumber(), $this->getMYSQLEntryDate());
			$db = new Database();
			$id = $db->insert($sql, $params);
			$this->setEntryId($id);
		}
	}

	public function delete() {
		$sql = "DELETE FROM Entry WHERE entryId=? LIMIT 1";
		$param = array($this->getEntryId());
		$db = new Database();
		$db->insert($sql, $param);
	}

	#***************************************************************************
	# Getters and Setters
	#***************************************************************************

	public function getEntryId() {
		return $this->entryId;
	}

	public function setEntryId($value) {
		$this->entryId = intval($value);
	}

	public function getBookId() {
		return $this->bookId;
	}

	public function setBookId($value) {
		$this->bookId = intval($value);
	}

	public function getPageNumber() {
		return $this->pageNumber;
	}

	public function setPageNumber($value) {
		$this->pageNumber = intval($value);
	}

	public function getEntryDate() {
		return $this->entryDate;
	}

	public function getMYSQLEntryDate() {
		return date('Y-m-d', strtotime($this->getEntryDate()));
	}

	public function setEntryDate($value) {
		$this->entryDate = $value;
	}
}
