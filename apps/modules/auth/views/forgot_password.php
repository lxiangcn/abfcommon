<div class="panel panel-default">
	<div class="panel-heading"><?php echo __('forgot_password_heading'); ?></div>
	<div class="panel-body">
		<?php echo form_open("auth/member/forgot_password", 'class="form-horizontal"'); ?>
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label"><?php echo sprintf(__('forgot_password_email_label'), $identity_label); ?></label>
			<div class="col-sm-5">
				<?php echo form_input($email, set_value('identity'), 'class="form-control"'); ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<?php echo form_submit('submit', __('forgot_password_submit_btn'), 'class="btn btn-primary"'); ?>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<div class="panel-footer">
		<?php echo sprintf(__('forgot_password_subheading'), $identity_label); ?>
	</div>
</div>