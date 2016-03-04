<!--导航栏-->
<ol class="breadcrumb">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">管理首页</a>
	</li>
	<li>
		<a href="<?php echo site_url("dashboard/page_cat/index");?>">分类列表</a>
	</li>
	<li class="active">添加分类</li>
</ol>
<!--/导航栏-->
<script type="text/javascript" src="<?php echo theme_url('assets/js/editor/kindeditor.js'); ?>" charset="utf-8"></script>
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">添加分类</h3>
	</div>
	<div class="panel-body">
		<form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('dashboard/page_cat/add/'. $page_no); ?>" accept-charset="UTF-8">
			<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">分类名称</label>
				<div class="col-md-6">
					<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? '' : set_value('name'); ?>" datatype="*2-10" nullmsg="不为空，长度为2-20个字符" sucmsg=" " />
					<?php if (form_error('name')): ?><?php echo form_error('name', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">分类名称</small><?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="url" class="col-md-2 control-label">上级分类</label>
				<div class="col-md-6">
					<?php echo form_dropdown('parent_id', $parents, $parent_id, "class='form-control' datatype='n' errormsg='请选择分类' nullmsg='请选择分类' sucmsg=' '"); ?>
					<?php if (form_error('parent_id')): ?><?php echo form_error('parent_id', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">选择上级分类</small><?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">是否显示</label>
				<div class="col-md-6 radio">
					<label>
						<input type="radio" name="published" id="published_1" value="1" <?php if($published): ?> checked="checked" <?php endif; ?> />
						显示
					</label>
					<label>
						<input type="radio" name="published" id="published_0" value="0" <?php if(!$published): ?> checked="checked" <?php endif; ?> />
						隐藏
					</label>
					<small class="help-block">设置是否显示</small>
				</div>
			</div>
			<div class="form-group">
				<label for="url" class="col-md-2 control-label">排序</label>
				<div class="col-md-6">
					<input type='text' class='form-control' name='sort_order' id="sort_order" value="<?php echo set_value('sort_order') == "" ? $sort_order : set_value('sort_order'); ?>" datatype="*,n" nullmsg="请输入数字" sucmsg=" " />
					<?php if (form_error('sort_order')): ?><?php echo form_error('sort_order', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">排序，只允许为数字</small><?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-9">
					<button class="btn btn-primary" name="commit" type="submit">添加分类</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!--内容-->