<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

	<title><?php echo $args->title; ?> | Bookkeeper</title>

	<link href='http://fonts.googleapis.com/css?family=Crimson+Text:400,400italic,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo $args->apphome; ?>/css/date_input.css" type="text/css" media="all" charset="utf-8">
	<link rel="stylesheet" href="<?php echo $args->apphome; ?>/css/style.css" type="text/css" media="all" charset="utf-8">
	<link rel="stylesheet" href="<?php echo $args->apphome; ?>/css/tablet.css" media="only screen and (max-width: 900px)" type="text/css" />
	<link rel="stylesheet" href="<?php echo $args->apphome; ?>/css/mobile.css" media="only screen and (max-width: 450px)" type="text/css" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->apphome; ?>/js/jquery.date_input.pack.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->apphome; ?>/js/config.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->apphome; ?>/js/bookkeeper.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $args->apphome; ?>/js/chart.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
	<div id="content">
		<?php include 'nav.php';
