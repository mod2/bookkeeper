<?php
/* Router                                 */
/* by Ben Crowder <ben.crowder@gmail.com> */
/* http://bencrowder.net/coding/router    */
/* Modified by Chad Hansen                */
/* added to hbll libraries: 07/26/11      */

class Router
{
	public static function route($uri, $routes, $defaultHandler)
	{
		$found = false;

		// go through each route and see if it matches; if so, execute the handler
		foreach ($routes as $pattern=>$handler)
		{
			if (preg_match($pattern, $uri, $matches))
			{
				call_user_func($handler, array_slice($matches, 1));
				$found = true;
				break;
			}
		}

		// call the default handler
		if (!$found)
		{
			call_user_func($defaultHandler, $uri);
		}
	}

	public static function routeURI($routes, $defaultHandler)
	{
		$uri = $_SERVER['REQUEST_URI'];
		self::route($uri, $routes, $defaultHandler);
	}
}
?>
