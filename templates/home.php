	<div id="view" >
		<h1>Today</h1>

		<h3>Today's Reading Goals</h3>
		<ul class="biglist">
		<?php if (count($args->activeBooks) > 0): ?>
		<?php foreach ($args->activeBooks as $book): ?>
			<li><span class="pages">Page <?php echo $book->getToPage(); ?> <span class="numpages">(<?php echo Book::getPageString($book->getPagesToday()); ?>)</span></span><span class="booktitle"><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/book/<?php echo $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></span></li>
		<?php endforeach; ?>
		<?php elseif (count($args->books) > 0): ?>
			<li>Congratulations&mdash;like a champion, you&rsquo;ve smashed your goals for the day. Well played, gentle reader, well played. Now go take a break.</li>
		<?php else: ?>
			<li>Welcome to Bookkeeper. Click on the <a id="addBook" href="<?php echo "{$args->app_url}/{$args->username}/book/add"; ?>">Add Book</a> link to make the magic start happening.</li>
		<?php endif; ?>
		</ul>

		<?php if (count($args->noGoalBooks) > 0): ?>
		<h3>Books Without Goal Dates (But Still Worth Reading)</h3>
		<ul class="biglist">
		<?php foreach ($args->noGoalBooks as $book): ?>
			<li><span class="pages"><?php echo Book::getPageString($book->getPagesLeft()); ?> left</span><span class="booktitle"><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/book/<?php echo $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></span></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->reachedBooks) > 0 || count($args->reachedNoGoalBooks) > 0): ?>
		<h3>Awesomeness Incarnate (Goals Already Met)</h3>
		<ul class="smalllist">
		<?php foreach ($args->reachedBooks as $book): ?>
			<?php if ($book->getPagesToday() == 0): ?>
			<li><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></li>
			<?php else: ?>
			<li><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?> (<?php echo Book::getPageString(abs($book->getPagesToday())); ?> ahead)</a></li>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php foreach ($args->reachedNoGoalBooks as $book): ?>
			<li><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->dormantBooks) > 0): ?>
		<h3>Off the Hook Today</h3>
		<ul class="smalllist">
		<?php foreach ($args->dormantBooks as $book): ?>
			<li><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/book/<?php echo $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
