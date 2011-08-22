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
	public static function run404($args) {
		
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
		$rtnBool = false;
		try {
			$openid = new LightOpenID(APP_HOST);
			if (!$openid->mode) {
				if (isset($_GET['login'])) {
					$openid->identity = 'https://www.google.com/accounts/o8/id';
					$openid->required = array('contact/email');
					header('Location: ' . $openid->authUrl());
					exit(1);
				}
			} elseif ($openid->validate()) {
				$rtnBool = true;
			}
		} catch(ErrorException $e) {
			trigger_error($e->getMessage());
		}
		
		if (!$rtnBool) { // didn't successfully login
			// display login page
		} else { // successfully logged in
			Bookkeeper::userHome();
		}
	}
}
?>
