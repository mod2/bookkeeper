<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>All Books</h1>

		<?php if (count($args->finished_books) > 0): ?>
		<h3>Finished</h3>
		<ul class="allbooks">
			<?php foreach($args->finished_books as $book): ?>
			<li>
				<header>
					<?php $entries = $book->getEntries(); ?>
					<?php $date = date("d M Y", strtotime($entries[count($entries) - 1]->getEntryDate())); ?>
					<?php $entryDate = strtotime($date); ?>
					<?php $endDate = strtotime($book->getEndDate()); ?>
					<div class="bookinfo">Finished <?php echo $date; ?> (<?php echo strval(abs($entryDate - $endDate) / (60*60*24)); ?> days)</div>
					<h4><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></h4>
				</header>
			</li>	
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->books) > 0): ?>
		<h3>Current</h3>
		<ul class="allbooks">
			<?php foreach($args->books as $book): ?>
			<li>
				<header>
					<div class="bookinfo"><?php echo $book->getPercentageComplete(); ?>% (<?php echo $book->getPagesLeft(); ?> pages left)</div>
					<h4><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></h4>
				</header>
			</li>	
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->hidden_books) > 0): ?>
		<h3>Hidden</h3>
		<ul class="allbooks hidden">
			<?php foreach($args->hidden_books as $book): ?>
			<li><h4><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></h4></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</section>

<?php include 'footer.php'; ?>
