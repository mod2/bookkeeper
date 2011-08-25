<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>Account</h1>

		<form id="account">
			<label>Username</label>
			<input type="text" id="username" name="username" />

			<label>Email address</label>
			<input type="email" id="email" name="email" />

			<input type="submit" value="Save Changes" class="button" />

			<p><small>We use the username for the short URL, and the email address is only so we can contact you if something goes wrong. (We won't give it to anyone, especially not vile spammers.)</small></p>
		</form>
	</div>
</section>

<?php include 'footer.php'; ?>
