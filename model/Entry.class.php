<?php
class Entry extends Model {
	protected $entryId;
	protected $bookId;
	protected $pageNumber;
	protected $entryDate;

	public function __construct($id = 0) {
		if (intval($id) != 0) {
			$sql = "SELECT * FROM Entry WHERE entryId=?";
			$params = array($id);
			$db = new Database();
			$results = $db->query($sql, $params);
			// todo check to make sure something was returned
			$this->setEntryId($results[0]['entryId']);
			$this->setBookId($results[0]['bookId']);
			$this->setPageNumber($results[0]['pageNumber']);
			$this->setEntryDate(new DateTime($results[0]['entryDate']));
		} else {
			$this->setEntryId(0);
			$this->setBookId(0);
			$this->setPageNumber(0);
			$this->setEntryDate(new DateTime());
		}
	}

	public function save() {
		if ($this->getEntryId() > 0) { // update entry
			$sql = "UPDATE Entry SET bookId=?, pageNumber=?, entryDate='?' WHERE entryId=? LIMIT 1";
			$params = array($this->getBookId(), $this->getPageNumber(), $this->getMYSQLEntryDate(), $this->getEntryId());
			$db = new Database();
			$db->query($sql, $params);
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
		$db->query($sql, $param);
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
		return $this->entryDate->format('Y-m-d');
	}

	public function setEntryDate($value) {
		if ($value != null && !is_a($value, 'DateTime')) {
			$msg = 'Invalid DateTime type passed into Entry.getEntryDate()';
			$exception = new Exception($msg);
			throw $exception;
		}

		$this->entryDate = $value;
	}
}
