<?php include 'header.php'; ?>

<section id="detail">
	<?php if ($args->home_mode): ?>
		<?php include 'home.php'; ?>
	<?php elseif ($args->edit_mode): ?>
		<?php include 'bookedit.php'; ?>
	<?php else: ?>
		<?php include 'bookview.php'; ?>
	<?php endif; ?>
	<?php include 'subfooter.php'; ?>
</section>

<?php include 'footer.php'; ?>
