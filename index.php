<?php
ini_set('include_path', ini_get('include_path') . ':lib/');
require_once 'config.php';
require_once 'Router.class.php';
require_once 'Bookkeeper.class.php';
require_once 'openid.php';

$temp = 'bookkeeper/';

$routes = array(
	"#^/$temp" . "login/?$#" => 'Bookkeeper::login',
	"#^/$temp([^/]+)/?$#" => 'Bookkeeper::userHome',
	"#^/$temp([^/]+)/books/(.*)$#" => 'Bookkeeper::bookReport',
	"#^/$temp([^/]+)/all/?$#" => 'Bookkeeper::allBooks'
);

Router::routeURI($routes, 'Bookkeeper::display404');
?>
