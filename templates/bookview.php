	<div id="view" >
		<h1><?php echo $args->current_book->getTitle(); ?></h1>

		<div id="today">
			<input type="hidden" id="currentbookid" value="<?php echo $args->current_book->getBookId(); ?>">
			<div id="goals" name="">I&rsquo;m on page <input type="text" id="currententry" value="<?php echo $args->current_book->getCurrentPage(); ?>" maxlength="5" /></div>
			<?php echo $args->action_html; ?>
		</div>

		<ul id="stats">
			<li><h3>Pages per day</h3><span id="pagesperday"><?php echo $args->current_book->getPagesPerDay(); ?></span></li>
			<li><h3>Pages left</h3><span id="pagesleft"><?php echo $args->current_book->getPagesLeft(); ?></span></li>
			<li><h3>Total pages</h3><span id="totalpages"><?php echo $args->current_book->getTotalPages(); ?></span></li>
			<li><h3>Days left</h3><span id="daysleft"><?php echo $args->current_book->getDaysLeft(); ?></span></li>
			<li><h3>Goal date</h3><span id="goaldate"><?php echo date("M d", strtotime($args->current_book->getEndDate())); ?></span></li>
		</ul>

		<h3>Chart</h3>
		<canvas id="reading_chart"></canvas>

		<h3>Entries</h3>
		<ul id="entries">
		<?php foreach ($args->current_book->getEntries() as $entry): ?>
			<li><?php echo date("M d", strtotime($entry->getEntryDate())); ?></li>
		<?php endforeach; ?>
		</ul>

		<footer>
		<a id="editbooklink" name="" href="<?php echo "{$args->app_url}/{$args->username}/book/{$args->current_book->getSlug()}/edit"; ?>">Edit Book</a>
		</footer>
	</div>
