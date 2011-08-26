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
	protected $slug;

	public static function getBookFromSlug($slug) {
		$sql = "SELECT bookId FROM Book WHERE slug='?' LIMIT 1";
		$db = new Database();
		$id = $db->query($sql, array($slug));
		if (count($id) < 1) {
			return null;
		}
		return new Book(intval($id['bookId']));
	}

	public static function getAllBooks() {
		$sql = "SELECT bookId FROM Book";
		$db = new Database();
		$rs = $db->query($sql, array());
		$array = array();
		foreach ($rs as $book) {
			$b = new Book(intval($book['bookId']));
			$array[] = $b;
		}
		return $array;
	}

	public function __construct($id = 0) {
		if (intval($id) != 0) {
			$sql = "SELECT * FROM Book WHERE bookId = ? LIMIT 1";
			$params = array(intval($id));
			$db = new Database();
			$results = $db->query($sql, $params);
			// todo check to make sure something was returned
			$this->setBookId($results['bookId']);
			$this->setUsername($results['username']);
			$this->setTitle($results['title']);
			$this->setTotalPages($results['totalPages']);
			$this->setStartDate($results['startDate']);
			$this->setEndDate($results['endDate']);
			$this->setSunday($results['sunday']);
			$this->setMonday($results['monday']);
			$this->setTuesday($results['tuesday']);
			$this->setWednesday($results['wednesday']);
			$this->setThursday($results['thursday']);
			$this->setFriday($results['friday']);
			$this->setSaturday($results['saturday']);
			$this->setHidden($results['hidden']);
			$this->setPrivate($results['private']);
			$this->setSlug($results['slug']);
		} else {
			$this->setBookId(0);
			$this->setUsername('');
			$this->setTitle('');
			$this->setTotalPages(0);
			$this->setStartDate(date('Y-m-d'));
			$this->setEndDate(date('Y-m-d'));
			$this->setSunday(1);
			$this->setMonday(1);
			$this->setTuesday(1);
			$this->setWednesday(1);
			$this->setThursday(1);
			$this->setFriday(1);
			$this->setSaturday(1);
			$this->setHidden(0);
			$this->setPrivate(1);
			$this->setSlug('');
		}
	}

	public function save() {
		if ($this->getBookId() > 0) { // update book
			//todo if title has changed change slug
			$sql = "UPDATE Book SET username='?', title='?', totalPages=?, startDate='?', endDate='?', sunday=?, monday=?, tuesday=?, wednesday=?, thursday=?, friday=?, saturday=?, hidden=?, private=?, slug='?' WHERE bookId=? LIMIT 1";
			$params = array($this->getUsername(), $this->getTitle(), $this->getTotalPages(), $this->getMYSQLStartDate(), $this->getMYSQLEndDate(), $this->getSunday(), $this->getMonday(), $this->getTuesday(), $this->getWednesday(), $this->getThursday(), $this->getFriday(), $this->getSaturday(), $this->getHidden(), $this->getPrivate(), $this->getSlug(), $this->getBookId());
			$db = new Database();
			$db->query($sql, $params);
		} else { // new book
			$db = new Database();
			$slug = str_replace(' ', '-', strtolower($this->getTitle()));
			$sql = "SELECT slug FROM Book WHERE slug LIKE '?%'";
			$rs = $db->query($sql, array($slug));
			$count = 1;
			//todo this doesn't work quite right
			if (count($rs) > 0) {
				foreach ($rs as $slug) {
					$currentCount = intval(substr($slug['slug'], -1));
					if ($currentCount >= $count) {
						$count = $currentCount + 1;
					} else {
						$count++;
					}
				}
			}
			if ($count > 1) {
				$slug .= '-' . $count;
			}
			$this->setSlug($slug);
			$sql = "INSERT INTO Book (username, title, totalPages, startDate, endDate, sunday, monday, tuesday, wednesday, thursday, friday, saturday, hidden, private, slug) VALUES ('?', '?', ?, '?', '?', ?, ?, ?, ?, ?, ?, ?, ?, ?, '?')";
			$params = array($this->getUsername(), $this->getTitle(), $this->getTotalPages(), $this->getMYSQLStartDate(), $this->getMYSQLEndDate(), $this->getSunday(), $this->getMonday(), $this->getTuesday(), $this->getWednesday(), $this->getThursday(), $this->getFriday(), $this->getSaturday(), $this->getHidden(), $this->getPrivate(), $this->getSlug());
			$id = $db->insert($sql, $params);
			$this->setBookId($id);
		}
	}

	public function delete() {
		$sql = "DELETE FROM Book WHERE bookId=? LIMIT 1";
		$param = array($this->getBookId());
		$db = new Database();
		$db->query($sql, $param);
	}


	#***************************************************************************
	# Getters and Setters
	#***************************************************************************
	public function getReadingDaysArray() {
		$array = array($this->getSunday(), $this->getMonday(), $this->getTuesday(), $this->getWednesday(), $this->getThursday(), $this->getFriday(), $this->getSaturday());
		return $array;
	}

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
		return date('Y-m-d', strtotime($this->getStartDate()));
	}

	public function setStartDate($value) {
		$this->startDate = $value;
	}

	public function getEndDate() {
		return $this->endDate;
	}

	public function getMYSQLEndDate() {
		return date('Y-m-d', strtotime($this->getEndDate()));
	}

	public function setEndDate($value) {
		$this->endDate = $value;
	}

	public function getSunday() {
		return ($this->sunday) ? 1 : 0;
	}

	public function setSunday($value) {
		if ($value == true || $value != 0) {
			$this->sunday = true;
		} else {
			$this->sunday = false;
		}
	}

	public function getMonday() {
		return ($this->monday) ? 1 : 0;
	}

	public function setMonday($value) {
		if ($value == true || $value != 0) {
			$this->monday = true;
		} else {
			$this->monday = false;
		}
	}

	public function getTuesday() {
		return ($this->tuesday) ? 1 : 0;
	}

	public function setTuesday($value) {
		if ($value == true || $value != 0) {
			$this->tuesday = true;
		} else {
			$this->tuesday = false;
		}
	}

	public function getWednesday() {
		return ($this->wednesday) ? 1 : 0;
	}

	public function setWednesday($value) {
		if ($value == true || $value != 0) {
			$this->wednesday = true;
		} else {
			$this->wednesday = false;
		}
	}

	public function getThursday() {
		return ($this->thursday) ? 1 : 0;
	}

	public function setThursday($value) {
		if ($value == true || $value != 0) {
			$this->thursday = true;
		} else {
			$this->thursday = false;
		}
	}

	public function getFriday() {
		return ($this->friday) ? 1 : 0;
	}

	public function setFriday($value) {
		if ($value == true || $value != 0) {
			$this->friday = true;
		} else {
			$this->friday = false;
		}
	}

	public function getSaturday() {
		return ($this->saturday) ? 1 : 0;
	}

	public function setSaturday($value) {
		if ($value == true || $value != 0) {
			$this->saturday = true;
		} else {
			$this->saturday = false;
		}
	}

	public function getHidden() {
		return ($this->hidden) ? 1 : 0;
	}

	public function setHidden($value) {
		if ($value == true || $value != 0) {
			$this->hidden = true;
		} else {
			$this->hidden = false;
		}
	}

	public function getPrivate() {
		return ($this->private) ? 1 : 0;
	}

	public function setPrivate($value) {
		if ($value == true || $value != 0) {
			$this->private = true;
		} else {
			$this->private = false;
		}
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug($value) {
		$this->slug = $value;
	}
}
