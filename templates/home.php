	<div id="view" >
		<h2>Dashboard</h2>
		<ul>
		<?php foreach ($args->books as $book): ?>
			<?php if ($book->isTodayAReadingDay() && $book->getPagesToday() > 0): ?>
			<li><?php echo $book->getTitle(); ?> - <?php echo Book::getPageString($book->getPagesToday()); ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>
