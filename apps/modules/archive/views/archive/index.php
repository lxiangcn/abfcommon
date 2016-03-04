<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">内容列表</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">内容列表</h3>
	</div>
	<div class="panel-body">
		<ul class="note">
			<a data-remote="true" class="btn btn-primary btn-sm" href="<?php echo site_url("archive/archive/add/".$channel_id.'/'.$page_no); ?>">添加内容</a>
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
					<th>标题</th>
					<th>所属分类</th>
					<th>是否显示</th>
					<th>点击次数</th>
					<th>发布时间</th>
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
					<td><?php echo $v->title; ?></td>
					<td>
				 		<?php
							if (! empty ( $v->category_id )) {
								$mappings = getMappingData ( $v->category_id, 'archive' );
								$name_string = "";
								foreach ( $mappings as $key => $val ) {
									$name_string = $name_string ? ($val->name . "/" . $name_string) : $val->name;
								}
								echo $name_string;
							}
							?>
					</td>
					<td>
						<span class="label <?php echo $v->published ? "label-success" : "label-default"; ?>"><?php echo $v->published ? "显示" : "隐藏"; ?></span>
					</td>
					<td><?php echo $v->hits; ?></td>
					<td><?php echo time_diff($v->created); ?></td>
					<td>
						<div aria-label="small button group" role="group" class="btn-group btn-group-sm">
							<a href="<?php echo site_url('archive/archive/edit/' . $v->id . '/' . $page_no); ?>" class="btn btn-primary btn-sm">编辑</a>
							<a data-message="真的要删除吗？" class="btn btn-sm btn-danger data-confirm" data-href="<?php echo site_url('archive/archive/delete/' . $v->id . '/' . $page_no); ?>">删除</a>
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