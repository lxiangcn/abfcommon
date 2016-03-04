<html>
<body>
	<h1><?php echo sprintf(__('email_activate_heading'), $identity); ?></h1>
	<p><?php echo sprintf(__('email_activate_subheading'), anchor('auth/activate/' . $id . '/' . $activation, __('email_activate_link'))); ?></p>
</body>
</html>