<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("dashboard/navigation/index");?>">导航管理</a>
	</li>
	<li class="active">添加导航</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">添加导航</h3>
	</div>
	<div class="panel-body">
		<form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('dashboard/navigation/add/' . $page_no); ?>" accept-charset="UTF-8">
			<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">导航名称</label>
				<div class="col-md-6">
					<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? "" : set_value('name'); ?>" datatype="s2-20" nullmsg="必填，长度为2-20个字符" errormsg="必填，长度为2-20个字符" sucmsg=" " />
					<?php if (form_error('name')): ?><?php echo form_error('name', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">导航名称</small><?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="url" class="col-md-2 control-label">条目链接</label>
				<div class="col-md-6">
					<div id='outurl'>
						<input type='text' class='form-control' name='link' id="link" value="<?php echo set_value('link') == "" ? "" : set_value('link'); ?>" datatype="*" nullmsg="请输入URL" sucmsg=" " />
		                <?php if (form_error('url')): ?><?php echo form_error('url', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">如果是站外链接，请直接输入网址，注意要加上http://,链接首页，请输入"/"</small><?php endif; ?>
		            </div>
				</div>
			</div>
			<div class="form-group">
				<label for="url" class="col-md-2 control-label">所在分类</label>
				<div class="col-md-6">
					<?php echo form_dropdown('navigation_cat_id', $cats, $navigation_cat_id, "class='form-control' datatype='n' errormsg='请选择分类' nullmsg='请选择分类' sucmsg=' '"); ?>
					<?php if (form_error('navigation_cat_id')): ?><?php echo form_error('navigation_cat_id', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">所在分类</small><?php endif; ?>
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
				<label for="user_account_attributes_location" class="col-md-2 control-label">链接类型</label>
				<div class="col-md-6 radio">
					<label>
						<input type="radio" name="published" id="published_1" value="1" checked="checked" />
						已启用
					</label>
					<label>
						<input type="radio" name="published" id="published_0" value="0" />
						未启用
					</label>
					<small class="help-block">设置是否启用</small>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-9">
					<button class="btn btn-primary" name="commit" type="submit">添加导航</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!--内容-->