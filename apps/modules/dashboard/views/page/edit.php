<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("dashboard/page/index");?>">单页面列表</a>
	</li>
	<li class="active">编辑单页面</li>
</ol>
<!--/导航栏-->
<script type="text/javascript" src="<?php echo theme_url('assets/js/editor/kindeditor.js'); ?>" charset="utf-8"></script>
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">添加单页面</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(site_url('dashboard/page/edit/' . $data->id.'/' . $page_no), 'id="validation-form" class="form-horizontal"'); ?>
			<div class="form-group">
			<label for="name" class="col-md-2 control-label">标题</label>
			<div class="col-md-8">
				<input type='text' class='form-control' name='title' id="title" value="<?php echo set_value('title') == "" ? $data->title : set_value('title'); ?>" />
				<small class="help-block">标题</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">是否显示</label>
			<div class="col-md-8">
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
				<small class="help-block">设置是否启用</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">关键词</label>
			<div class="col-md-8">
				<input type='text' class='form-control' name='keywords' id="keywords" value="<?php echo set_value('keywords') == "" ? $data->keywords : set_value('keywords'); ?>" />
	            <small class="help-block">SEO关键词信息</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">描述</label>
			<div class="col-md-8">
				<textarea class="form-control" name="description" rows="2" cols="20" id="description"><?php echo set_value('description') == "" ? $data->description : set_value('description'); ?></textarea>
				<small class="help-block">SEO描述信息</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">内容</label>
			<div class="col-md-10">
				<?php echo load_editor($data->content); ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-primary" name="commit" type="submit">更新单页面</button>
			</div>
		</div>
		<?php echo form_close();?>
	</div>
</div>
<!--内容-->
<script type="text/javascript">
$("#validation-form").validate({
	focusInvalid: false,
	rules: {
		'title': {
			required: true,
			minlength: 2,
			maxlength: 20
		},
		'published':{
			required: true,
			minlength: 0,
			maxlength: 20
		},
		'keywords':{
			required: false,
			minlength: 0,
			maxlength: 20
		},
		'description':{
			required: false,
			minlength: 0,
			maxlength: 20
		}
	},
	messages: {
		'title': '不为空，长度为2-20个字符!',
		'published':'请选择是否显示',
		'keywords':'最大长度为20个字符',
		'description':'最大长度为20个字符'
	}
});
</script>