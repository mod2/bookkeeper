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

$temp = 'bookkeeper/';

$routes = array(
	"#^/$temp" . "login/?$#" => 'Bookkeeper::login',
	"#^/$temp" . "setup/?$#" => 'Bookkeeper::setup',
	"#^/$temp([^/]+)/?$#" => 'Bookkeeper::userHome',
	"#^/$temp([^/]+)/books/save/?\?(.*)#" => 'Bookkeeper::saveBook',
	"#^/$temp([^/]+)/books/(.*)$#" => 'Bookkeeper::bookReport',
	"#^/$temp([^/]+)/all/?$#" => 'Bookkeeper::allBooks'
);

Router::routeURI($routes, 'Bookkeeper::display404');
?>
