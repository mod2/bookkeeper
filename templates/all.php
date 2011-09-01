<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>All Books</h1>

		<?php if (count($args->finishedBooks) > 0): ?>
		<h3>Finished</h3>
		<ul class="allbooks">
		<?php foreach ($args->finishedBooks as $book): ?>
			<li>
				<div class="bookinfo">Finished <?php echo $book->finishedDate; ?> (<?php echo $book->totalDays; ?>)</div>
				<h4><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/book/<?php echo $book->getSlug(); ?>/"><?php echo $book->getTitle(); ?></a></h4>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->currentBooks) > 0): ?>
		<h3>Current</h3>
		<ul class="allbooks">
		<?php foreach ($args->currentBooks as $book): ?>
			<li>
				<div class="bookinfo"><?php echo $book->getPercentageComplete(); ?>% (<?php echo Book::getPageString($book->getPagesLeft()); ?> left)</div>
				<h4><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></h4>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->hiddenBooks) > 0): ?>
		<h3>Hidden</h3>
		<ul class="allbooks hidden">
		<?php foreach ($args->hiddenBooks as $book): ?>
			<li><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</section>

<?php include 'footer.php'; ?>
