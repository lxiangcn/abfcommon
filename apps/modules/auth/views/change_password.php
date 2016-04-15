<div class="panel panel-default">
    <div class="panel-heading"><?php echo __('change_password_heading'); ?></div>
    <div class="panel-body">
        <?php echo form_open("auth/user/change_password", 'class="form-horizontal"'); ?>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><?php echo __('change_password_old_password_label', 'old_password'); ?></label>
            <div class="col-sm-5">
                <?php echo form_input($old_password, set_value('old_password'), 'class="form-control"'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="url" class="col-md-2 control-label"><?php echo sprintf(__('change_password_new_password_label'), $min_password_length); ?></label>
            <div class="col-sm-5">
                <?php echo form_input($new_password, set_value('new_password'), 'class="form-control"'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="url" class="col-md-2 control-label"><?php echo __('change_password_new_password_confirm_label', 'new_password_confirm'); ?></label>
            <div class="col-sm-5">
                <?php echo form_input($new_password_confirm, set_value('new_password_confirm'), 'class="form-control"'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-9">
                <?php echo form_input($user_id); ?>
                <?php echo form_submit('submit', __('change_password_submit_btn'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>