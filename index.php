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
	"#^" . APP_URL . "/login/?$#" => 'Bookkeeper::login',
	"#^" . APP_URL . "/setup/?$#" => 'Bookkeeper::setup',
	"#^" . APP_URL . "/([^/]+)/?$#" => 'Bookkeeper::displayUserHome',
	"#^" . APP_URL . "/([^/]+)/action/savebook/?\?(.*)#" => 'Bookkeeper::saveBook',
	"#^" . APP_URL . "/([^/]+)/action/saveentry/?\?(.*)#" => 'Bookkeeper::saveEntry',
	"#^" . APP_URL . "/([^/]+)/action/saveuser/?\?(.*)#" => 'Bookkeeper::saveUser',
	"#^" . APP_URL . "/([^/]+)/book/([^/]+)/?$#" => 'Bookkeeper::displayBook',
	"#^" . APP_URL . "/([^/]+)/book/([^/]+)/edit/?$#" => 'Bookkeeper::editBook',
	"#^" . APP_URL . "/([^/]+)/all/?$#" => 'Bookkeeper::allBooks'
);

Router::routeURI($routes, 'Bookkeeper::display404');
?>
