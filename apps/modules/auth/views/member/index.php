<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index"); ?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">管理员管理</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">管理员列表</h3>
	</div>
	<div class="panel-body">
		<ul class="note">

		</ul>
		<table class="table table-hover table-condensed">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<col />
				<col />
				<col width='180' />
			</colgroup>
			<thead>
				<tr>
					<th>选择</th>
					<th>用户名</th>
					<th>昵称</th>
					<th>性别</th>
					<th>注册时间</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			 	<?php if (count($info_list) > 0): ?>
            	<?php foreach ($info_list as $k => $v): ?>
				<tr>
					<td>
						<strong><?php echo $v->id; ?></strong>
					</td>
					<td><?php echo $v->username; ?></td>
					<td><?php echo $v->nickname; ?></td>
					<td><?php if ($v->gender == 'male'): ?>男<?php elseif ($v->gender == 'female'): ?>女<?php elseif ($v->gender == ''): ?>未知<?php endif;?></td>
					<td><?php echo time_diff($v->created); ?></td>
					<td>
						<span class="label <?php echo ($v->active == 0) ? "label-default" : ($v->active == 1 ? "label-success" : "label-danger"); ?>"><?php echo ($v->active == 0) ? "待审核" : ($v->active == 1 ? "可用" : "禁用"); ?></span>
					</td>
					<td>
						<div aria-label="small button group" role="group" class="btn-group btn-group-sm">
							<a href="<?php echo site_url('auth/member/audit/' . $v->id . '/' . $page_no); ?>" class="btn btn-warning btn-sm">审核</a>
							<a href="<?php echo site_url('auth/member/edit/' . $v->id . '/' . $page_no); ?>" class="btn btn-primary btn-sm">编辑</a>
							<?php if ($v->id != 1): ?>
							<a rel="nofollow" data-method="delete" data-confirm="真的要删除吗？" class="btn btn-sm btn-danger" href="<?php echo site_url('auth/member/delete/' . $v->id); ?>">删除</a>
							<?php endif;?>
						</div>
					</td>
				</tr>
				<?php endforeach;?>
       			<?php else: ?>
            	<tr>
					<td align="center" colspan="7">暂无记录</td>
				</tr>
       			<?php endif;?>
			</tbody>
		</table>
		<ul class="pagination">
			<?php if (isset($pagestr) && !empty($pagestr)): ?>
			<?php echo $pagestr; ?>
			<?php endif;?>
		</ul>
	</div>
</div>
<!-- /内容 -->