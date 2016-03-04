<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index"); ?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("auth/role/index"); ?>">角色管理</a>
	</li>
	<li class="active">编辑信息</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			编辑信息
			<span class="small pull-right">
				<a href="<?php echo site_url("auth/role/index"); ?>">返回列表</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('auth/role/edit/' . $id . '/' . $page_no); ?>" accept-charset="UTF-8">
			<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
			<div class="form-group">
				<label for="group_name" class="col-md-2 control-label">用户组名称</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='group_name' id="group_name" value="<?php echo set_value('group_name') == "" ? $data->group_name : set_value('group_name'); ?>" datatype="*0-50" sucmsg=" " />
					<?php if (form_error('group_name')): ?><?php echo form_error('group_name', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">用户组名称</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-md-2 control-label">描述</label>
				<div class="col-md-8">
					<input type='text' class='form-control' name='description' id="description" value="<?php echo set_value('description') == "" ? $data->description : set_value('description'); ?>" datatype="*0-50" sucmsg=" " />
					<?php if (form_error('description')): ?><?php echo form_error('description', '<small class="help-block Validform_checktip Validform_wrong">', '</small>'); ?><?php else: ?><small class="help-block Validform_checktip">用户组描述</small><?php endif;?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">是否可用</label>
				<div class="col-md-8">
					<select name="published" class="form-control" style="width: 100%">
						<option value="1" <?php if ($data->published): ?> selected="selected" <?php endif;?>>可用</option>
						<option value="0" <?php if (!$data->published): ?> selected="selected" <?php endif;?>>禁用</option>
					</select>
					<small class="help-block Validform_checktip">设置是否可用</small>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">角色权限</label>
				<div class="col-md-8 user-access">
					<label class="checkbox-inline">
						<input class="px" type="checkbox" value="checkbox" id="checkall" />
						<span class="lbl">全选</span>
					</label>
					<ol></ol>
					<?php echo $info_list; ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-9">
					<button class="btn btn-primary" type="submit">更新数据</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!--内容-->
<script type="text/javascript">
$(function(){
	$("#checkall").on('click', function() {
		$("input:checkbox").prop("checked", $(this).prop("checked"));
	});
});

function check(id,obj) {
	$(".sub_access_" + id).prop("checked",$(obj).prop("checked"));
}

function checkrelevance(pid, obj) {
	if(true === $(obj).prop("checked")){
		$(".access_" + pid).prop("checked",true);
	}else{
		var _sub = 0;
		$(".sub_access_" + pid).each(function() {
			if($(this).prop("checked")){
				_sub ++;
			}
		});
		//console.log(_sub);
		if(_sub >= 1){
			$(".access_" + pid).prop("checked",true);
		}else{
			$(".access_" + pid).prop("checked",false);
		}
	}
}
</script>