<div class="panel panel-default">
      <div class="panel-heading"><?php echo __('create_user'); ?></div>
      <div class="panel-body">
            <?php echo form_open("auth/user/register?ref=" . $ref, 'class="form-horizontal"'); ?>
            <div class="form-group">
                  <label for="name" class="col-sm-2 control-label"><?php echo __('create_user_username_label', 'username'); ?></label>
                  <div class="col-sm-5">
                        <?php echo form_input($username, set_value('username'), 'class="form-control"'); ?>
                  </div>
            </div>
            <div class="form-group">
                  <label for="url" class="col-md-2 control-label"><?php echo __('create_user_nickname_label', 'nickname'); ?></label>
                  <div class="col-sm-5">
                        <?php echo form_input($nickname, set_value('nickname'), 'class="form-control"'); ?>
                  </div>
            </div>
            <div class="form-group">
                  <label for="url" class="col-md-2 control-label"><?php echo __('create_user_email_label', 'email'); ?></label>
                  <div class="col-sm-5">
                        <?php echo form_input($email, set_value('email'), 'class="form-control"'); ?>
                  </div>
            </div>
            <div class="form-group">
                  <label for="url" class="col-md-2 control-label"><?php echo __('create_user_password_label', 'password'); ?></label>
                  <div class="col-sm-5">
                        <?php echo form_input($password, set_value('password'), 'class="form-control"'); ?>
                  </div>
            </div>
            <div class="form-group">
                  <label for="url" class="col-md-2 control-label"><?php echo __('create_user_password_confirm_label', 'password_confirm'); ?></label>
                  <div class="col-sm-5">
                        <?php echo form_input($password_confirm, set_value('password_confirm'), 'class="form-control"'); ?>
                  </div>
            </div>
            <div class="form-group">
                  <div class="col-md-offset-2 col-md-9">
                        <?php echo form_submit('submit', __('create_user_submit_btn'), 'class="btn btn-primary"'); ?>
                  </div>
            </div>
            <div class="form-group">
                  <label for="url" class="col-md-2 control-label"></label>
                  <div class="col-sm-5">
                        <a href="<?php echo site_url('auth/user/login'); ?>"><?php echo __('create_user_login_label'); ?></a>
                  </div>
            </div>
            <?php echo form_close(); ?>
      </div>
      <div class="panel-footer">
            <?php echo __('create_user_subheading'); ?>
      </div>
</div>