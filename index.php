<?php
ini_set('include_path', ini_get('include_path') . ':lib/');
require_once 'Router.class.php';
require_once 'Bookkeeper.class.php';
require_once 'openid.php';

$routes = array(
	'#^/([^/]+)/?$#' => 'Controller::userHome',
	'#^/([^/]+)/books/(.*)$#' => 'Controller::bookReport',
	'#^/([^/]+)/all/?$#' => 'Controller::allBooks'
);

Router::routeURI($routes, 'Controller::run404');
?>
