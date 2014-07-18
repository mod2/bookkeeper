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
		$user = new User($_SESSION['authorizeduser']['google']);
		$args = new stdClass();
		$args->title = "File Not Found (404)";
		$args->user = $user;
		$args->app_url = APP_URL;
		$args->page = '404';
		$args->username = $user->getUsername();
		$args->books = Book::getCurrentBooks($args->username);
		self::displayTemplate('404.php', $args);
	}

	/**
	 * redirectToLogin
	 * redirects the user to the login page
	 *
	 * @author ChadGH
	 **/
	public static function redirectToLogin($args) {
		header('Location: ' . APP_URL . '/login/');
	}

	public static function logout($args) {
		$_SESSION['authorizeduser'] = array();
		unset($_SESSION['authorizeduser']);
		header('Location: http://www.google.com');
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

		$openid = new LightOpenID(APP_HOST);

		try {
			if (array_key_exists('openid_mode', $_GET) && trim($_GET['openid_mode']) != '') {
				if ($openid->validate()) {
					$bool = true;
				}
			} else {
				$openid->identity = 'https://www.google.com/accounts/o8/id';
				$openid->required = array('contact/email');
				header('Location: ' . $openid->authUrl());
				exit(1);
			}
		} catch(ErrorException $e) {
			trigger_error($e->getMessage());
		}
		
		if (!$bool) { // didn't successfully login
			header("Location: http://www.google.com");
		} else { // successfully logged in
			$attr = $openid->getAttributes();
			$googleId = trim($attr['contact/email']);

			$user = new User($googleId);
			$_SESSION['authorizeduser'] = array();

			if ($user->getExisting()) {
				$_SESSION['authorizeduser']['auth'] = true;
				$_SESSION['authorizeduser']['google'] = $googleId;

				if ($user->getUsername() == '') {
					header('Location: ' . APP_URL . '/newaccount/');
				} else {
					$_SESSION['authorizeduser']['username'] = $user->getUsername();
					if (array_key_exists('redirect_url', $_SESSION) && $_SESSION['redirect_url'] != ''){
						header('Location: ' . $_SESSION['redirect_url']);
					} else {
						header('Location: ' . APP_URL . '/' . $user->getUsername());
					}
				}
			} else { // new user
				$_SESSION['authorizeduser']['auth'] = true;
				$_SESSION['authorizeduser']['google'] = $googleId;

				$user->setGoogleIdentifier($googleId);
				$user->setEmail($googleId);
				$user->save();

				header('Location: ' . APP_URL . '/newaccount/');
			}
		}
	}

	/**
	 * checkUserAuth
	 * returns true or false based on if there is an authenticated user session
	 *
	 * @author ChadGH
	 **/
	private static function checkUserAuth($username = '') {
		if (!array_key_exists('authorizeduser', $_SESSION) || !array_key_exists('auth', $_SESSION['authorizeduser']) || !$_SESSION['authorizeduser']['auth'] || ($username != '' && array_key_exists('username', $_SESSION['authorizeduser']) && $username != $_SESSION['authorizeduser']['username'])) 
		{
			$_SESSION['redirect_url'] = APP_URL . $_SERVER['REQUEST_URI'];
			header('Location: ' . APP_URL . '/');
			exit(1);
		}

		if ($username != '') {
			$user = new User($username);
			date_default_timezone_set($user->getTimezone());
		}
	}

	/**
	 * displayNewAccount
	 * display the new user account page. This page is used for creating a new user account
	 *
	 * @author ChadGH
	 **/
	public static function displayNewAccount($args) {
		self::checkUserAuth();
		$user = new User($_SESSION['authorizeduser']['google']);
		$args = new stdClass();
		$args->title = "New Account";
		$args->app_url = APP_URL;
		$args->user = $user;
		$args->books = array();
		$args->username = '';
		$args->page = 'newaccount';
		self::displayTemplate('account.php', $args);
	}

	/**
	 * displayImport
	 * display the import form
	 *
	 * @author ChadGH
	 **/
	public static function displayImport($args) {
		self::checkUserAuth();
		$user = new User($_SESSION['authorizeduser']['google']);
		$args = new stdClass();
		$args->username = $user->getUsername();
		$args->title = "Import Books | " . $args->username;
		$args->app_url = APP_URL;
		$args->books = Book::getCurrentBooks($args->username);
		$args->user = $user;
		$args->page = "import";
		self::displayTemplate('import.php', $args);
	}

	public static function saveImport($args) {
		self::checkUserAuth();
		$user = new User($_SESSION['authorizeduser']['google']);
		$books = Book::getAllBooks($user->getUsername());
		foreach ($books as $book) {
			foreach ($book->getEntries() as $entry) {
				$entry->delete();
			}
			$book->delete();
		}
		$books = array();

		$jsonstr = $_POST['jsonimport'];
		$jsonData = json_decode($jsonstr, true);
		foreach ($jsonData as $book) {
			$newBook = new Book();
			$newBook->setUsername($user->getUsername());
			$newBook->setTitle($book['title']);
			$newBook->setTotalPages($book['totalPages']);
			$newBook->setStartDate($book['startDate']);
			$newBook->setEndDate($book['endDate']);
			$newBook->setSunday($book['sunday']);
			$newBook->setMonday($book['monday']);
			$newBook->setTuesday($book['tuesday']);
			$newBook->setWednesday($book['wednesday']);
			$newBook->setThursday($book['thursday']);
			$newBook->setFriday($book['friday']);
			$newBook->setSaturday($book['saturday']);
			$newBook->setHidden($book['hidden']);
			$newBook->setPrivate($book['private']);
			$newBook->setSlug($book['slug']);
			$newBook->save();
			foreach ($book['entries'] as $entry) {
				$newEntry = new Entry();
				$newEntry->setBookId($newBook->getBookId());
				$newEntry->setPageNumber($entry['pageNumber']);
				$newEntry->setEntryDate($entry['entryDate']);
				$newEntry->save();
			}
		}
		header('Location: ' . APP_URL . '/' . $user->getUsername());
	}

	/**
	 * displayUserAccount
	 * display the user's account page.
	 *
	 * @author ChadGH
	 **/
	public static function displayUserAccount($args) {
		self::checkUserAuth();
		$user = new User($_SESSION['authorizeduser']['google']);
		$args = new stdClass();
		$args->username = $user->getUsername();
		$args->title = "Account | " . $args->username;
		$args->app_url = APP_URL;
		$args->user = $user;
		$args->key = hash('sha256', $user->getUsername() . '-' . $user->getGoogleIdentifier() . $user->getEmail());
		$args->books = Book::getCurrentBooks($args->username);
		$args->page = "account";
		self::displayTemplate('account.php', $args);
	}

	/**
	 * wsExport
	 * export all books and entries in json web service
	 *
	 * @author ChadGH
	 * @return echo json string
	 **/
	public static function wsExport($args) {
		$username = trim($args[0]);
		$user = User::getUserByUsername($username);
		if ($user != null) {
			$key = trim($args[1]);
			if ($key == hash('sha256', $user->getUsername() . '-' . $user->getGoogleIdentifier() . $user->getEmail())) {
				$books = Book::getAllBooks($username);
				$rtn = '[';
				foreach ($books as $book) {
					$rtn .= $book->getJson() . ', ';
				}
				$rtn = substr($rtn, 0, -2);
				$rtn .= ']';
				echo $rtn;
			}
		}
	}

	/**
	 * wsUniqueUsername
	 * returns a true or false indicating whether or not the
	 * provided username is unique.
	 *
	 * @author ChadGH
	 * @return json bool
	 **/
	public static function wsUniqueUsername($args)
	{
		$rtnVar = array('unique'=>true);
		$username = $args[0];
		$google = $args[1];
		$user = new User($google);
		if ($user->getUsername() != $username) {
			$user = User::getUserByUsername($username);
			if ($user != null) {
				$rtnVar['unique'] = false;
			}
		}
		echo json_encode($rtnVar);
	}

	/**
	 * saveAccount
	 * save account changes
	 *
	 * @author ChadGH
	 **/
	public static function saveAccount($args) {
		self::checkUserAuth();
		$parts = explode('&', $args[0]);
		$variables = array();
		foreach ($parts as $variable) {
			$pair = explode('=', $variable);
			$variables[$pair[0]] = urldecode($pair[1]);
		}
		$user = new User($_SESSION['authorizeduser']['google']);
		$user->setUsername($variables['username']);
		$_SESSION['authorizeduser']['username'] = $user->getUsername();
		$user->setEmail($variables['email']);
		$user->setTimezone($variables['timezone']);
		$user->save();
		header('Location: ' . APP_URL . '/' . $user->getUsername());
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
		$db = new Database();
		if (!$db->testDatabase()) {
			$database = DB_DATABASE;
			$sql = <<<SQL
				USE `$database`;
				DROP TABLE IF EXISTS User;
				CREATE TABLE `User` (`username` VARCHAR(255) character set utf8 NOT NULL DEFAULT '', `googleIdentifier` varchar(255) character set utf8 NOT NULL DEFAULT '', `email` varchar(255) character set utf8 NOT NULL DEFAULT '', `private` tinyint(1) NOT NULL DEFAULT '1', `timezone` varchar(255) character set utf8 NOT NULL DEFAULT 'America', PRIMARY KEY (`googleIdentifier`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				DROP TABLE IF EXISTS Book;
				CREATE TABLE `Book` (`bookId` int(11) NOT NULL auto_increment, `username` varchar(255) character set utf8 NOT NULL DEFAULT '', `title` text character set utf8 NOT NULL DEFAULT '', `totalPages` int(11) NOT NULL DEFAULT '0', `startDate` date NOT NULL, `endDate` date NOT NULL, `sunday` tinyint(1) NOT NULL DEFAULT '1', `monday` tinyint(1) NOT NULL DEFAULT '1', `tuesday` tinyint(1) NOT NULL DEFAULT '1', `wednesday` tinyint(1) NOT NULL DEFAULT '1', `thursday` tinyint(1) NOT NULL DEFAULT '1', `friday` tinyint(1) NOT NULL DEFAULT '1', `saturday` tinyint(1) NOT NULL DEFAULT '1', `hidden` tinyint(1) NOT NULL DEFAULT '0', `private` tinyint(1) NOT NULL DEFAULT '1', `slug` varchar(255) NOT NULL DEFAULT '', PRIMARY KEY (`bookId`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
				DROP TABLE IF EXISTS Entry;
				CREATE TABLE `Entry` (`entryId` int(11) NOT NULL auto_increment, `bookId` int(11) NOT NULL DEFAULT '0', `pageNumber` int(11) NOT NULL DEFAULT '0', `entryDate` date NOT NULL, PRIMARY KEY (`entryId`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
SQL;
			/*$db = new Database();*/
			echo "This doesn't work yet. You will need to run the sql below yourself to create the proper database structure.";
			echo "<pre>";
			echo $sql;
			echo "</pre>";
			/*$db->run(trim($sql));*/
		} else {
			header('Location: ' . APP_URL . '/');
		}
	}

	public static function displayAddBook($args) {
		$user = $args[0];
		self::checkUserAuth($user);
		$params = array('title'=>"Add Book | $user", 'new_book'=>true, 'current_book'=>new Book());
		self::mainPage($user, $params, true);
	}

	public static function displayEditBook($args) {
		$user = $args[0];
		self::checkUserAuth($user);
		$slug = $args[1];
		$b = Book::getBookFromSlug($slug);
		$title = 'Edit ' . $b->getTitle() . ' | ' . $user;
		$params = array('title'=>"Edit {$b->getTitle()} | $user", 'current_book'=>$b, 'new_book'=>false);
		self::mainPage($user, $params, true);
	}

	private static function getActionHTML($book) {
		$actionHtml = '';
		if ($book->getPagesLeft() == 0) {
			$actionHtml = "<div id='finished' class='action'>You finished!</div>";
		} elseif (!$book->isTodayAReadingDay()) {
			$actionHtml = "<div id='notreadingday' class='action'>You&rsquo;re off the hook today.</div>";
		} elseif ($book->getEndDate() == '0000-00-00') {
			$actionHtml = "<div id='nogoalread' class='action'>Read!</div>";
		} elseif ($book->getPagesToday() == 0) {
			$actionHtml = "<div id='reached' class='action'>You&rsquo;ve hit your goal for today.</div>";
		} elseif ($book->getPagesToday() < 0) {
			$numPages = strval($book->getPagesToday() * -1);
			$actionHtml = "<div id='over' class='action'>You&rsquo;re <span class='pagenum'><span id='pagesover'>$numPages</span> page";
			if ($numPages != 1) $actionHtml .= 's';
			$actionHtml .= "</span> over your goal for today.</div>";
		} else {
			$actionHtml = "<div id='action' class='action'>Read to <span class='pagenum'>page <span id='topage'>{$book->getToPage()}</span></span> today <span class='pagecount'>(<span id='pagestoday'>{$book->getPagesToday()}</span> page";
			if ($book->getPagesToday() != 1) $actionHtml .= 's';
			$actionHtml .= ")</span></div>";
		}
		return $actionHtml;
	}

	public static function displayBook($args) {
		$user = $args[0];
		self::checkUserAuth($user);
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

		$template = "book.php";

		if ($displayHome) {
			$template = "index.php";
		}

		self::displayTemplate($template, $args);
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
		self::checkUserAuth($user);
		$books = Book::getCurrentBooks($user);
		$activeBooks = array();
		$dormantBooks = array();
		$reachedBooks = array();
		$reachedNoGoalBooks = array();
		$noGoalBooks = array();
		foreach ($books as $book) {
			$entries = $book->getEntries();
			$entryToday = (count($entries) > 0 && $entries[count($entries) - 1]->getEntryDate() == date('Y-m-d')) ? true : false;
			if ($entryToday) {
				$entryToday = false;
				if (count($entries) == 1 && $entries[0]->getPageNumber() > 0) {
					$entryToday = true;
				} elseif (count($entries) > 1) {
					$today = $entries[count($entries) - 1];
					$prev = $entries[count($entries) - 2];
					if ($today->getPageNumber() > $prev->getPageNumber()) {
						$entryToday = true;
					}
				}
			}
			if ($book->isTodayAReadingDay() && $book->getPagesToday() > 0 && $book->getEndDate() != '0000-00-00') {
				$activeBooks[] = $book;
			} elseif ($book->isTodayAReadingDay() && $book->getPagesToday() <= 0 && $book->getEndDate() != '0000-00-00') {
				$reachedBooks[] = $book;
			} elseif ($book->isTodayAReadingDay() && $book->getEndDate() == '0000-00-00') {
				if ($entryToday) {
					$reachedNoGoalBooks[] = $book;
				} else {
					$noGoalBooks[] = $book;
				}
			} elseif (!$book->isTodayAReadingDay()) {
				$dormantBooks[] = $book;
			}
		}
		$params = array('title'=>$user, 'activeBooks'=>$activeBooks, 'reachedBooks'=>$reachedBooks, 'reachedNoGoalBooks'=>$reachedNoGoalBooks, 'dormantBooks'=>$dormantBooks, 'noGoalBooks'=>$noGoalBooks);
		self::mainPage($user, $params, false, true, "home");
	}

	/**
	 * displayFinishedBooks
	 * displays the Finished Books page
	 *
	 * @author Ben Crowder
	 * @param 
	 * @return void
	 **/
	public static function displayFinishedBooks($args) {
		$username = $args[0];
		self::checkUserAuth($username);

		$args = new stdClass();
		$args->username = $username;
		$args->app_url = APP_URL;

		// for all books page
		$args->finishedBooks = Book::getFinishedBooks($username);

		$args->page = "finished";
		$args->title = 'Finished Books | ' . $username;

		self::displayTemplate('finished.php', $args);
	}

	/**
	 * displayHiddenBooks
	 * displays the Hidden Books page
	 *
	 * @author Ben Crowder
	 * @param 
	 * @return void
	 **/
	public static function displayHiddenBooks($args) {
		$username = $args[0];
		self::checkUserAuth($username);

		$args = new stdClass();
		$args->username = $username;
		$args->app_url = APP_URL;

		// Hidden books
		$hiddenBooks = Book::getHiddenBooks($username);
		$args->hiddenBooks = array();
		foreach ($hiddenBooks as $book) {
			$entries = $book->getEntries();
			if (count($entries) > 0) {
				$lastEntry = $entries[count($entries) - 1];
				$lastEntry = $lastEntry->getEntryDate();
			} else {
				$lastEntry = $book->getStartDate();
			}
			$book->setLastEntry(date('j M Y', strtotime($lastEntry)));
			$book->setStartDate(date('j M Y', strtotime($book->getStartDate())));
			array_push($args->hiddenBooks, $book);
		}

		$args->page = "hidden";
		$args->title = 'Hidden Books | ' . $username;

		self::displayTemplate('hidden.php', $args);
	}



	public static function saveEntry($args) {
		/*todo check authentication in some way*/
		/*self::checkUserAuth();*/
		$username = trim($args[0]);
		$user = new User($username);
		date_default_timezone_set($user->getTimezone());
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

	public static function hideBook($args) {
		$username = trim($args[0]);
		self::checkUserAuth($username);
		$bookId = intval($args[1]);
		$b = new Book($bookId);
		$redirect = '';
		if ($b->getUsername() == $username) {
			if ($b->getHidden()) {
				$b->setHidden(false);
				$redirect = 'book/' . $b->getSlug();
			} else {
				$b->setHidden(true);
				$redirect = '';
			}
			$b->save();
		}
		header('Location: ' . APP_URL . "/$username/$redirect");
	}

	public static function deleteBook($args) {
		$username = trim($args[0]);
		self::checkUserAuth($username);
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
	public static function saveBook($args) {
		$username = trim($args[0]);
		self::checkUserAuth($username);
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
	 * displayTemplate
	 *
	 * @author ChadGH
	 **/
	private static function displayTemplate($templateName, $args) {
		include APP_PATH . '/templates/' . $templateName;
	}
}
?>
