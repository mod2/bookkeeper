<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

	<title><?php echo $args->title; ?> | Bookkeeper</title>

	<link href='http://fonts.googleapis.com/css?family=Crimson+Text:400,400italic,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo $args->app_url; ?>/css/date_input.css" type="text/css" media="all" charset="utf-8">
	<link rel="stylesheet" href="<?php echo $args->app_url; ?>/css/style.css" type="text/css" media="all" charset="utf-8">
	<link rel="stylesheet" href="<?php echo $args->app_url; ?>/css/tablet.css" media="only screen and (max-width: 900px)" type="text/css" />
	<link rel="stylesheet" href="<?php echo $args->app_url; ?>/css/mobile.css" media="only screen and (max-width: 450px)" type="text/css" />

	<script type="text/javascript" charset="utf-8">
		var currentuser = '<?php echo $args->username; ?>';
		var app_url = '<?php echo $args->app_url; ?>';
		var current_book = {};
		<?php if ($args->current_book): ?>
		current_book = <?php echo $args->current_book->getJson(); ?>;
		<?php endif; ?>
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->app_url; ?>/js/date_input.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->app_url; ?>/js/bookkeeper.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->app_url; ?>/js/chart.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
	<div id="content">
		<?php include 'nav.php';
