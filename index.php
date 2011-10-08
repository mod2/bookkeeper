<?php
ini_set('include_path', ini_get('include_path') . ':lib/:model/');
session_start();
require_once 'config.php';
require_once 'Database.class.php';
require_once 'Model.class.php';
require_once 'Book.class.php';
require_once 'Entry.class.php';
require_once 'User.class.php';

require_once 'Router.class.php';
require_once 'Bookkeeper.class.php';
require_once 'openid.php';

$routes = array(
	"#^" . APP_URL . "/login/?(.*)$#" => 'Bookkeeper::login',
	"#^" . APP_URL . "/logout/?$#" => 'Bookkeeper::logout',
	"#^" . APP_URL . "/newaccount/?$#" => 'Bookkeeper::displayNewAccount',
	"#^" . APP_URL . "/saveaccount/?\?(.*)$#" => 'Bookkeeper::saveAccount',
	"#^" . APP_URL . "/setup/?$#" => 'Bookkeeper::setup',
	"#^" . APP_URL . "/usernamecheck/([^/]+)/([^/]+)/?$#" => 'Bookkeeper::wsUniqueUsername',
	"#^" . APP_URL . "/([^/]+)/?$#" => 'Bookkeeper::displayUserHome',
	"#^" . APP_URL . "/([^/]+)/account/?$#" => 'Bookkeeper::displayUserAccount',
	"#^" . APP_URL . "/([^/]+)/action/savebook/?\?(.*)#" => 'Bookkeeper::saveBook',
	"#^" . APP_URL . "/([^/]+)/action/deletebook/([\d]+)#" => 'Bookkeeper::deleteBook',
	"#^" . APP_URL . "/([^/]+)/action/hidebook/([\d]+)#" => 'Bookkeeper::hideBook',
	"#^" . APP_URL . "/([^/]+)/action/saveentry/?\?(.*)#" => 'Bookkeeper::saveEntry',
	"#^" . APP_URL . "/([^/]+)/action/saveuser/?\?(.*)#" => 'Bookkeeper::saveUser',
	"#^" . APP_URL . "/([^/]+)/book/add/?$#" => 'Bookkeeper::displayAddBook',
	"#^" . APP_URL . "/([^/]+)/book/([^/]+)/?$#" => 'Bookkeeper::displayBook',
	"#^" . APP_URL . "/([^/]+)/book/([^/]+)/edit/?$#" => 'Bookkeeper::displayEditBook',
	"#^" . APP_URL . "/([^/]+)/all/?$#" => 'Bookkeeper::displayAllBooks',
	"#^" . APP_URL . "/([^/]+)/export/([^/]+)/?$#" => 'Bookkeeper::wsExport',
	"#^" . APP_URL . "/([^/]+)/import/?$#" => 'Bookkeeper::displayImport',
	"#^" . APP_URL . "/([^/]+)/saveimport/?$#" => 'Bookkeeper::saveImport',
	"#^" . APP_URL . "/?$#" => 'Bookkeeper::redirectToLogin'
);

Router::routeURI($routes, 'Bookkeeper::display404');
?>
