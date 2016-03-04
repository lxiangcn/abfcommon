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
	<li class="active">分类管理</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			分类列表
			<span class="small pull-right">
				<a href="<?php echo site_url("archive/category/index");?>">返回列表</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<ul class="note">
			<a data-remote="true" class="btn btn-primary btn-sm" href="<?php echo site_url("archive/category/add/".$channel_id.'/'. $page_no); ?>">添加分类</a>
		</ul>
		<table class="table table-hover table-condensed">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<col />
				<col width='180' />
			</colgroup>
			<thead>
				<tr>
					<th>ID</th>
					<th>类别名称</th>
					<th>信息总条数</th>
					<th>排序</th>
					<th>发布状态</th>
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
					<td><?php echo $v->sub_count; ?></td>
					<td><?php echo $v->sort_order; ?></td>
					<td>
						<span class="label <?php echo $v->published ? "label-success" : "label-default"; ?>"><?php echo $v->published ? "显示" : "隐藏"; ?></span>
					</td>
					<td>
						<div aria-label="Small button group" role="group" class="btn-group btn-group-sm">
							<?php echo hasSubCat('category', $v->id) ? "<a class='btn btn-sm btn-success' href='".site_url("archive/category/index/" .$channel_id.'/'. $page_no.'/'. $v->id ) ."'>查看子类</a>" : "<a class='btn btn-sm btn-default' href='javascript:;'>查看子类</a>"; ?>
							<a href="<?php echo site_url('archive/category/edit/'.$channel_id.'/'. $v->id . '/' . $page_no); ?>" class="btn btn-primary btn-sm">编辑</a>
							<a data-message="真的要删除吗？" class="btn btn-sm btn-danger data-confirm" data-href="<?php echo site_url('archive/category/delete/'. $v->id . '/' . $page_no); ?>">删除</a>
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
		<ul class="pagination">
			<?php if (isset($pagestr) && !empty($pagestr)): ?>
			<?php echo $pagestr; ?>
			<?php endif; ?>
		</ul>
	</div>
</div>
<!-- /内容 -->