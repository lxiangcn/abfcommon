<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">频道列表</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">频道列表</h3>
	</div>
	<div class="panel-body">
		<ul class="note">
			<a data-remote="true" class="btn btn-primary btn-sm" href="<?php echo site_url("archive/channel/add/".$page_no); ?>">添加频道</a>
		</ul>
		<table class="table table-hover table-condensed">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<col />
				<col />
				<col width='120' />
			</colgroup>
			<thead>
				<tr>
					<th>ID</th>
					<th>名称</th>
					<th>标题</th>
					<th>分页数量</th>
					<th>排序</th>
					<th>时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			 	<?php if (count($info_list) > 0): ?>
            	<?php foreach ($info_list as $k => $v): ?>
				<tr>
					<td>
						<strong><?php echo $v->id;?></strong>
					</td>
					<td><?php echo $v->name; ?></td>
					<td><?php echo $v->title; ?></td>
					<td><?php echo $v->page_size; ?></td>
					<td><?php echo $v->sort_order; ?></td>
					<td><?php echo time_diff($v->created); ?></td>
					<td>
						<div aria-label="Small button group" role="group" class="btn-group btn-group-sm">
							<a href="<?php echo site_url('archive/channel/edit/' . $v->id . '/' . $page_no); ?>" class="btn btn-primary btn-sm">编辑</a>
							<a class="btn btn-sm btn-danger data-confirm" data-message="真的要删除吗，不反悔，确定？" data-href="<?php echo site_url('archive/channel/delete/' . $v->id . '/' . $page_no); ?>">删除</a>
						</div>
					</td>
				</tr>
				<?php endforeach; ?>
       			<?php else : ?>
            	<tr>
					<td align="center" colspan="6">暂无记录</td>
				</tr>
       			<?php endif; ?>
			</tbody>
		</table>
		<nav>
			<ul class="pagination">
				<?php if (isset($pagestr) && !empty($pagestr)): ?>
				<?php echo $pagestr; ?>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
</div>
<!-- /内容 -->