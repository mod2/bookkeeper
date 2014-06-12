<?php include 'header.php'; ?>

<section id="detail">
	<div id="view">
		<h1>Hidden</h1>

		<nav class="history"><a href="<?php echo $args->app_url . '/' . $args->username . '/finished'; ?>">Finished &rarr;</a></nav>

		<?php if (count($args->hiddenBooks) > 0): ?>
		<ul class="allbooks archive">
		<?php $lastYear = ''; ?>
		<?php foreach ($args->hiddenBooks as $book): ?>
			<?php if ($book->startYear != $lastYear): ?>
			<li class="heading"><h2><?php echo $book->startYear; ?></h2></li>
			<?php $lastYear = $book->startYear; ?>
			<?php endif; ?>
			<li>
				<div class="bookinfo"><?php echo $book->getStartDate(); ?><?php if ($book->getStartDate() != $book->getLastEntry()): ?>&thinsp;&ndash;&thinsp;<?php echo $book->getLastEntry(); ?><?php endif; ?></div>
				<a id="book<?php echo $book->getBookId(); ?>" class="booklink" href="<?php echo $args->app_url . '/' . $args->username . '/book/' . $book->getSlug(); ?>"><?php echo $book->getTitle(); ?> 
					<div class="percentage">
						<div class="percentage_container">
							<div class="percent" style="width: <?php echo $book->getPercentageComplete(); ?>px;"></div>
						</div>
						<span><b><?php echo $book->getPercentageComplete(); ?>&hairsp;%</b> (<span class="pagesleft"><?php echo $book->getPagesLeft(); ?></span> of <?php echo $book->getTotalPages(); ?> pages)</span>
					</div>
				</a>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</section>

<?php include 'footer.php'; ?>
