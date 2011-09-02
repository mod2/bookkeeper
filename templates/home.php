	<div id="view" >
		<h1>Home</h1>

		<h3>Today's Reading Goals</h3>
		<ul id="todaysreading">
		<?php foreach ($args->books as $book): ?>
			<?php if ($book->isTodayAReadingDay() && $book->getPagesToday() > 0): ?>
			<li><span class="pages">Page 50 <span class="numpages">(<?php echo Book::getPageString($book->getPagesToday()); ?>)</span></span><span class="booktitle"><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/<?php echo $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></span></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>

		<h3>Awesomeness Incarnate (Goals Already Met)</h3>
		<ul class="smalllist">
		<li><a href="#">Well of Ascension (by 5 pages)</a></li>
		</ul>

		<h3>Off the Hook Today</h3>
		<ul class="smalllist">
		<?php foreach ($args->books as $book): ?>
			<?php if (!$book->isTodayAReadingDay()): ?>
			<li><a href="<?php echo $args->app_url; ?>/<?php echo $args->username; ?>/<?php echo $book->getSlug(); ?>"><?php echo $book->getTitle(); ?></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>
