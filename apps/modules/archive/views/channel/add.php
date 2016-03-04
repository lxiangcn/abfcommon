<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("archive/channel/index");?>">频道管理</a>
	</li>
	<li class="active">内容列表</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			添加频道
			<span class="small pull-right">
				<a href="<?php echo site_url("archive/channel/index");?>">返回列表</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(site_url('archive/channel/add/'.$page_no  ), 'id="validation-form" class="form-horizontal"'); ?>
			<div class="form-group">
			<label for="name" class="col-md-2 control-label">名称</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? "" : set_value('name'); ?>" datatype="*0-50" sucmsg=" " />
				<?php if (form_error('name')): ?><?php echo form_error('name', '<small class="help-block error">', '</small>'); ?><?php else: ?><small class="help-block">频道名称，频道唯一标识，只能输入英文字符。</small><?php endif; ?>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">标题</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='title' id="title" value="<?php echo set_value('title') == "" ? "" : set_value('title'); ?>" datatype="*0-50" sucmsg=" " />
				<?php if (form_error('title')): ?><?php echo form_error('title', '<small class="help-block error">', '</small>'); ?><?php else: ?><small class="help-block">频道标题</small><?php endif; ?>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">分页数量</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='page_size' id="page_size" value="<?php echo set_value('page_size') == "" ? "10" : set_value('page_size'); ?>" datatype="*0-50" sucmsg=" " />
				<?php if (form_error('page_size')): ?><?php echo form_error('page_size', '<small class="help-block error">', '</small>'); ?><?php else: ?><small class="help-block">列表页每页显示数据数量</small><?php endif; ?>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">排序</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='sort_order' id="sort_order" value="<?php echo set_value('sort_order') == "" ? "0" : set_value('sort_order'); ?>" datatype="*0-50" sucmsg=" " />
				<?php if (form_error('sort_order')): ?><?php echo form_error('sort_order', '<small class="help-block error">', '</small>'); ?><?php else: ?><small class="help-block">数字，越小越靠前</small><?php endif; ?>
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