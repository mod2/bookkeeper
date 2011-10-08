<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>Import Books</h1>
		
		<p class="warning">Warning: Importing books will delete all the books and entries you've added to Bookkeeper.</p>
		<form action="<?php echo $args->app_url .'/'. $args->username . '/saveimport/'; ?>" id="import" method="post">
			<label for="jsonimport">Paste the JSON data (from the export page) into this box:</label><br>
			<textarea name="jsonimport" id="jsonimport" rows="8" cols="40"></textarea>
			<p><input type="submit" name="importsubmit" id="importsubmit" value="Import" class="button"></p>
		</form>
	</div>
	<?php include 'subfooter.php'; ?>
</section>

<?php include 'footer.php'; ?>
