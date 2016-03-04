<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index"); ?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("dashboard/menu/index"); ?>">后台菜单管理</a>
	</li>
	<li class="active">添加菜单</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			添加菜单
			<span class="small pull-right">
				<a href="<?php echo site_url("dashboard/menu/index"); ?>">返回列表</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(site_url('dashboard/menu/add'), 'id="validation-form" class="form-horizontal"'); ?>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">菜单名称：</label>
			<div class="col-md-8">
				<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? '' : set_value('name'); ?>" />
				<small class="help-block">必填，2-20个字符</small>
			</div>
		</div>
		<div class="form-group">
			<label for="class" class="col-md-2 control-label">模块目录/类名：</label>
			<div class="col-md-8">
				<input type='text' class='form-control' name='class' id="class" value="<?php echo set_value('class') == "" ? '' : set_value('class'); ?>" />
				<small class="help-block">Directory/Class格式，模块目录/类名。目录可以为多级，按照目录格式填写Directory/Directory/Class。注意最后的不能有“/”字符，少于30个字符。</small>
			</div>
		</div>
		<div class="form-group">
			<label for="method" class="col-md-2 control-label">方法名：</label>
			<div class="col-md-8">
				<input type='text' class='form-control' name='method' id="method" value="<?php echo set_value('method') == "" ? '' : set_value('method'); ?>" />
				<small class="help-block">方法名称</small>
			</div>
		</div>
		<div class="form-group">
			<label for="method" class="col-md-2 control-label">显示图标：</label>
			<div class="col-md-8">
				<?php echo form_dropdown('ico', $ico, $ico_name, "class='form-control'"); ?>
				<small class="help-block">选择图标</small>
			</div>
		</div>
		<div class="form-group">
			<label for="parent_id" class="col-md-2 control-label">上级菜单：</label>
			<div class="col-md-8">
				<?php echo form_dropdown('parent_id', $parents, $parent_id, "class='form-control'"); ?>
				<small class="help-block">选择上级分类</small>
			</div>
		</div>
		<div class="form-group">
			<label for="sort_order" class="col-md-2 control-label">排序：</label>
			<div class="col-md-8">
				<input type='text' class='form-control' name='sort_order' id="sort_order" value="<?php echo set_value('sort_order') == "" ? $sort_order : set_value('sort_order'); ?>" />
				<small class="help-block">排序，按数组从小到大排列</small>
			</div>
		</div>
		<div class="form-group">
			<label for="is_menu" class="col-md-2 control-label">是否为菜单</label>
			<div class="col-md-8">
				<div class="radio">
					<label>
						<input class="px" type="radio" name="is_menu" id="is_menu_1" value="1" <?php if ($is_menu): ?> checked="checked" <?php endif;?> />
						<span class="lbl">是</span>
					</label>
				</div>
				<div class="radio">
					<label>
						<input class="px" type="radio" name="is_menu" id="is_menu_2" value="0" <?php if (!$is_menu): ?> checked="checked" <?php endif;?> />
						<span class="lbl">否</span>
					</label>
				</div>
				<small class="help-block">菜单分为导航菜单，权限定义。</small>
			</div>
		</div>
		<div class="form-group">
			<label for="published" class="col-md-2 control-label">是否显示</label>
			<div class="col-md-8">
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_1" value="1" <?php if ($published): ?> checked="checked" <?php endif;?> />
						<span class="lbl">显示</span>
					</label>
				</div>
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_2" value="0" <?php if (!$published): ?> checked="checked" <?php endif;?> />
						<span class="lbl">隐藏</span>
					</label>
				</div>
				<small class="help-block">设置是否显示</small>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-primary" name="commit" type="submit">提交保存</button>
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
		'class':{
			required: false,
			minlength: 0,
			maxlength: 30
		},
		'method':{
			required: false,
			minlength: 0,
			maxlength: 10,
			isEnCode: true
		}
	},
	messages: {
		'name': '不为空，长度为2-20个字符!',
		'class':'最大长度为20个字符',
		'method':'最大长度为10个字符'
	}
});
</script>