<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>Import Books</h1>
		
		<p>Warning: importing books will wipe all other books and data you currently have in the system.</p>
		<form action="<?php echo $args->app_url .'/'. $args->username . '/saveimport/'; ?>" id="import" method="post">
			<label for="jsonimport">Paste JSON string of import data here:</label><br>
			<textarea name="jsonimport" id="jsonimport" rows="8" cols="40"></textarea>
			<p><input type="submit" name="importsubmit" id="importsubmit" value="Import"></p>
		</form>
	</div>
	<?php include 'subfooter.php'; ?>
</section>

<?php include 'footer.php'; ?>
