<div class="panel panel-default">
	<div class="panel-heading"><?php echo __('login_heading'); ?></div>
	<div class="panel-body">
		<?php echo form_open("auth/user/login?ref=" . $ref, 'class="form-horizontal"'); ?>
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label"><?php echo __('login_identity_label', 'identity'); ?></label>
			<div class="col-sm-5">
				<?php echo form_input($identity, set_value('identity'), 'class="form-control"'); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label"><?php echo __('login_password_label', 'password'); ?></label>
			<div class="col-sm-5">
				<?php echo form_input($password, set_value('password'), 'class="form-control"'); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label"></label>
			<div class="col-sm-5">
				<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?><?php echo __('login_remember_label', 'remember'); ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<?php echo form_submit('submit', __('login_submit_btn'), 'class="btn btn-primary"'); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label"></label>
			<div class="col-sm-5">
				<a href="<?php echo site_url('auth/user/forgot_password'); ?>"><?php echo __('login_forgot_password'); ?></a>
				&nbsp;&nbsp;
				<a href="<?php echo site_url('auth/user/register'); ?>"><?php echo __('login_register'); ?></a>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<div class="panel-footer">
		<?php echo __('login_subheading'); ?>
	</div>
</div>