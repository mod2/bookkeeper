	<div id="view" >
		<h1>Home</h1>

		<?php if (count($args->activeBooks) > 0): ?>
		<h3>Today's Reading Goals</h3>
		<ul id="todaysreading">
		<?php foreach ($args->activeBooks as $book): ?>
			<li><span class="pages">Page <?php echo $book->getToPage(); ?> <span class="numpages">(<?php echo Book::getPageString($book->getPagesToday()); ?>)</span></span><span class="booktitle"><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/book/<?php echo $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></span></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if (count($args->reachedBooks) > 0): ?>
		<h3>Awesomeness Incarnate (Goals Already Met)</h3>
		<ul class="smalllist">
		<?php foreach ($args->reachedBooks as $book): ?>
			<?php if ($book->getPagesToday() == 0): ?>
			<li><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></li>
			<?php else: ?>
			<li><a href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?> (<?php echo Book::getPageString(abs($book->getPagesToday())); ?> ahead)</a></li>
			<?php endif; ?>
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
