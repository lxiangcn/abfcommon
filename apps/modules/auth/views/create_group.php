<h1><?php echo __('create_group_heading');?></h1>
<p><?php echo __('create_group_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/create_group");?>

<p>
            <?php echo __('create_group_name_label', 'group_name');?> <br />
            <?php echo form_input($group_name);?>
      </p>

<p>
            <?php echo __('create_group_desc_label', 'description');?> <br />
            <?php echo form_input($description);?>
      </p>

<p><?php echo form_submit('submit', __('create_group_submit_btn'));?></p>

<?php echo form_close();?>