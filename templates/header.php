<html>
<head>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

	<title><?php echo $args->title; ?> | Bookkeeper</title>

	<link rel="stylesheet" href="<?php echo $args->app_url; ?>/css/charter.css" type="text/css" media="all" charset="utf-8">
	<link rel="stylesheet" href="<?php echo $args->app_url; ?>/css/style.css" type="text/css" media="all" charset="utf-8">
	<link rel="stylesheet" href="<?php echo $args->app_url; ?>/css/calendar.css" type="text/css" media="all" charset="utf-8">

	<link rel="apple-touch-icon-precomposed" href="<?php echo $args->app_url; ?>/apple-touch-icon.png">
	<link rel="shortcut icon" href="<?php echo $args->app_url; ?>/favicon.png" />

	<script type="text/javascript" charset="utf-8">
		var currentuser = '<?php echo $args->username; ?>';
		var app_url = '<?php echo $args->app_url; ?>';
		var current_book = {};
		<?php if (property_exists($args, "current_book") && $args->current_book): ?>
		current_book = <?php echo $args->current_book->getJson(); ?>;
		<?php endif; ?>
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->app_url; ?>/js/bookkeeper.js?0910" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->app_url; ?>/js/chart.js?0910" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->app_url; ?>/js/cal.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
	<div id="banner_container">
		<div id="banner">
			<?php if (property_exists($args, "username")): ?><ul id="account"><li><a href="<?php echo $args->app_url . '/' . $args->username . '/finished'; ?>">History</a><li><a href="<?php echo $args->app_url . '/' . $args->username; ?>/account">Account</a></li><li><a href="<?php echo $args->app_url; ?>/logout">Logout</a></ul><?php endif; ?>
			<a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>"><h1>Books</h1></a>
		</div>	
	</div>
	<div id="page">
