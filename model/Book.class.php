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

	protected $percentage;
	protected $pagesLeft;
	protected $entries;
	protected $readingDays;

	protected $pagesPerDay;
	protected $pagesToday;
	protected $toPage;
	protected $daysLeft;

	protected $actionHtml;

	public static function getBookFromSlug($slug) {
		$sql = "SELECT bookId FROM Book WHERE slug='?' LIMIT 1";
		$db = new Database();
		$id = $db->query($sql, array($slug));
		if (count($id) < 1) {
			return null;
		}
		return new Book(intval($id[0]['bookId']));
	}

	public static function getAllBooks($username) {
		$sql = "SELECT bookId FROM Book WHERE username = '?'";
		$db = new Database();
		$rs = $db->query($sql, array($username));
		$array = array();
		foreach ($rs as $book) {
			$b = new Book(intval($book['bookId']));
			$array[] = $b;
		}
		return $array;
	}

	public function __construct($id = 0) {
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

		$this->setReadingDays(array(true, true, true, true, true, true, true));
		$this->setEntries(array());
		$this->setPagesLeft(0);
		$this->setPercentageComplete(0);

		$this->pagesPerDay = 0;
		$this->pagesToday = 0;
		$this->toPage = 0;
		$this->daysLeft = 0;

		if (intval($id) != 0) {
			$sql = "SELECT * FROM Book WHERE bookId = ? LIMIT 1";
			$params = array(intval($id));
			$db = new Database();
			$results = $db->query($sql, $params);
			if (count($results) > 0) {
				$this->setBookId($results[0]['bookId']);
				$this->setUsername($results[0]['username']);
				$this->setTitle($results[0]['title']);
				$this->setTotalPages($results[0]['totalPages']);
				$this->setStartDate($results[0]['startDate']);
				$this->setEndDate($results[0]['endDate']);
				$this->setSunday($results[0]['sunday']);
				$this->setMonday($results[0]['monday']);
				$this->setTuesday($results[0]['tuesday']);
				$this->setWednesday($results[0]['wednesday']);
				$this->setThursday($results[0]['thursday']);
				$this->setFriday($results[0]['friday']);
				$this->setSaturday($results[0]['saturday']);
				$this->setHidden($results[0]['hidden']);
				$this->setPrivate($results[0]['private']);
				$this->setSlug($results[0]['slug']);

				$this->setReadingDays(array($this->getSunday(), $this->getMonday(), $this->getTuesday(), $this->getWednesday(), $this->getThursday(), $this->getFriday(), $this->getSaturday()));
				$this->setEntries(Entry::getAllEntries($this->getBookId()));
				if (count($this->entries) > 0) {
					$this->setPagesLeft($this->getTotalPages() - $this->entries[count($this->entries) - 1]->getPageNumber());
				} else {
					$this->setPagesLeft($this->getTotalPages());
				}
				$this->setPercentageComplete(round((($this->getTotalPages() - $this->getPagesLeft()) / $this->getTotalPages()) * 100));

				$this->getPagesPerDay();
				$this->getToPage();
			}
		}
	}

	public function save() {
		if ($this->getBookId() > 0) { // update book
			//todo if title has changed change slug
			$sql = "UPDATE Book SET username='?', title='?', totalPages=?, startDate='?', endDate='?', sunday=?, monday=?, tuesday=?, wednesday=?, thursday=?, friday=?, saturday=?, hidden=?, private=?, slug='?' WHERE bookId=? LIMIT 1";
			$params = array($this->getUsername(), $this->getTitle(), $this->getTotalPages(), $this->getMYSQLStartDate(), $this->getMYSQLEndDate(), $this->getSunday(), $this->getMonday(), $this->getTuesday(), $this->getWednesday(), $this->getThursday(), $this->getFriday(), $this->getSaturday(), $this->getHidden(), $this->getPrivate(), $this->getSlug(), $this->getBookId());
			$db = new Database();
			$db->insert($sql, $params);
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
		foreach ($this->getEntries() as $entry) {
			$entry->delete();
		}
		$sql = "DELETE FROM Book WHERE bookId=? LIMIT 1";
		$param = array($this->getBookId());
		$db = new Database();
		$db->insert($sql, $param);
	}


	#***************************************************************************
	# Business Logic
	#***************************************************************************
	public function getChartEntries() {
		$chartpoints = array();
		$previousPage = 0;
		$currentEntry = 0;
		$today = strtotime(date('Y-m-d'));
		/*for ($loopTime = strtotime($this->getStartDate(); $loopTime <= $today; $looptime += 86400) {*/

		/*}*/
		/*for (loopTime = new Date(goal.startDate); loopTime <= today; loopTime.setTime(loopTime.valueOf() + 86400000)) {*/
			/*if (goal.readingDays[loopTime.getDay()] == 1) {*/
				/*date = loopTime.getFullYear() + '-' + (loopTime.getMonth() + 1) + '-' + loopTime.getDate();*/
				/*for (currentEntry; currentEntry < entries.length; currentEntry++) {*/
					/*compared = this.compareDates(new Date(entries[currentEntry].date), new Date(date));*/
					/*if (compared == 0) {*/
						/*previousPage = entries[currentEntry].page;*/
						/*break;*/
					/*} else if (compared == 1) {*/
						/*break;*/
					/*} else {*/
						/*previousPage = entries[currentEntry].page;*/
					/*}*/
				/*}*/
				/*chartpoints.push(previousPage);*/
			/*}*/
		/*}*/
		/*return chartpoints;*/
	}

	public function getCurrentPage() {
		if (count($this->entries) > 0) {
			return $this->entries[count($this->entries) - 1]->getPageNumber();
		}
		return 0;
	}

	public function getToPage() {
		if ($this->toPage == 0) {
			$this->toPage = $this->getCurrentPage() + $this->getPagesToday();
		}
		return $this->toPage;
	}

	public function getPagesToday() {
		if ($this->pagesToday == 0) {
			$previousentry = 0;
			$currententry = 0;
			$entries = $this->getEntries();
			if (count($entries) == 1 && $this->compareDateToToday($entries[0]->getEntryDate())) {
				$currententry = $this->getCurrentPage();
			} elseif (count($entries) > 1) {
				if ($this->compareDateToToday($entries[count($entries) - 1]->getEntryDate())) {
					$previousentry = $entries[count($entries) - 2]->getPageNumber();
					$currententry = $entries[count($entries) - 1]->getPageNumber();
				} else {
					$previousentry = $entries[count($entries) - 1]->getPageNumber();
					$currententry = $previousentry;
				}
			}
			$pages = $this->getPagesPerDay() - ($currententry - $previousentry);
			$this->pagesToday = ceil($pages);
		}
		return $this->pagesToday;
	}

	public function getDaysLeft() {
		if ($this->daysLeft == 0) {
			$today = strtotime(date("Y-m-d"));
			$this->daysLeft = $this->daysBetween($today, strtotime($this->getEndDate()), $this->getReadingDays());
		}
		return $this->daysLeft;
	}

	public function getPagesPerDay($fromToday = false) {
		if ($this->pagesPerDay == 0) {
			$daysLeft = $this->getDaysLeft();
			$entries = $this->getEntries();
			$entryPage = 0;
			if ($this != null && $this->getBookId() != 0 && count($entries) > 0) { // book with entries
				if (count($entries) == 1 && !$this->compareDateToToday($entries[0]->getEntryDate())) {
					$entryPage = $entries[0]->getPageNumber();
				} else if (count($entries) > 1) {
					if ($fromToday && $this->compareDateToToday($entries[count($entries) - 1]->getEntryDate())) {
						$entryPage = $entries[count($entries) - 1]->getPageNumber();
					} else if ($this->compareDateToToday($entries[count($entries) - 1]->getEntryDate())) {
						$entryPage = $entries[count($entries) - 2]->getPageNumber();
					} else {
						$entryPage = $entries[count($entries) - 1]->getPageNumber();
					}
				}
			} elseif ($this == null || $this->getBookId() == 0) {  // book with no entries
				return 0;
			}

			$pagesLeft = $this->getTotalPages() - $entryPage;
			if ($daysLeft < 1) {
				$daysLeft = 1;
			}
			$this->pagesPerDay = ceil($pagesLeft / $daysLeft);
		}
		return $this->pagesPerDay;
	}

	public function isTodayAReadingDay() {
		$today = date('w');
		$rtnBool = true;
		$days = $this->getReadingDays();
		if ($days[$today] == 0) {
			$rtnBool = false;
		}
		return $rtnBool;
	}

	#***************************************************************************
	# Utility Functions
	#***************************************************************************
	private function compareDateToToday($date) {
		$today = date('Y-m-d');
		$theDate = date('Y-m-d', strtotime($date));
		return ($today === $theDate) ? true : false;
	}

	private function daysBetween($date1, $date2, $readingDays) {
		$days = 0;
		for ($loopTime = $date1; $loopTime <= $date2; $loopTime += 86400) {
			if ($readingDays[date('w', $loopTime)] || $readingDays[date('w', $loopTime)] == 1) {
				$days++;
			}
		}
		return $days;
	}

	private function compareDate($date1, $date2) {
		$rtnInt = 0;
		$dateA = strtotime($date1);
		$dateB = strtotime($date2);
		if ($dateA < $date2) {
			$rtnInt = -1;
		} elseif ($dateA > $dateB) {
			$rtnInt = 1;
		}
		return $rtnInt;
	}


	#***************************************************************************
	# Getters and Setters
	#***************************************************************************
	public function setReadingDays($array) {
		$this->readingDays = $array;
	}

	public function getReadingDays() {
		return $this->readingDays;
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

	public function setPagesLeft($pages) {
		$this->pagesLeft = $pages;
	}

	public function getPagesLeft() {
		return $this->pagesLeft;
	}

	public function getPercentageComplete() {
		return $this->percentage;
	}

	public function setPercentageComplete($percentage) {
		$this->percentage = $percentage;
	}

	public function getEntries() {
		return $this->entries;
	}

	public function setEntries($entries) {
		$this->entries = $entries;
	}

	public function setActionHtml($html) {
		$this->actionHtml = $html;
	}

	public function getActionHtml() {
		return $this->actionHtml;
	}
}
