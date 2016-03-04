<!--导航栏-->
<ul class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">管理中心</li>
</ul>
<!--/导航栏-->
<?php
$stime = date ( 'Y-m-d H:i:s' );
// 检测函数支持
function isfun($funName = '') {
	if (! $funName || trim ( $funName ) == '' || preg_match ( '~[^a-z0-9\_]+~i', $funName, $tmp )) return '错误';
	return (false !== function_exists ( $funName )) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"></span>' : '<span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red;"></span>';
}
// 检测PHP设置参数
function show($varName) {
	switch ($result = get_cfg_var ( $varName )) {
		case 0 :
			return '<font color="red">×</font>';
			break;
		
		case 1 :
			return '<font color="green">√</font>';
			break;
		
		default :
			return $result;
			break;
	}
}
?>
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">欢迎进入后台管理</h3>
	</div>
	<div class="panel-body">
		<ul class="panel-padding">
			<li>您好，<?php echo $username; ?></li>
			<li>
				<b>Email地址：</b><?php echo $email; ?></li>
			<li>
				<b>上次访问时间：</b><?php echo time_diff($last_login); ?>
			</li>
		</ul>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">配置信息</h3>
	</div>
	<div class="panel-body">
		<ul class="panel-padding">
			<li>服务器域名/IP：<?php echo $_SERVER['SERVER_NAME'];?>(<?php if('/'==DIRECTORY_SEPARATOR){echo $_SERVER['SERVER_ADDR'];}else{echo @gethostbyname($_SERVER['SERVER_NAME']);} ?>)</li>
			<li>服务器当前时间：<?php echo $stime?></li>
			<li>服务器主机名：<?php $os = explode(" ", php_uname()); ?><?php if('/'==DIRECTORY_SEPARATOR ){echo $os[1];}else{echo $os[2];} ?></li>
			<li>操作系统：  <?php echo $os[0];?> 内核版本：<?php if('/'==DIRECTORY_SEPARATOR){echo $os[2];}else{echo $os[1];} ?></li>
			<li>PHP支持：<?php echo PHP_VERSION;?> (<a target="_blank" href="<?php echo site_url('dashboard/welcome/server_info');?>">PHPINFO</a>
				)
			</li>
			<li>服务器解译引擎：<?php echo $_SERVER['SERVER_SOFTWARE'];?></li>
			<li>GD库支持：<?php
			if (@function_exists ( gd_info )) {
				$gd_info = @gd_info ();
				echo $gd_info ["GD Version"];
			} else {
				echo '<font color="red"><i class="glyphicon glyphicon-remove"></i></font>';
			}
			?></li>
			<li>上传最大限制：<?php echo show("post_max_size");?></li>
		</ul>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">官方消息</h3>
	</div>
	<div class="panel-body">
		<ul class="panel-padding">
			<?php echo Modules::run('test/test/index'); ?>
		</ul>
	</div>
</div>

<!--/内容-->