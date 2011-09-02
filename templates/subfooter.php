<footer>	
	<span>Bookkeeper crafted lovingly by <a href="http://bencrowder.net/">Ben Crowder</a> and <a href="http://chadgh.com/">Chad Hansen</a>.</span>
	<?php if (property_exists($args, "username")): ?><div id="subfooterlinks"><a href="<?php echo $args->app_url . '/' . $args->username; ?>/account">Account</a> &bull; <a href="<?php echo $args->app_url; ?>/logout">Logout</a></div><?php endif; ?>
</footer>
