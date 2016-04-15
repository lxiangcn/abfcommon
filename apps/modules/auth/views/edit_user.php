<div class="panel panel-default">
  <div class="panel-heading"><?php echo __('edit_user_heading'); ?></div>
  <div class="panel-body">
    <?php echo form_open(uri_string(), 'class="form-horizontal"'); ?>
    <div class="form-group">
      <label for="name" class="col-sm-2 control-label"><?php echo __('edit_user_username_label', 'username'); ?></label>
      <div class="col-sm-5">
        <p class="form-control-static"><?php echo $username; ?></p>
      </div>
    </div>
    <div class="form-group">
      <label for="url" class="col-md-2 control-label"><?php echo __('edit_user_nickname_label', 'nickname'); ?></label>
      <div class="col-sm-5">
        <?php echo form_input($nickname, set_value('nickname'), 'class="form-control"'); ?>
      </div>
    </div>
    <div class="form-group">
      <label for="url" class="col-md-2 control-label"><?php echo __('edit_user_email_label', 'email'); ?></label>
      <div class="col-sm-5">
        <?php echo form_input($email, set_value('email'), 'class="form-control"'); ?>
      </div>
    </div>
    <div class="form-group">
      <label for="url" class="col-md-2 control-label"><?php echo __('edit_user_gender_label', 'gender'); ?></label>
      <div class="col-sm-5">
        <div class="radio">
          <label>
            <input class="px" type="radio" name="gender" id="gender_1" value="1" <?php if ($gender): ?> checked="checked" <?php endif;?> />
            <span class="lbl"><?php echo __('edit_user_gender_label_m'); ?></span>
          </label>
        </div>
        <div class="radio">
          <label>
            <input class="px" type="radio" name="gender" id="gender_0" value="0" <?php if (!$gender): ?> checked="checked" <?php endif;?> />
            <span class="lbl"><?php echo __('edit_user_gender_label_f'); ?></span>
          </label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-offset-2 col-md-9">
        <?php echo form_hidden('id', $user->id); ?>
        <?php echo form_submit('submit', __('edit_user_submit_btn'), 'class="btn btn-primary"'); ?>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
  <div class="panel-footer">
    <?php echo __('edit_user_subheading'); ?>
  </div>
</div>
