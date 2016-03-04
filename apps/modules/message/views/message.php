<?php if ($message_type == "error"): ?>
<div class="alert alert-danger alert-page">
	<?php echo $message;?>
</div>
<?php endif; ?>
<?php if ($message_type == "success"): ?>
<div class="alert alert-success alert-page">
	<?php echo $message;?>
</div>
<?php endif; ?>
<?php if ($message_type == "notice"): ?>
<div class="alert alert-warning alert-page">
	<?php echo $message;?>
</div>
<?php endif; ?>