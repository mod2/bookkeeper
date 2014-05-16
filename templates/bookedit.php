	<div id="edit" >
		<h1><?php if ($args->new_book): echo "Add Book"; else: echo $args->current_book->getTitle(); endif; ?></h1>
		<form action="<?php echo $args->app_url . "/{$args->username}/action/savebook"; ?>" method="get" accept-charset="utf-8">
			<input type="hidden" name="editbookid" value="<?php if (!$args->new_book): echo $args->current_book->getBookId(); endif; ?>" id="editbookid">
			<label for="editbookname">Title</label><input type="text" name="editbooktitle" value="<?php if (!$args->new_book): echo $args->current_book->getTitle(); endif; ?>" id="editbooktitle">
			<label for="editbooktotalpages">Total Pages</label><input type="number" name="editbooktotalpages" value="<?php if (!$args->new_book): echo $args->current_book->getTotalPages(); endif; ?>" id="editbooktotalpages" maxlength="5">

			<div style="display: none;">
				<label for="editbookstartdate">Start Date</label><input type="text" name="editbookstartdate" value="<?php if ($args->new_book) { echo date("Y-m-d"); } else { echo $args->current_book->getStartDate(); } ?>" id="editbookstartdate" class="date_input"><small>Format: YYYY-MM-DD</small>
				<label for="editbookenddate">End Date</label><input type="text" name="editbookenddate" value="<?php if (!$args->new_book && $args->current_book->getEndDate() != '0000-00-00'): echo $args->current_book->getEndDate(); endif; ?>" id="editbookenddate" class="date_input" placeholder="YYYY-MM-DD"><small>Format: YYYY-MM-DD</small>
			</div>

			<input type="hidden" name="sunday" id="readingdaysun" value="0" />
			<input type="hidden" name="monday" id="readingdaymon" value="1" />
			<input type="hidden" name="tuesday" id="readingdaytue" value="1" />
			<input type="hidden" name="wednesday" id="readingdaywed" value="1" />
			<input type="hidden" name="thursday" id="readingdaythu" value="1" />
			<input type="hidden" name="friday" id="readingdayfri" value="1" />
			<input type="hidden" name="saturday" id="readingdaysat" value="1" />

			<p><input type="submit" value="Save Book" class="button"></p>

			<footer>
				<ul id="subfooterlinks" <?php if($args->new_book): echo 'style="display:none;"'; endif; ?>>
					<?php if (!$args->new_book && $args->current_book->getHidden()): ?>
					<li><a id="hidebooklink" href="<?php echo $args->app_url . '/' . $args->username . '/action/hidebook/' . $args->current_book->getBookId(); ?>">Show this book</a></li>
					<?php elseif (!$args->new_book && !$args->current_book->getHidden()): ?>
					<li><a id="hidebooklink" href="<?php echo $args->app_url . '/' . $args->username . '/action/hidebook/' . $args->current_book->getBookId(); ?>">Hide this book</a></li>
					<?php endif; ?>
					<li><a id="deletebooklink" href="<?php echo $args->app_url . '/' . $args->username . '/action/deletebook/' . $args->current_book->getBookId(); ?>">Delete this book</a></li>
				</ul>
			</footer>
		</form>
	</div>
