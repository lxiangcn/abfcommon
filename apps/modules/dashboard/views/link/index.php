<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">友情链接</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">链接列表</h3>
	</div>
	<div class="panel-body">
		<ul class="note">
			<a data-remote="true" class="btn btn-primary btn-sm" href="<?php echo site_url("dashboard/link/add/" . $page_no); ?>">添加链接</a>
		</ul>
		<table class="table table-hover table-condensed">
			<colgroup>
				<col />
				<col />
				<col />
				<col />
				<col width='120' />
			</colgroup>
			<thead>
				<tr>
					<th>ID</th>
					<th>链接名称</th>
					<th>网址</th>
					<th>显示</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			 	<?php if (count($items) > 0): ?>
            	<?php foreach ($items as $k => $v): ?>
				<tr>
					<td>
						<strong><?php echo $v->id;?></strong>
					</td>
					<td>
						<a href="<?php echo $v->url; ?>"> <?php echo $v->name; ?> </a>
					</td>
					<td>
						<a target="_blank" title="<?php echo $v->name; ?>" class="rabel profile_link" href="<?php echo $v->url; ?>"> <?php echo $v->url; ?> </a>
					</td>
					<td>
						<span class="label <?php echo $v->published ? "label-success" : "label-default"; ?>"><?php echo $v->published ? "显示" : "隐藏"; ?></span>
					</td>
					<td>
						<div aria-label="Small button group" role="group" class="btn-group btn-group-sm">
							<a href="<?php echo site_url('dashboard/link/edit/' . $v->id . '/' . $page_no); ?>" class="btn btn-primary btn-sm">编辑</a>
							<a rel="nofollow" data-method="delete" data-confirm="真的要删除吗？" class="btn btn-sm btn-danger" href="<?php echo site_url('dashboard/link/delete/' . $v->id . '/' . $page_no); ?>">删除</a>
						</div>
					</td>
				</tr>
				<?php endforeach; ?>
       			<?php else : ?>
            	<tr>
					<td align="center" colspan="5">暂无记录</td>
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