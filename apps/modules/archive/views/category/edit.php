<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("archive/admin/index");?>">内容列表</a>
	</li>
	<li>
		<a href="<?php echo site_url("archive/category/index");?>">分类管理</a>
	</li>
	<li class="active">编辑分类</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			编辑分类
			<span class="small pull-right">
				<a href="<?php echo site_url("archive/category/index");?>">返回列表</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(site_url('archive/category/edit/'.$channel_id.'/'. $data->id ."/" . $page_no), 'id="validation-form" class="form-horizontal"'); ?>
			<div class="form-group">
			<label for="name" class="col-md-2 control-label">分类名称</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? $data->name : set_value('name'); ?>" datatype="*2-20" nullmsg="不为空，长度为2-20字符" sucmsg=" " />
				<small class="help-block">分类名称</small>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label">上级分类</label>
			<div class="col-md-6">
					<?php echo form_dropdown('parent_id', $parents, $parent_id, "class='form-control' datatype='n' errormsg='请选择分类' nullmsg='请选择分类' sucmsg=' '"); ?>
					<small class="help-block">选择上级分类</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">是否显示</label>
			<div class="col-md-6">
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_1" value="1" <?php if($data->published): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">显示</span>
					</label>
				</div>
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_0" value="0" <?php if(!$data->published): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">隐藏</span>
					</label>
				</div>
				<small class="help-block">设置是否显示</small>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label">排序</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='sort_order' id="sort_order" value="<?php echo set_value('sort_order') == "" ? $data->sort_order : set_value('sort_order'); ?>" datatype="*,n" nullmsg="请输入数字" sucmsg=" " />
				<small class="help-block">排序，只允许为数字</small>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-primary" name="commit" type="submit">提交保存</button>
			</div>
		</div>
		</form>
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
		'parent_id':{
			number: true
		},
		'sort_order':{
			number: true
		}
	},
	messages: {
		'name': '不为空，长度为2-20个字符!',
		'parent_id':'请选择分类',
		'sort_order':'排序字段，只允许为数字'
	}
});
</script>