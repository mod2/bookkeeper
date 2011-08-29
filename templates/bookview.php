	<div id="view" >
		<h1></h1>

		<div id="today">
			<div id="goals" name="">I&rsquo;m on page <input type="text" id="currentEntry" value="<?php echo $args->current_book->getCurrentPage(); ?>" maxlength="5" /></div>
			<!--<div id="action" class="action">Read to <span class="pagenum">page <span id="topage"><?php echo $args->current_book->getToPage(); ?></span></span> today <span class="pagecount">(<span id="pagestoday"><?php echo $args->current_book->getPagesToday(); ?></span> pages)</span></div>
			<div id="over" class="action">You&rsquo;re <span class="pagenum"><span id="pagesover"></span> pages</span> over your goal for today.</div>
			<div id="reached" class="action">You&rsquo;ve hit your goal for today.</div>
			<div id="notreadingday" class="action">You&rsquo;re off the hook today.</div> -->
			<?php echo $args->action_html; ?>
		</div>

		<ul id="stats">
			<li><h3>Pages per day</h3><span id="pagesperday"></span></li>
			<li><h3>Pages left</h3><span id="pagesleft">201</span></li>
			<li><h3>Total pages</h3><span id="totalpages">855</span></li>
			<li><h3>Days left</h3><span id="daysleft">5</span></li>
			<li><h3>Goal date</h3><span id="goaldate">23 Aug</span></li>
		</ul>

		<h3>Chart</h3>
		<canvas id="reading_chart"></canvas>

		<h3>Entries</h3>
		<ul id="entries"></ul>

		<footer>
		<a id="editbooklink" name="" href="<?php echo "{$args->app_url}/{$args->username}/book/{$args->current_book->getSlug()}/edit"; ?>">Edit Book</a>
		</footer>
	</div>
