	<div id="edit" >
		<h1><?php if ($args->new_book): echo "Add Book"; else: echo $args->current_book->getTitle(); endif; ?></h1>
		<form action="<?php echo $args->app_url . "/{$args->username}/action/savebook"; ?>" method="get" accept-charset="utf-8">
			<input type="hidden" name="editbookid" value="<?php if (!$args->new_book): echo $args->current_book->getBookId(); endif; ?>" id="editbookid">
			<label for="editbookname">Title</label><input type="text" name="editbooktitle" value="<?php if (!$args->new_book): echo $args->current_book->getTitle(); endif; ?>" id="editbooktitle">
			<label for="editbooktotalpages">Total Pages</label><input type="text" name="editbooktotalpages" value="<?php if (!$args->new_book): echo $args->current_book->getTotalPages(); endif; ?>" id="editbooktotalpages" maxlength="5">
			<label for="editbookstartdate">Start Date</label><input type="text" name="editbookstartdate" value="<?php if ($args->new_book) { echo date("Y-m-d"); } else { echo $args->current_book->getStartDate(); } ?>" id="editbookstartdate" class="date_input"><small>Format: YYYY-MM-DD</small>
			<label for="editbookenddate">End Date</label><input type="text" name="editbookenddate" value="<?php if (!$args->new_book): echo $args->current_book->getEndDate(); endif; ?>" id="editbookenddate" class="date_input" placeholder="YYYY-MM-DD"><small>Format: YYYY-MM-DD</small>

			<h3>Reading Days</h3>
			<ul id="readingdays">
			<li><label for="readingdaysun">S</label><input type="checkbox" name="sunday" id="readingdaysun" value="0" <?php if (!$args->new_book && $args->current_book->getSunday()): echo "checked=checked"; endif; ?>></li>
				<li><label for="readingdaymon">M</label><input type="checkbox" name="monday" id="readingdaymon" value="1" <?php if ($args->new_book || $args->current_book->getMonday()): echo "checked=checked"; endif; ?>></li>
				<li><label for="readingdaytue">T</label><input type="checkbox" name="tuesday" id="readingdaytue" value="2" <?php if ($args->new_book || $args->current_book->getTuesday()): echo "checked=checked"; endif; ?>></li>
				<li><label for="readingdaywed">W</label><input type="checkbox" name="wednesday" id="readingdaywed" value="3" <?php if ($args->new_book || $args->current_book->getWednesday()): echo "checked=checked"; endif; ?>></li>
				<li><label for="readingdaythu">T</label><input type="checkbox" name="thursday" id="readingdaythu" value="4" <?php if ($args->new_book || $args->current_book->getThursday()): echo "checked=checked"; endif; ?>></li>
				<li><label for="readingdayfri">F</label><input type="checkbox" name="friday" id="readingdayfri" value="5" <?php if ($args->new_book || $args->current_book->getFriday()): echo "checked=checked"; endif; ?>></li>
				<li><label for="readingdaysat">S</label><input type="checkbox" name="saturday" id="readingdaysat" value="6" <?php if (!$args->new_book && $args->current_book->getSaturday()): echo "checked=checked"; endif; ?>></li>
			</ul>

			<p><input type="submit" value="Save Book" class="button"></p>

			<ul id="dangerous" <?php if($args->new_book): echo 'style="display:none;"'; endif; ?>>
				<?php if (!$args->new_book && $args->current_book->getHidden()): ?>
				<li><a id="hidebooklink" href="<?php echo $args->app_url . '/' . $args->username . '/action/hidebook/' . $args->current_book->getBookId(); ?>">Show this book</a></li>
				<?php elseif (!$args->new_book && !$args->current_book->getHidden()): ?>
				<li><a id="hidebooklink" href="<?php echo $args->app_url . '/' . $args->username . '/action/hidebook/' . $args->current_book->getBookId(); ?>">Hide this book</a></li>
				<?php endif; ?>
				<li><a id="deletebooklink" href="<?php echo $args->app_url . '/' . $args->username . '/action/deletebook/' . $args->current_book->getBookId(); ?>">Delete this book</a></li>
			</ul>
		</form>
	</div>
