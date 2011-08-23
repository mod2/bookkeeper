<?php
class Book extends Model {
	protected $bookId;
	protected $username;
	protected $title;
	protected $totalPages;
	protected $startDate;
	protected $endDate;
	protected $sunday;
	protected $monday;
	protected $tuesday;
	protected $wednesday;
	protected $thursday;
	protected $friday;
	protected $saturday;
	protected $hidden;
	protected $private;

	public function __construct($id = 0) {
		if (intval($id) != 0) {
			$sql = "SELECT * FROM Book WHERE bookId = ? LIMIT 1";
			$params = array(intval($id));
			$results = $this->query($this->prepareSql($sql, $params));
			// todo check to make sure something was returned
			$this->setBookId($results[0]['bookId']);
			$this->setUsername($results[0]['username']);
			$this->setTitle($results[0]['title']);
			$this->setTotalPages($results[0]['totalPages']);
			$this->setStartDate(new DateTime($results[0]['startDate']));
			$this->setEndDate(new DateTime($results[0]['endDate']));
			$this->setSunday($results[0]['sunday']);
			$this->setMonday($results[0]['monday']);
			$this->setTuesday($results[0]['tuesday']);
			$this->setWednesday($results[0]['wednesday']);
			$this->setThursday($results[0]['thursday']);
			$this->setFriday($results[0]['friday']);
			$this->setSaturday($results[0]['saturday']);
			$this->setHidden($results[0]['hidden']);
			$this->setPrivate($results[0]['private']);
		} else {
			$this->setBookId(0);
			$this->setUsername('');
			$this->setTitle('');
			$this->setTotalPages(0);
			$this->setStartDate(new DateTime());
			$this->setEndDate(new DateTime());
			$this->setSunday(1);
			$this->setMonday(1);
			$this->setTuesday(1);
			$this->setWednesday(1);
			$this->setThursday(1);
			$this->setFriday(1);
			$this->setSaturday(1);
			$this->setHidden(0);
			$this->setPrivate(1);
		}
	}

	public function save() {
		if ($this->getBookId() > 0) { // update book
			$sql = "UPDATE Book SET username='?', title='?', totalPages=?, startDate='?', endDate='?', sunday=?, monday=?, tuesday=?, wednesday=?, thursday=?, friday=?, saturday=?, hidden=?, private=? WHERE bookId=? LIMIT 1";
			$params = array($this->getUsername(), $this->getTitle(), $this->getTotalPages(), $this->getMYSQLStartDate(), $this->getMYSQLEndDate(), $this->getSunday(), $this->getMonday(), $this->getTuesday(), $this->getWednesday(), $this->getThursday(), $this->getFriday(), $this->getSaturday(), $this->getHidden(), $this->getPrivate(), $this->getBookId());
			$this->query($this->prepareSql($sql, $params));
		} else { // new book
			$sql = "INSERT INTO Book (username, title, totalPages, startDate, endDate, sunday, monday, tuesday, wednesday, thursday, friday, saturday, hidden, private) VALUES ('?', '?', ?, '?', '?', ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$params = array($this->getUsername(), $this->getTitle(), $this->getTotalPages(), $this->getMYSQLStartDate(), $this->getMYSQLEndDate(), $this->getSunday(), $this->getMonday(), $this->getTuesday(), $this->getWednesday(), $this->getThursday(), $this->getFriday(), $this->getSaturday(), $this->getHidden(), $this->getPrivate());
			$id = $this->insert($this->prepareSql($sql, $params));
			$this->setBookId($id);
		}
	}

	public function delete() {
		$sql = "DELETE FROM Book WHERE bookId=? LIMIT 1";
		$param = array($this->getBookId());
		$this->query($this->prepareSql($sql, $param));
	}


	#***************************************************************************
	# Getters and Setters
	#***************************************************************************
	public function getBookId() {
		return $this->bookId;
	}

	public function setBookId($value) {
		$this->bookId = intval($value);
	}

	public function getUsername() {
		return $this->username;
	}

	public function setUsername($value) {
		$this->username = $value . '';
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($value) {
		$this->title = $value . '';
	}

	public function getTotalPages() {
		return $this->totalPages;
	}

	public function setTotalPages($value) {
		$this->totalPages = intval($value);
	}

	public function getStartDate() {
		return $this->startDate;
	}

	public function getMYSQLStartDate() {
		return $this->startDate->format('Y-m-d');
	}

	public function setStartDate($value) {
		if ($value != null && !is_a($value, 'DateTime')) {
			$type = 'Type exception';
			$msg = 'Invalid DateTime type passed into Book.getStartDate()';
			$exception = new ClassTypeException($type, $msg);
			throw($exception);
		}

		$this->startDate = $value;
	}

	public function getEndDate() {
		return $this->endDate;
	}

	public function getMYSQLEndDate() {
		return $this->endDate->format('Y-m-d');
	}

	public function setEndDate($value) {
		if($value != null && !is_a($value, 'DateTime')) {
			$type = 'Type exception';
			$msg = 'Invalid DateTime type passed into Book.getEndDate()';
			$exception = new ClassTypeException($type, $msg);
			throw($exception);
		}

		$this->endDate = $value;
	}

	public function getSunday() {
		return $this->sunday;
	}

	public function setSunday($value) {
		if ($value == true || $value != 0) {
			$this->sunday = true;
		} else {
			$this->sunday = false;
		}
	}

	public function getMonday() {
		return $this->monday;
	}

	public function setMonday($value) {
		if ($value == true || $value != 0) {
			$this->monday = true;
		} else {
			$this->monday = false;
		}
	}

	public function getTuesday() {
		return $this->tuesday;
	}

	public function setTuesday($value) {
		if ($value == true || $value != 0) {
			$this->tuesday = true;
		} else {
			$this->tuesday = false;
		}
	}

	public function getWednesday() {
		return $this->wednesday;
	}

	public function setWednesday($value) {
		if ($value == true || $value != 0) {
			$this->wednesday = true;
		} else {
			$this->wednesday = false;
		}
	}

	public function getThursday() {
		return $this->thursday;
	}

	public function setThursday($value) {
		if ($value == true || $value != 0) {
			$this->thursday = true;
		} else {
			$this->thursday = false;
		}
	}

	public function getFriday() {
		return $this->friday;
	}

	public function setFriday($value) {
		if ($value == true || $value != 0) {
			$this->friday = true;
		} else {
			$this->friday = false;
		}
	}

	public function getSaturday() {
		return $this->saturday;
	}

	public function setSaturday($value) {
		if ($value == true || $value != 0) {
			$this->saturday = true;
		} else {
			$this->saturday = false;
		}
	}

	public function getHidden() {
		return $this->hidden;
	}

	public function setHidden($value) {
		if ($value == true || $value != 0) {
			$this->hidden = true;
		} else {
			$this->hidden = false;
		}
	}

	public function getPrivate() {
		return $this->private;
	}

	public function setPrivate($value) {
		if ($value == true || $value != 0) {
			$this->private = true;
		} else {
			$this->private = false;
		}
	}
}
