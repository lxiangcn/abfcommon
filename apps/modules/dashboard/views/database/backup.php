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
	<li class="active">数据备份</li>
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
			<a data-remote="true" class="btn btn-primary btn-sm" href="javascript:;">数据备份</a>
			<a data-remote="true" class="btn btn-default btn-sm" href="<?php echo site_url("admin/database/restore"); ?>">数据恢复</a>
		</ul>
		<form action="<?php echo site_url('admin/database/backup'); ?>" method="post">
			<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
			<table class='table table-hover table-condensed'>
				<colgroup>
					<col width='30' />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th>
							<input type="checkbox" id="checkall" value="全选" />
						</th>
						<th>数据表</th>
					</tr>
				</thead>
				<tbody>
	            <?php if (count($info_list) > 0): ?>
	                <?php foreach ($info_list as $k => $v): ?>
					<tr>
						<td>
							<input class="ids" type="checkbox" value="<?php echo $v; ?>" name="<?php echo $k; ?>" id="id<?php echo $k; ?>" />
						</td>
						<td>
							<label for="id<?php echo $k; ?>"><?php echo $v; ?></label>
						</td>
					</tr>
					<?php endforeach; ?>
					<?php else : ?>
			        <tr>
						<td colspan="2">暂无记录</td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<div class="form-actions">
				<input class="btn btn-primary" type="submit" value="开始备份数据库" />
			</div>
		</form>
	</div>
</div>
<!-- /内容 -->