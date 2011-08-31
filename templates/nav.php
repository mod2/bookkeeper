<section id="sidebar">
	<h3>Current Books</h3>
	<ul id="booklist">
	<?php foreach ($args->books as $book): ?>
		<li<?php if ($book->getBookId() == $args->current_book->getBookId()) { echo ' class="selected"'; }?>>
			<a id="book<?php echo $book->getBookId(); ?>" class="booklink" href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?>
				<div class="percentage"><div class="percentage_container"><div class="percent" style="width: <?php echo $book->getPercentageComplete(); ?>px;"></div></div>
				<span><b><?php echo $book->getPercentageComplete(); ?>%</b> (<?php echo $book->getPagesLeft(); ?> pages left)</span></div>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>

	<h3><a href="<?php echo $args->app_url; ?>/all/">All Books</a></h3>

	<h3><a id="addBook" href="<?php echo "{$args->app_url}/{$args->username}/book/add"; ?>">+ Add Book</a></h3>
</section>
