<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index"); ?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("auth/admin/index"); ?>">管理员管理</a>
	</li>
	<li class="active">编辑管理员</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			编辑用户
			<span class="small pull-right">
				<a href="<?php echo site_url("auth/admin/index"); ?>">返回列表</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('auth/admin/edit/' . $data->id . '/' . $page_no); ?>" accept-charset="UTF-8">
			<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
			<div class="form-group">
				<label for="url" class="col-md-2 control-label">用户名</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='username' id="username" value="<?php echo set_value('username') == "" ? $data->username : set_value('username'); ?>" datatype="*2-50" nullmsg="请输入文章标题" sucmsg=" " />
					<?php if (form_error('username')): ?><?php echo form_error('username', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">用户名</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">邮箱</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='email' id="email" value="<?php echo set_value('email') == "" ? $data->email : set_value('email'); ?>" datatype="*2-50" nullmsg="Email格式，且为唯一" sucmsg=" " />
					<?php if (form_error('email')): ?><?php echo form_error('email', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">用户邮箱</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="nickname" class="col-md-2 control-label">昵称</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='nickname' id="nickname" value="<?php echo set_value('nickname') == "" ? $data->nickname : set_value('nickname'); ?>" datatype="*2-20" nullmsg="不为空，长度为2-20个字符" sucmsg=" " />
					<?php if (form_error('nickname')): ?><?php echo form_error('nickname', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">用户昵称</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="url" class="col-md-2 control-label">密码</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='password' id="password" value="" datatype="" sucmsg=" " />
					<?php if (form_error('password')): ?><?php echo form_error('password', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">密码为空即不修改密码</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="url" class="col-md-2 control-label">用户分组</label>
				<div class="col-md-8">
					<?php echo form_dropdown('role_id', $roles, $gid, "class='form-control' datatype='n' nullmsg='请选择用户角色' sucmsg=' '"); ?>
					<?php if (form_error('role_id')): ?><?php echo form_error('role_id', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">选择用户角色</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">是否显示</label>
				<div class="col-md-8">
					<div class="radio">
						<label>
							<input class="px" type="radio" name="active" id="active_1" value="1" <?php if ($data->active): ?> checked="checked" <?php endif;?> />
							<span class="lbl">可用</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input class="px" type="radio" name="active" id="active_2" value="0" <?php if (!$data->active): ?> checked="checked" <?php endif;?> />
							<span class="lbl">禁用</span>
						</label>
					</div>
					<small class="help-block Validform_checktip">设置是否显示</small>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">性别</label>
				<div class="col-md-8">
					<div class="radio">
						<label>
							<input class="px" type="radio" name="gender" id="gender_male" value="male" <?php if ($data->gender == 'male'): ?> checked="checked" <?php endif;?> />
							<span class="lbl">男</span>
						</label>
					</div>
					<div class="radio">
						<label>
							<input class="px" type="radio" name="gender" id="gender_female" value="female" <?php if ($data->gender == 'female'): ?> checked="checked" <?php endif;?> />
							<span class="lbl">女</span>
						</label>
					</div>
					<small class="help-block Validform_checktip">设置是否显示</small>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-9">
					<button class="btn btn-primary" name="commit" type="submit">更新用户</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!--内容-->