<h1><?php echo __('deactivate_heading');?></h1>
<p><?php echo sprintf(__('deactivate_subheading'), $user->username);?></p>

<?php echo form_open("auth/deactivate/".$user->id);?>

<p>
  	<?php echo __('deactivate_confirm_y_label', 'confirm');?>
    <input type="radio" name="confirm" value="yes" checked="checked" />
    <?php echo __('deactivate_confirm_n_label', 'confirm');?>
    <input type="radio" name="confirm" value="no" />
</p>

<?php echo form_hidden($csrf); ?>
  <?php echo form_hidden(array('id'=>$user->id)); ?>

<p><?php echo form_submit('submit', __('deactivate_submit_btn'));?></p>

<?php echo form_close();?>