<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>History: Finished</h1>

		<nav class="history"><a href="<?php echo $args->app_url . '/' . $args->username . '/hidden'; ?>">Hidden</a></nav>

		<?php if (count($args->finishedBooks) > 0): ?>
		<h3 id="finished">Finished</h3>
		<ul class="allbooks">
		<?php foreach ($args->finishedBooks as $book): ?>
			<li>
				<div class="bookinfo">Finished <?php echo $book->finishedDate; ?> (<?php echo $book->totalDays; ?>)</div>
				<h4><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/book/<?php echo $book->getSlug(); ?>/"><?php echo $book->getTitle(); ?></a></h4>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->hiddenBooks) > 0): ?>
		<h3 id="hidden">Hidden</h3>
		<ul class="allbooks">
		<?php foreach ($args->hiddenBooks as $book): ?>
			<li>
			<div class="bookinfo"><?php echo $book->getStartDate(); ?><?php if ($book->getStartDate() != $book->getLastEntry()): ?>&thinsp;&ndash;&thinsp;<?php echo $book->getLastEntry(); ?><?php endif; ?></div>
				<h4><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></h4>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</section>

<?php include 'footer.php'; ?>
