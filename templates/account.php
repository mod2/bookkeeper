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

			<label for="timezone">Timezone</label>
			<select name="timezone" id="timezone">
				<option value="Africa" <?php if ($args->user->getTimezone() == 'Africa') { echo 'selected="selected"'; } ?>>Africa</option>
				<option value="America" <?php if ($args->user->getTimezone() == 'America') { echo 'selected="selected"'; } ?>>America</option>
				<option value="Antarctica" <?php if ($args->user->getTimezone() == 'Antarctica') { echo 'selected="selected"'; } ?>>Antarctica</option>
				<option value="Aisa" <?php if ($args->user->getTimezone() == 'Aisa') { echo 'selected="selected"'; } ?>>Aisa</option>
				<option value="Atlantic" <?php if ($args->user->getTimezone() == 'Atlantic') { echo 'selected="selected"'; } ?>>Atlantic</option>
				<option value="Europe" <?php if ($args->user->getTimezone() == 'Europe') { echo 'selected="selected"'; } ?>>Europe</option>
				<option value="Indian" <?php if ($args->user->getTimezone() == 'Indian') { echo 'selected="selected"'; } ?>>Indian</option>
				<option value="Pacific" <?php if ($args->user->getTimezone() == 'Pacific') { echo 'selected="selected"'; } ?>>Pacific</option>
			</select>
			<br>

			<input type="submit" value="Save Changes" class="button" />

			<p><a id="exportlink" href="<?php echo $args->app_url . '/' . $args->username . '/export/' . $args->key; ?>">Export data</a></p>

			<p><small>We use the username for the short URL, and the email address is only so we can contact you if something goes wrong. (We won't give it to anyone, especially not vile spammers.)</small></p>
		</form>
	</div>
	<?php include 'subfooter.php'; ?>
</section>

<?php include 'footer.php'; ?>
