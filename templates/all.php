<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>All Books</h1>

		<h3>Finished</h3>
		<ul class="allbooks">
		<?php foreach ($args->finishedBooks as $book): ?>
			<li>
				<div class="bookinfo">Finished <?php echo $book->finishedDate; ?> (<?php echo $book->totalDays; ?>)</div>
				<h4><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/<?php echo $book->slug; ?>/"><?php echo $book->title; ?></a></h4>
			</li>
		<?php endforeach; ?>
		</ul>

		<h3>Current</h3>
		<ul class="allbooks">
		<?php foreach ($args->currentBooks as $book): ?>
			<li>
				<div class="bookinfo"><?php echo $book->percentDone; ?> (<?php echo $book->pagesLeft; ?> left)</div>
				<h4><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/<?php echo $book->slug; ?>/"><?php echo $book->title; ?></a></h4>
			</li>
		<?php endforeach; ?>
		</ul>

		<h3>Hidden</h3>
		<ul class="allbooks hidden">
		<?php foreach ($args->hiddenBooks as $book): ?>
			<li><h4><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/<?php echo $book->slug; ?>/"><?php echo $book->title; ?></a></h4></li>
		<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php include 'footer.php'; ?>
