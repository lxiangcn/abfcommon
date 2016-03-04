<!--导航栏-->
<ol class="breadcrumb breadcrumb-page">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("admin/database/index");?>">数据管理</a>
	</li>
	<li class="active">数据恢复</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">数据管理</h3>
	</div>
	<div class="panel-body">
		<ul class="pagetitle">
			<a data-remote="true" class="btn btn-default btn-sm" href="<?php echo site_url("admin/database/index"); ?>">数据管理</a>
			<a data-remote="true" class="btn btn-default btn-sm" href="<?php echo site_url("admin/database/backup"); ?>">数据备份</a>
			<a data-remote="true" class="btn btn-primary btn-sm" href="javascript:;">数据恢复</a>
		</ul>
		<form action="<?php echo site_url('admin/database/restore'); ?>" method="post">
			<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
			<table class='table table-hover table-condensed'>
				<colgroup>
					<col />
					<col />
					<col />
					<col />
					<col width='140' />
				</colgroup>
				<thead>
					<tr>
						<th>
							<input type="checkbox" id="checkall" value="全选" />
						</th>
						<th>文件名称</th>
						<th>文件大小</th>
						<th>创建日期</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
		            <?php if (isset($info_list) && !empty($info_list)): ?>
		                <?php foreach ($info_list as $k => $v): ?>
					<tr>
						<td>
							<input class="ids" type="checkbox" value="<?php echo $k; ?>" name="<?php echo $k ?>" id="id<?php echo $k; ?>" />
						</td>
						<td>
							<label for="id<?php echo $k; ?>"><?php echo $v['name'] ?></label>
						</td>
						<td><?php echo $v['size'] / 1000 ?>KB</td>
						<td><?php echo time_diff(date('Y-m-d H:i:s', $v['date'])); ?></td>
						<td>
							<div aria-label="Small button group" role="group" class="btn-group btn-group-sm">
								<a class="btn btn-sm btn-primary" href="<?php echo base_url('data/backup/' . $v['name']) ?>" target=_blank>下载</a>
								<a class="btn btn-sm btn-success" href="<?php echo site_url('database/restore/' . $v['name']) ?>">还原数据库</a>
							</div>
						</td>
					</tr>
		                <?php endforeach; ?>
		            <?php else : ?>
		                <tr>
						<td align="center" colspan="9">暂无记录</td>
					</tr>
		            <?php endif; ?>
		    	</tbody>
			</table>
			<div class="form-actions">
				<input class="btn btn-primary" type="submit" value="清除文件" />
			</div>
		</form>
	</div>
</div>
<!-- /内容 -->