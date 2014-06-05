<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>History: Hidden</h1>

		<nav class="history"><a href="<?php echo $args->app_url . '/' . $args->username . '/finished'; ?>">Finished</a></nav>

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
