	<div id="view" >
		<h1><?php echo $args->current_book->getTitle(); ?></h1>

		<div id="today">
			<input type="hidden" id="currentbookid" value="<?php echo $args->current_book->getBookId(); ?>">
			<div id="goals" name=""><?php if ($args->current_book->getPagesLeft() != 0): ?>I&rsquo;m on page <input type="text" id="currententry" value="<?php echo $args->current_book->getCurrentPage(); ?>" maxlength="5" /><?php endif; ?></div>
			<div id="actionhtml"><?php echo $args->action_html; ?></div>
		</div>

		<ul id="stats">
		<?php $pagesLeft = $args->current_book->getPagesLeft();?>
		<?php $endDate = $args->current_book->getEndDate();?>
		<?php if ($pagesLeft > 0 && $endDate != '0000-00-00'): ?>
			<li><h3>Pages per day</h3><span id="pagesperday"><?php echo $args->current_book->getPagesPerDay(); ?></span></li>
			<li><h3>Pages left</h3><span id="pagesleft"><?php echo $args->current_book->getPagesLeft(); ?></span></li>
			<li><h3>Days left</h3><span id="daysleft"><?php echo $args->current_book->getDaysLeft(); ?></span></li>
			<li><h3>Goal date</h3><span id="goaldate"><?php echo date("j M", strtotime($args->current_book->getEndDate())); ?></span></li>
		<?php elseif ($pagesLeft > 0 && $endDate == '0000-00-00'): ?>
			<li><h3>Pages left</h3><span id="pagesleft"><?php echo $args->current_book->getPagesLeft(); ?></span></li>
			<li><h3>Start date</h3><span id="startdate"><?php echo date("j M", strtotime($args->current_book->getStartDate())); ?></span></li>
		<?php elseif ($pagesLeft == 0): ?>
			<li><h3>Start date</h3><span id="startdate"><?php echo date("j M", strtotime($args->current_book->getStartDate())); ?></span></li>
			<?php if ($endDate != '0000-00-00'): ?>
			<li><h3>Goal date</h3><span id="goaldate"><?php echo date("j M", strtotime($args->current_book->getEndDate())); ?></span></li>
			<?php endif; ?>
		<?php endif; ?>
			<li><h3>Total pages</h3><span id="totalpages"><?php echo $args->current_book->getTotalPages(); ?></span></li>
		</ul>

		<h3>Chart</h3>
		<canvas id="reading_chart"></canvas>

		<h3>Entries</h3>
		<ul id="entries">
		<?php foreach ($args->current_book->getEntries() as $entry): ?>
			<li>Page <?php echo $entry->getPageNumber(); ?> <span class='date'>(<?php echo date("j M", strtotime($entry->getEntryDate())); ?>)</span></li>
		<?php endforeach; ?>
		</ul>

		<footer>
		<a id="editbooklink" name="" href="<?php echo "{$args->app_url}/{$args->username}/book/{$args->current_book->getSlug()}/edit"; ?>">Edit Book</a>
		</footer>
	</div>
