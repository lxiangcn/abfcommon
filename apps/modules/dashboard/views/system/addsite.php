<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index"); ?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("dashboard/system/site"); ?>">网站配置</a>
	</li>
	<li class="active">添加配置</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			添加配置
			<span class="small pull-right">
				<a href="<?php echo site_url("dashboard/system/site"); ?>">返回</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('dashboard/system/addsite'); ?>" accept-charset="UTF-8">
			<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
			<div class="form-group">
				<label for="group_name" class="col-md-2 control-label">名称</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='tag' id="tag" value="<?php echo set_value('tag') == "" ? '' : set_value('tag'); ?>" datatype="s2-20" errormsg="长度为2-20的英文字符" nullmsg="不为空，长度为2-20字符" sucmsg=" " />
					<?php if (form_error('tag')): ?><?php echo form_error('tag', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">名称</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-md-2 control-label">值域</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='range' id="range" value="<?php echo set_value('range') == "" ? '' : set_value('range'); ?>" datatype="*2-20" errormsg="长度为2-20个字符" nullmsg="不为空，长度为2-20个字符" sucmsg=" " />
					<?php if (form_error('range')): ?><?php echo form_error('range', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">值域</small><?php endif;?>
				</div>
			</div>
				<div class="form-group">
				<label for="description" class="col-md-2 control-label">备注</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='comment' id="comment" value="<?php echo set_value('comment') == "" ? '' : set_value('comment'); ?>" datatype="*2-20" errormsg="长度为2-20个字符" nullmsg="不为空，长度为2-20个字符" sucmsg=" " />
					<?php if (form_error('comment')): ?><?php echo form_error('comment', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">备注</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">类型</label>
				<div class="col-md-8">
					<?php echo form_dropdown('type', $type, 0, "class='form-control' datatype='n' nullmsg='请选择用户角色' sucmsg=' '"); ?>
					<?php if (form_error('type')): ?><?php echo form_error('type', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">选择输入类型</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">分组</label>
				<div class="col-md-8">
					<?php echo form_dropdown('group', $group, 0, "class='form-control' datatype='n' nullmsg='请选择用户角色' sucmsg=' '"); ?>
					<?php if (form_error('group')): ?><?php echo form_error('group', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">选择分组</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-9">
					<button class="btn btn-primary" type="submit">提交保存</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!--内容-->
