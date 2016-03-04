<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("welcome/index");?>">管理首页</a>
	</li>
	<li>
		<a href="<?php echo site_url("navigation/index");?>">导航管理</a>
	</li>
	<li>
		<a href="<?php echo site_url("navigation_cat/index");?>">导航分类</a>
	</li>
	<li class="active">导航分类</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">导航分类</h3>
	</div>
	<div class="panel-body">
		<ul class="pagetitle">
			<a data-remote="true" class="btn btn-primary btn-sm" href="<?php echo site_url("navigation_cat/add/" . $page_no); ?>">添加分类</a>
		</ul>
		<table class="table table-hover table-condensed">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<col />
				<col />
				<col width='80' />
			</colgroup>
			<thead>
				<tr>
					<th>ID</th>
					<th>类别名称</th>
					<th>类别标识</th>
					<th>类别描述</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			 	<?php if (count($info_list) > 0): ?>
            	<?php foreach ($info_list as $k => $v): ?>
				<tr>
					<td>
						<strong><?php echo $v['id'];?></strong>
					</td>
					<td><?php echo htmlspecialchars($v['name']); ?></td>
					<td><?php echo $v['cat_key']; ?></td>
					<td><?php echo $v['descript']; ?></td>
					<td>
						<span class="label <?php echo $v['published'] ? "label-success" : "label-default"; ?>"><?php echo $v['published'] ? "显示" : "隐藏"; ?></span>
					</td>
					<td>
						<div aria-label="Small button group" role="group" class="btn-group btn-group-sm">
							<a href="<?php echo site_url('navigation_cat/edit/' . $v['id'] . '/' . $page_no); ?>" class="btn btn-primary btn-sm">编辑</a>
							<a href="<?php echo site_url('navigation_cat/items/' . $v['id'] . '/' . $page_no); ?>" class="btn btn-info btn-sm">配置</a>
							<a rel="nofollow" data-method="delete" data-confirm="真的要删除吗？" class="btn btn-sm btn-danger" href="javascript:del_one(<?php echo $v['id']; ?>,'<?php echo setting("admin_folder") ?>/navigation_cat');">删除</a>
						</div>
					</td>
				</tr>
				<?php endforeach; ?>
       			<?php else : ?>
            	<tr>
					<td align="center" colspan="7">暂无记录</td>
				</tr>
       			<?php endif; ?>
			</tbody>
		</table>
		<ul class="pagination">
			<?php if (isset($pagestr) && !empty($pagestr)): ?>
			<?php echo $pagestr; ?>
			<?php endif; ?>
		</ul>
	</div>
</div>
<!-- /内容 -->