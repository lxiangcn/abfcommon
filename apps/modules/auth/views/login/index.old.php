<!-- Right side -->
<div class="signin-form">
<?php if ($message != FALSE): ?>
<div class="col-md-12">
<div class="alert alert-danger"><?php echo $message;?></div>
</div>
<?php endif; ?>
<!-- Form -->
<?php echo form_open("auth/admin", 'id="signin-form_id" class="form-horizontal"'); ?>
	<div class="form-group">
		<div class="col-md-12">
			<?php echo form_input($identity,'',"id='username_id' class='form-control input-lg' placeholder='用户名'"); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-12">
			<?php echo form_input($password,'',"id='password_id' class='form-control input-lg' placeholder='密码'");?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-8">
			<?php echo form_input($captcha,'',"id='captcha_id' class='form-control input-lg' placeholder='验证码'");?>
		</div>
		<div class="col-md-4">
			<a href="javascript:reloadcode();" title="更换验证码">
				<img class="verifycode" src="<?php echo site_url().'captcha.png'; ?>" id="checkCodeImg" align="absmiddle" alt="点我刷新" title="点我刷新" />
			</a>
		</div>
	</div>

	<div class="form-actions">
		<input type="submit" value="登录" class="signin-btn bg-primary">
	</div>
	<?php echo form_close(); ?>
	<!-- / Form -->
</div>
<!-- Right side -->
<script type="text/javascript">
function reloadcode() {
 	var verify = document.getElementById('checkCodeImg');
 	verify.setAttribute('src', '<?php echo site_url().'captcha.png'; ?>?' + Math.random());
}
// Setup Sign In form validation
init.push(function () {
	$("#signin-form_id").validate({ focusInvalid: true, errorPlacement: function () {} });
});
</script>