<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index"); ?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">后台菜单管理</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">菜单列表</h3>
	</div>
	<div class="panel-body">
		<ul class="note">
			<a data-remote="true" class="btn btn-primary btn-sm" href="<?php echo site_url("dashboard/menu/add/"); ?>">添加菜单</a>
		</ul>
		<table class="table table-hover table-condensed">
			<colgroup>
				<col width='340' />
				<col />
				<col />
				<col />
				<col width='120' />
			</colgroup>
			<thead>
				<tr>
					<th>菜单名称</th>
					<th>Directory/Class</th>
					<th>Method</th>
					<th>是否菜单</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			 	<?php if (count($info_list) > 0): ?>
            	<?php foreach ($info_list as $k => $v): ?>
				<tr>
					<td><?php echo $v->name; ?></td>
					<td><?php echo $v->class; ?></td>
					<td><?php echo $v->method; ?></td>
					<td>
						<span class="label <?php echo $v->is_menu ? "label-success" : "label-default"; ?>"><?php echo $v->is_menu ? "是" : "否"; ?></span>
					</td>
					<td>
						<div aria-label="Small button group" role="group" class="btn-group btn-group-sm">
							<a href="<?php echo site_url('dashboard/menu/edit/' . $v->id); ?>" class="btn btn-primary btn-sm">编辑</a>
							<a rel="nofollow" data-method="delete" data-confirm="真的要删除吗？" class="btn btn-sm btn-danger" href="<?php echo site_url('dashboard/menu/delete/' . $v->id); ?>">删除</a>
						</div>
					</td>
				</tr>
					<?php endforeach;?>
	       			<?php else: ?>
	            	<tr>
					<td align="center" colspan="2">暂无记录</td>
				</tr>
       			<?php endif;?>
			</tbody>
		</table>
	</div>
</div>
<!-- /内容 -->