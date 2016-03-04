<!--导航栏-->
<ol class="breadcrumb">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">模块管理</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">模块列表</h3>
	</div>
	<div class="panel-body">
		<table class="table table-hover table-condensed">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<!-- <col width='120' /> -->
			</colgroup>
			<thead>
				<tr>
					<th>模块名</th>
					<th>目录</th>
					<th>定位</th>
					<th>描述</th>
					<!-- <th>操作</th> -->
				</tr>
			</thead>
			<tbody>
				<?php foreach ($modules as $slug => $row): ?>
				<tr>
					<td><?php echo $row['module_name'] ?></td>
					<td><?php echo $slug ?></td>
					<td>
						<label class="badge badge-<?php echo ($row['location'] == 'dev')? 'warning': 'primary'; ?>"><?php echo $row['location']; ?></label>
					</td>
					<td><?php echo $row['description']; ?></td>
					<!-- <td><a href="<?php echo site_url('module_manager/admin'); ?>" class="btn btn-sm btn-primary">管理</a></td> -->
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<!-- /内容 -->