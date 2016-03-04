<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("dashboard/link/index"); ?>">
			<span>链接列表</span>
		</a>
	</li>
	<li class="active">编辑链接</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">添加链接</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(site_url('dashboard/link/edit/' . $data->id . '/' . $page_no),'id="validation-form" class="form-horizontal"'); ?>
			<div class="form-group">
			<label for="name" class="col-md-2 control-label">链接名称</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? $data->name : set_value('name'); ?>" datatype="*2-20" nullmsg="请输入链接名称" sucmsg=" " />
				<small class="help-block">链接名称</small>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label">链接地址</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='url' id="url" value="<?php echo set_value('url') == "" ? $data->url : set_value('url'); ?>" datatype="url" nullmsg="请输入URL" sucmsg=" " />
				<small class="help-block">链接地址</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">显示/隐藏</label>
			<div class="col-md-6">
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_1" value="1" <?php if ($data->published): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">显示</span>
					</label>
				</div>
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_0" value="0" <?php if (!$data->published): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">隐藏</span>
					</label>
				</div>
				<small class="help-block Validform_checktip">*设置是否显示</small>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-primary" name="commit" type="submit">更新链接</button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<!--内容-->
<script type="text/javascript">
$("#validation-form").validate({
	focusInvalid: false,
	rules: {
		'name': {
			required: true,
			minlength: 2,
			maxlength: 20
		},
		'url':{
			required: false,
			url: true 
		}
	},
	messages: {
		'name': '不为空，长度为2-20个字符!',
		'url': {url: '必须为正确的url地址'}
	}
});
</script>