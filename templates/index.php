<?php include 'header.php'; ?>

<ul id="booklist">
<?php foreach ($args->books as $book): ?>
	<li<?php if ($args->page == "book" && property_exists($args, 'current_book') && $book->getBookId() == $args->current_book->getBookId()) { echo ' class="selected"'; }?>>
		<div class="goals"><input type="number" pattern="\d*" class="currententry" value="<?php echo $book->getCurrentPage(); ?>" maxlength="5" data-book-id="<?php echo $book->getBookId(); ?>" /></div>

		<a id="book<?php echo $book->getBookId(); ?>" class="booklink" href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?> 
			<div class="percentage">
				<div class="percentage_container">
					<div class="percent" style="width: <?php echo $book->getPercentageComplete(); ?>px;"></div>
				</div>
				<span><b><?php echo $book->getPercentageComplete(); ?>&hairsp;%</b> (<span class="pagesleft"><?php echo $book->getPagesLeft(); ?></span> of <?php echo $book->getTotalPages(); ?> pages left, <?php echo $book->totalDays; ?>)</span>
			</div>
		</a>
	</li>
<?php endforeach; ?>
</ul>

<a id="addBook" href="<?php echo "{$args->app_url}/{$args->username}/book/add"; ?>"><h3>+ Add Book</h3></a>

<?php include 'footer.php'; ?>
