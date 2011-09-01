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
		$b = new Book(2);
		echo $b->getPagesPerDay();
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

	public static function displayAddBook($args) {
		$user = $args[0];
		$params = array('title'=>"Add Book | $user", 'new_book'=>true, 'current_book'=>new Book());
		self::mainPage($user, $params, true);
	}

	public static function displayEditBook($args) {
		$user = $args[0];
		$slug = $args[1];
		$b = Book::getBookFromSlug($slug);
		$title = 'Edit ' . $b->getTitle() . ' | ' . $user;
		$params = array('title'=>"Edit {$b->getTitle()} | $user", 'current_book'=>$b, 'new_book'=>false);
		self::mainPage($user, $params, true);
	}

	private static function getActionHTML($book) {
		$actionHtml = '';
		if (!$book->isTodayAReadingDay()) {
			$actionHtml = "<div id='notreadingday' class='action'>You&rsquo;re off the hook today.</div>";
		} elseif ($book->getPagesToday() == 0) {
			$actionHtml = "<div id='reached' class='action'>You&rsquo;ve hit your goal for today.</div>";
		} elseif ($book->getPagesToday() < 0) {
			$numPages = strval($book->getPagesToday() * -1);
			$actionHtml = "<div id='over' class='action'>You&rsquo;re <span class='pagenum'><span id='pagesover'>$numPages</span> pages</span> over your goal for today.</div>";
		} else {
			$actionHtml = "<div id='action' class='action'>Read to <span class='pagenum'>page <span id='topage'>{$book->getToPage()}</span></span> today <span class='pagecount'>(<span id='pagestoday'>{$book->getPagesToday()}</span> pages)</span></div>";
		}
		return $actionHtml;
	}

	public static function displayBook($args)
	{
		$user = $args[0];
		$slug = $args[1];
		$b = Book::getBookFromSlug($slug);
		$title = $b->getTitle() . ' | ' . $user;
		$actionHtml = self::getActionHTML($b);

		$params = array('title'=>$title, 'current_book'=>$b, 'action_html'=>$actionHtml, 'selected_book_id'=>$b->getBookId());
		self::mainPage($user, $params);
	}

	private static function mainPage($username, $params, $displayEdit = false, $displayHome = false, $page = "book") {
		$args = new stdClass();
		$args->username = $username;
		$args->app_url = APP_URL;
		$args->books = Book::getCurrentBooks($username);
		$args->edit_mode = $displayEdit;
		$args->home_mode = $displayHome;
		$args->page = $page;
		foreach ($params as $key=>$value) {
			$args->$key = $value;
		}
		/*$args->userInfo = new User($user);*/
		self::displayTemplate('index.php', $args);
	}

	/**
	 * displayUserHome
	 * displays the homepage
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	public static function displayUserHome($args) {
		$user = $args[0];
		$params = array('title'=>$user);
		self::mainPage($user, $params, false, true, "home");
	}

	/**
	 * displayAllBooks
	 * displays the All Books page
	 *
	 * @author Ben Crowder
	 * @param 
	 * @return void
	 **/
	public static function displayAllBooks($args) {
		$username = $args[0];

		$args = new stdClass();
		$args->username = $username;
		$args->app_url = APP_URL;

		// for nav
		$args->books = Book::getCurrentBooks($username);

		// for all books page
		$args->finishedBooks = Book::getAllFinishedBooks($username);
		$args->currentBooks = Book::getAllCurrentBooks($username);
		$args->hiddenBooks = Book::getAllHiddenBooks($username);

		$args->page = "all";

		/*$args->userInfo = new User($user);*/
		self::displayTemplate('all.php', $args);
	}

	public static function saveEntry($args) {
		$username = trim($args[0]);
		$parts = explode('&', trim($args[1]));
		$bookid = 0;
		$page = 0;
		$entryid = 0;
		foreach ($parts as $part) {
			$components = explode('=', trim($part));
			$key = $components[0];
			$value = $components[1];
			switch ($key)
			{
				case 'bookid':
					$bookid = intval($value);
					break;
				case 'page':
					$page = intval($value);
					break;
				case 'entryid':
					$entryid = intval($value);
					break;
			}
		}

		if ($bookid == 0) {
			echo json_encode(array('error'=>'could not save'));
			exit();
		}

		$entry = new Entry($entryid);
		if ($entry->getEntryId() == 0) {
			$entry = Entry::getEntryFromDate($bookid, date('Y-m-d'));
		}
		if ($entry->getBookId() == 0) {
			$entry->setBookId($bookid);
			$entry->setEntryDate(date('Y-m-d'));
		}
		$entry->setPageNumber($page);
		$entry->save();
		$b = new Book($bookid);
		$b->setActionHtml(self::getActionHTML($b));
		echo $b->getJson();
	}


	public static function deleteBook($args) {
		$username = trim($args[0]);
		$bookId = intval($args[1]);
		$b = new Book($bookId);
		if ($b->getUsername() == $username) {
			$b->delete();
		}
		header('Location: ' . APP_URL . "/$username/");
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
		$variales = array();
		foreach ($parts as $part) {
			$components = explode('=', $part);
			$prop = strtolower(trim($components[0]));
			$value = trim($components[1]);
			$variables[$prop] = $value;
			if ($prop == 'editbookid') {
				$id = intval($value);
			}
		}

		$b = new Book($id);
		if ($id != 0 && $b->getUsername() != $username) {
			echo "something is wrong";
			die();
		} elseif ($id == 0) {
			$b->setUsername($username);
		}

		$b->setSunday(false);
		$b->setMonday(false);
		$b->setTuesday(false);
		$b->setWednesday(false);
		$b->setThursday(false);
		$b->setFriday(false);
		$b->setSaturday(false);
		foreach ($variables as $prop=>$value) {
			switch ($prop)
			{
				case 'editbooktitle':
					$b->setTitle(urldecode($value));
					break;
				case 'editbooktotalpages':
					$b->setTotalPages($value);
					break;
				case 'editbookstartdate':
					$b->setStartDate($value);
					break;
				case 'editbookenddate':
					$b->setEndDate($value);
					break;
				/*case 'hidden':*/
					/*$b->setHidden($value);*/
					/*break;*/
				/*case 'private':*/
					/*$b->setPrivate($value);*/
					/*break;*/
				/*case 'editbookid':*/
					/*$b->setBookId($value);*/
					/*break;*/
				case 'sunday':
					$b->setSunday(true);
					break;
				case 'monday':
					$b->setMonday(true);
					break;
				case 'tuesday':
					$b->setTuesday(true);
					break;
				case 'wednesday':
					$b->setWednesday(true);
					break;
				case 'thursday':
					$b->setThursday(true);
					break;
				case 'friday':
					$b->setFriday(true);
					break;
				case 'saturday':
					$b->setSaturday(true);
					break;
			}
		}
		$url = APP_URL . "/$username/book/{$b->getSlug()}";
		$b->save();
		header("Location: " . APP_URL . "/$username/book/{$b->getSlug()}");
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
}
?>
