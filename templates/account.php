<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>Account</h1>

		<form action="<?php echo $args->app_url . '/saveaccount/'; ?>" id="account">
			<label>Username</label>
			<input type="text" id="username" name="username" value="<?php echo $args->user->getUsername(); ?>"/>
			<input type="hidden" id="google" name="google" value="<?php echo $args->user->getGoogleIdentifier(); ?>"/>

			<label>Email address</label>
			<input type="email" id="email" name="email" value="<?php echo $args->user->getEmail(); ?>"/>

			<input type="submit" value="Save Changes" class="button" />

			<p>API KEY: <?php echo $args->key; ?></p>
			<p><small>We use the username for the short URL, and the email address is only so we can contact you if something goes wrong. (We won't give it to anyone, especially not vile spammers.)</small></p>
		</form>
	</div>
	<?php include 'subfooter.php'; ?>
</section>

<?php include 'footer.php'; ?>
