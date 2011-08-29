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
		$params = array('new_book'=>true);
		self::mainPage($user, $params, true);
	}

	public static function displayEditBook($args) {
		$user = $args[0];
		$slug = $args[1];
		$b = Book::getBookFromSlug($slug);
		$title = 'Edit ' . $b->getTitle() . ' | ' . $user . ' | Bookkeeper';
		$params = array('current_book'=>$b);
		self::mainPage($user, $params, true);
	}

	public static function displayBook($args)
	{
		$user = $args[0];
		$slug = $args[1];
		$b = Book::getBookFromSlug($slug);
		$title = $b->getTitle() . ' | ' . $user . ' | Bookkeeper';
		$actionHtml = '';
		if (!$b->isTodayAReadingDay()) {
			$actionHtml = "<div id='notreadingday' class='action'>You&rsquo;re off the hook today.</div>";
		} elseif ($b->getPagesToday() == 0) {
			$actionHtml = "<div id='reached' class='action'>You&rsquo;ve hit your goal for today.</div>";
		} elseif ($b->getPagesToday() < 0) {
			$numPages = strval($b->getPagesToday() * -1);
			$actionHtml = "<div id='over' class='action'>You&rsquo;re <span class='pagenum'><span id='pagesover'>$numPages</span> pages</span> over your goal for today.</div>";
		} else {
			$actionHtml = "<div id='action' class='action'>Read to <span class='pagenum'>page <span id='topage'>{$b->getToPage()}</span></span> today <span class='pagecount'>(<span id='pagestoday'>{$b->getPagesToday()}</span> pages)</span></div>";
		}

		$params = array('title'=>$title, 'current_book'=>$b, 'action_html'=>$actionHtml, 'selected_book_id'=>$b->getBookId());
		self::mainPage($user, $params);
	}

	private static function mainPage($username, $params, $displayEdit = false) {
		$args = new stdClass();
		$args->username = $username;
		$args->app_url = APP_URL;
		$args->books = Book::getAllBooks($username);
		$args->edit_mode = $displayEdit;
		foreach ($params as $key=>$value) {
			$args->$key = $value;
		}
		/*$args->userInfo = new User($user);*/
		self::displayTemplate('index.php', $args);
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
		$user = $args[0];
		$params = array('title'=>$user . ' | Bookkeeper');
		self::mainPage($user, $params);
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
}
?>
