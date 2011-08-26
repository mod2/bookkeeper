<?php
/**
 * Bookkeeper
 * undocumented class
 *
 * @package default
 * @subpackage default
 * @author ChadGH
 **/
class Bookkeeper
{
	/**
	 * display404
	 * undocumented function
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	public static function display404($args) {
		echo self::pagesPerDay(1);
	}

	/**
	 * login
	 * redirects to google (using openId) to login a user.
	 *
	 * @author ChadGH
	 * @param $args (query string parameters)
	 * @return void
	 **/
	public static function login($args) {
		$bool = false;
		try {
			$openid = new LightOpenID(APP_HOST);
			if (!$openid->mode) {
				$openid->identity = 'https://www.google.com/accounts/o8/id';
				$openid->required = array('contact/email');
				header('Location: ' . $openid->authUrl());
				exit(1);
			} elseif ($openid->validate()) {
				$bool = true;
			}
		} catch(ErrorException $e) {
			trigger_error($e->getMessage());
		}
		
		if (!$bool) { // didn't successfully login
			// display login page
			echo "display login screen";
		} else { // successfully logged in
			header('Location: ' . APP_URL . 'chadgh');
			exit(1);
		}
	}

	/**
	 * setup
	 * setup a new instance of bookkeeper or reinstall an instance.
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	public static function setup($args)
	{
		$database = DB_DATABASE;
		$sql = <<<SQL
			USE `$database`;
			DROP TABLE IF EXISTS User;
			CREATE TABLE `User` (`username` VARCHAR(255) character set utf8 NOT NULL DEFAULT '', `googleIdentifier` varchar(255) character set utf8 NOT NULL DEFAULT '', `email` varchar(255) character set utf8 NOT NULL DEFAULT '', `private` tinyint(1) NOT NULL DEFAULT '1', PRIMARY KEY (`googleIdentifier`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			DROP TABLE IF EXISTS Book;
			CREATE TABLE `Book` (`bookId` int(11) NOT NULL auto_increment, `username` varchar(255) character set utf8 NOT NULL DEFAULT '', `title` varchar(255) character set utf8 NOT NULL DEFAULT '', `totalPages` int(11) NOT NULL DEFAULT '0', `startDate` date NOT NULL, `endDate` date NOT NULL, `sunday` tinyint(1) NOT NULL DEFAULT '1', `monday` tinyint(1) NOT NULL DEFAULT '1', `tuesday` tinyint(1) NOT NULL DEFAULT '1', `wednesday` tinyint(1) NOT NULL DEFAULT '1', `thursday` tinyint(1) NOT NULL DEFAULT '1', `friday` tinyint(1) NOT NULL DEFAULT '1', `saturday` tinyint(1) NOT NULL DEFAULT '1', `hidden` tinyint(1) NOT NULL DEFAULT '0', `private` tinyint(1) NOT NULL DEFAULT '1', `slug` varchar(255) NOT NULL DEFAULT '', PRIMARY KEY (`bookId`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
			DROP TABLE IF EXISTS Entry;
			CREATE TABLE `Entry` (`entryId` int(11) NOT NULL auto_increment, `bookId` int(11) NOT NULL DEFAULT '0', `pageNumber` int(11) NOT NULL DEFAULT '0', `entryDate` date NOT NULL, PRIMARY KEY (`entryId`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
SQL;
		/*$db = new Database();*/
		echo "This doesn't work yet. You will need to run the sql below yourself to create the proper database structure.";
		echo "<pre>";
		echo $sql;
		echo "</pre>";
		/*$db->run(trim($sql));*/
	}

	/**
	 * bookReport
	 * displays a book
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	public static function displayBook($args)
	{
		$slug = $args[1];
		$b = Book::getBookFromSlug($slug);
		if ($b == null) {
			echo 'nothing matching';
		} else {
			echo $b;
		}
	}

	/**
	 * userHome
	 * displays the homepage
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	public static function displayUserHome($args) {
		$args = new stdClass();
		$args->title = 'Home';
		$args->app_url = APP_URL;
		self::displayTemplate('index.php', $args);
	}

	/**
	 * saveBook
	 * Saves a book to the database and redirects to that books tab
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	public static function saveBook($args)
	{
		$username = trim($args[0]);
		$parts = explode('&', trim($args[1]));
		$id = 0;
		foreach ($parts as $part) {
			$components = explode('=', $part);
			$prop = strtolower(trim($components[0]));
			$value = trim($components[1]);
			if ($prop == 'id') {
				$id = intval($value);
				break;
			}
		}

		$b = new Book($id);
		if ($id != 0 && $b->getUsername() != $username) {
			echo "something is wrong";
			die();
		} elseif ($id == 0) {
			$b->setUsername($username);
		}

		foreach ($parts as $part) {
			$components = explode('=', $part);
			$prop = strtolower(trim($components[0]));
			$value = trim($components[1]);
			switch ($prop)
			{
				case 'title':
					$b->setTitle(urldecode($value));
					break;
				case 'totalpages':
					$b->setTotalPages($value);
					break;
				case 'startdate':
					$b->setStartDate(new DateTime($value));
					break;
				case 'enddate':
					$b->setEndDate(new DateTime($value));
					break;
				case 'hidden':
					$b->setHidden($value);
					break;
				case 'private':
					$b->setPrivate($value);
					break;
				case 'id':
					$b->setBookId($value);
					break;
				case 'sun':
					$b->setSunday($value);
					break;
				case 'mon':
					$b->setMonday($value);
					break;
				case 'tue':
					$b->setTuesday($value);
					break;
				case 'wed':
					$b->setWednesday($value);
					break;
				case 'thu':
					$b->setThursday($value);
					break;
				case 'fri':
					$b->setFriday($value);
					break;
				case 'sat':
					$b->setSaturday($value);
					break;
			}
		}
		$b->save();
		header("Location: " . APP_URL . "/chadgh/book/{$b->getSlug()}");
	}

	/**
	 * getTemplate
	 * undocumented function
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	private static function displayTemplate($templateName, $args) {
		include APP_PATH . '/templates/' . $templateName;
	}

	########################################################################
	######################  Business Logic  ################################
	########################################################################
	private static function pagesPerDay($bookId, $fromToday = false) {
		$book = new Book($bookId);
		$daysLeft = self::daysLeft($book);
		$entries = Entry::getAllEntries($bookId);
		$entryPage = 0;
		if ($book != null && $book->getBookId() != 0 && count($entries) > 0) { // book with entries
			if (count($entries) == 1 && !self::compareDateToToday($entries[0]->getDate())) {
				$entryPage = $entries[0]->getPage();
			} else if (count($entries) > 1) {
				if ($fromToday && self::compareDateToToday($entries[count($entries) - 1]->getDate())) {
					$entryPage = $entries[count($entries) - 1]->getPage();
				} else if (self::compareDateToToday($entries[count($entries) - 1]->getDate())) {
					$entryPage = $entries[count($entries) - 2]->getPage();
				} else {
					$entryPage = $entries[count($entries) - 1]->getPage();
				}
			}
		} elseif ($book == null || $book->getBookId() == 0) {  // book with no entries
			return 0;
		}

		$pagesLeft = $book->getTotalPages() - $entryPage;
		return ceil($pagesLeft / $daysLeft);
	}

	private static function compareDateToToday($date) {
		$today = date('Y-m-d');
		$theDate = date('Y-m-d', strtotime($date));
		return ($today === $theDate) ? true : false;
	}

	private static function daysBetween($date1, $date2, $readingDays) {
		$days = 0;
		for ($loopTime = $date1; $loopTime <= $date2; $loopTime += 86400) {
			if ($readingDays[date('w', $loopTime)] || $readingDays[date('w', $loopTime)] == 1) {
				$days++;
			}
		}
		return $days;
	}

	private static function daysLeft($book) {
		$today = strtotime(date("Y-m-d"));
		return self::daysBetween($today, strtotime($book->getEndDate()), $book->getReadingDaysArray());
	}
	
	/*this.chartEntries = function (goal, entries) {*/
		/*chartpoints = [];*/
		/*previousPage = 0;*/
		/*currentEntry = 0;*/
		/*tempday = new Date();*/
		/*today = new Date(tempday.getFullYear() + '-' + (tempday.getMonth() + 1) + '-' + tempday.getDate());*/
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
	/*};*/


	private static function compareDate($date1, $date2) {
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

	private static function pagesToday($entries, $pagesperday) {
		/*var today = new Date();*/
		/*previousentry = 0;*/
		/*currententry = 0;*/
		/*if (entries.length == 1 && this.compareDateToToday(entries[0].date)) {*/
			/*currententry = entries[0].page;*/
		/*} else if (entries.length > 1) {*/
			/*if (this.compareDateToToday(entries[entries.length - 1].date)) {*/
				/*previousentry = entries[entries.length - 2].page;*/
				/*currententry = entries[entries.length - 1].page;*/
			/*} else {*/
				/*previousentry = entries[entries.length - 1].page;*/
				/*currententry = previousentry;*/
			/*}*/
		/*}*/
		/*pages = pagesperday - (currententry - previousentry);*/
		/*return Math.ceil(pages);*/
	}

	private static function toPage($pagestoday, $currentpage) {
		return $currentpage + $pagestoday;
	}

	private static function todayReadingDay($readingDays) {
		$today = date('w');
		$rtnBool = true;
		if ($readingDays[$today] == 0) {
			$rtnBool = false;
		}
		return $rtnBool;
	}
}
?>
