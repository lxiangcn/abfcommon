<?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>
<?php foreach ($errors as $error): ?>
<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
<?php endforeach; ?>
<?php echo form_open("", array('class' => 'form-horizontal')); ?>
<h3 style="margin: 10px 0;">
	<b>1 . 请输入网站名称和服务器的信息。</b>
</h3>
<div class="form-group">
	<label for="site_name" class="col-md-2 col-md-offset-1 control-label">网站名称</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'site_name', 'id' => 'site_name', 'value' => set_value('site_name'),'class'=>"form-control","placeholder"=>"Huiber CMS")); ?>
    </div>
</div>

<div class="form-group">
	<label for="site_name" class="col-md-2 col-md-offset-1 control-label">服务器</label>
	<div class="col-md-7">
        <?php echo form_dropdown('server', array('' => '-- 请选择 --', 'apache_wo' => 'Apache (Without mod_rewrite)', 'apache_w' => 'Apache (With mod_rewrite)', 'other' => 'Other'), set_value('server', ($rewrite_support) ? 'apache_w' : 'apache_wo'),'class="form-control"'); ?>
    </div>
</div>
<h3 style="margin: 10px 0;">
	<b>2 . 请输入您的数据库连接的详细信息。</b>
</h3>
<div class="form-group">
	<label for="hostname" class="col-md-2 col-md-offset-1 control-label">数据库服务器</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'hostname', 'id' => 'hostname', 'value' => set_value('hostname', 'localhost'),'class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="username" class="col-md-2 col-md-offset-1 control-label">数据库用户名</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'username', 'id' => 'username', 'value' => set_value('username','root'),'class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="password" class="col-md-2 col-md-offset-1 control-label">数据库密码</label>
	<div class="col-md-7">
        <?php echo form_password(array('name' => 'password', 'id' => 'password', 'value' => set_value('password'),'class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="database" class="col-md-2 col-md-offset-1 control-label">数据库名</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'database', 'id' => 'database', 'value' => set_value('database','huiber'),'class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="port" class="col-md-2 col-md-offset-1 control-label">数据库端口</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'port', 'id' => 'port', 'value' => set_value('port', '3306'),'class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="prefix" class="col-md-2 col-md-offset-1 control-label">表前缀</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'prefix', 'id' => 'prefix', 'value' => set_value('prefix', "hb_"),'class'=>"form-control")); ?>
    </div>
</div>

<h3 style="margin: 10px 0;">
	<b>3. 请输入管理用户名和密码。</b>
</h3>
<div class="form-group">
	<label for="admin" class="col-md-2 col-md-offset-1 control-label">管理员帐号</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'admin', 'id' => 'admin', 'value' => set_value('admin'),'class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="email" class="col-md-2 col-md-offset-1 control-label">管理员E-mail</label>
	<div class="col-md-7">
        <?php echo form_input(array('name' => 'email', 'id' => 'email', 'value' => set_value('email'),'class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="admin_password" class="col-md-2 col-md-offset-1 control-label">密码</label>
	<div class="col-md-7">
        <?php echo form_password(array('name' => 'admin_password', 'id' => 'admin_password','class'=>"form-control")); ?>
    </div>
</div>
<div class="form-group">
	<label for="confirm_admin_password" class="col-md-2 col-md-offset-1 control-label">确认密码</label>
	<div class="col-md-7">
        <?php echo form_password(array('name' => 'confirm_admin_password', 'id' => 'confirm_admin_password','class'=>"form-control")); ?>
    </div>
</div>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<input type="submit" name="submit" class="btn btn-primary btn-block" value="下一步" />
	</div>
</div>
<?php echo form_close(); ?>