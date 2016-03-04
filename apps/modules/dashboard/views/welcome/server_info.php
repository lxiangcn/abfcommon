<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">管理首页</a>
	</li>
	<li class="active">管理中心</li>
	<li class="active">服务器信息</li>
</ol>
<!--/导航栏-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">服务器信息</h3>
	</div>
	<div class="panel-body" id="phpinfo">
		 <?php echo $pinfo; ?>
	</div>
</div>