<div class="container login-div">
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo __('login_heading'); ?></h3>
			</div>
			<div class="panel-body">
				<?php echo form_open("auth/admin/login?ref=" . $ref, 'class="form-horizontal"'); ?>
				<?php if ($this->session->flashdata('success') != FALSE): ?>
				<div class="alert alert-success">
        			<?php echo $this->session->flashdata('success'); ?>
        		</div>
        		<?php endif;?>
        		<?php if ($this->session->flashdata('error') != FALSE): ?>
        		<div class="alert alert-danger">
        			<?php echo $this->session->flashdata('error'); ?>
        		</div>
        		<?php endif;?>
        		<?php if ($this->session->flashdata('notice') != FALSE): ?>
        		<div class="alert alert-warning">
        			<?php echo $this->session->flashdata('notice'); ?>
        		</div>
        		<?php endif;?>
        		<?php if (validation_errors() != FALSE): ?>
        		<div class="alert alert-danger">
        			<?php echo validation_errors('<p class="error">', '</p>'); ?>
        		</div>
        		<?php endif;?>
				<?php if ($message != FALSE): ?>
				<div class="alert alert-danger"><?php echo $message; ?></div>
				<?php endif;?>
				<div class="form-group">
					<label class="col-md-2 control-label" for="user_nickname">用户名</label>
					<div class="col-md-10">
						<?php echo form_input($identity, '', "class='form-control'"); ?>
						<span class="help-block red"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="user_password">密码</label>
					<div class="col-md-10">
					 	<?php echo form_input($password, '', "class='form-control'"); ?>
						<span class="help-block red"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="captcha_code">验证码</label>
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-8">
							<?php echo form_input($captcha, '', "class='form-control'"); ?>
							<span class="help-block red"></span>
							</div>
							<div class="col-md-4">
								<a href="javascript:;" id="verifycode" title="更换验证码">
									<img class="verifycode" src="<?php echo site_url() . 'captcha.png'; ?>" id="checkCodeImg" align="absmiddle" alt="点我刷新" title="点我刷新" />
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-9">
						<?php echo form_submit('submit', __('login_submit_btn'), "class='btn btn-primary'"); ?>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
		<div class="login_copyright">
			<p>Copyright &copy; <?php echo date("Y", time()) . "&nbsp;" . version(); ?><?php if ($sys_env != 'production'): ?><span style='color: #5cb85c; font-weight: bold;'>
					(
					<span class="glyphicon glyphicon-leaf"></span><?php echo $sys_env; ?> )</span> <?php endif;?></p>
			<p><?php $pos = strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');?>网页执行信息：<?php echo $this->benchmark->elapsed_time(); ?> Second ,内存消耗：{memory_usage},<?php echo count($this->db->queries); ?> queries<?php if ($pos): ?>,&nbsp;Gzip Enable.<?php endif;?></p>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("#identity").focus();
	$("#verifycode").click(function(){
	 	$("#checkCodeImg").attr('src', '<?php echo site_url() . 'captcha.png'; ?>?' + Math.random());
	});
});
</script>