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
	 * run404
	 * undocumented function
	 *
	 * @author ChadGH
	 * @param 
	 * @return void
	 **/
	public static function display404($args) {
		echo "404";
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
			header('Location: /bookkeeper/chadgh');
			exit(1);
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
	public static function userHome($args) {
		$args = new stdClass();
		$args->title = 'hello';
		$args->note = 'blah blah blah';
		self::displayTemplate('index.php', $args);
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
