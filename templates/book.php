<?php include 'header.php'; ?>

<section id="detail">
	<?php if ($args->edit_mode): ?>
		<?php include 'bookedit.php'; ?>
	<?php else: ?>
		<?php include 'bookview.php'; ?>
	<?php endif; ?>
</section>

<?php include 'footer.php'; ?>
