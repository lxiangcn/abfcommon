<!DOCTYPE html>
<html>
<head>
<meta content='' name='description'>
<meta charset='UTF-8'>
<meta content='True' name='HandheldFriendly'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title><?php echo !empty($page_title) ? $page_title : $config['site_name']; ?></title>
<base href="<?php echo base_url() ?>" />
<link href="<?php echo theme_url('assets/css/b-min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo theme_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css" />
<?php echo load_css_file($page_css); ?>
<script type="text/javascript" src="<?php echo theme_url('assets/js/jquery/jquery-1.10.2.min.js'); ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo theme_url('assets/js/jquery/jquery-ui.min.js'); ?>" charset="utf-8"></script>
<link href="<?php echo theme_url('assets/js/select2/css/select2.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo theme_url('assets/js/select2/js/select2.min.js'); ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo theme_url('assets/js/select2/js/i18n/zh-CN.js'); ?>" charset="utf-8"></script>
<link href="<?php echo theme_url('assets/js/icheck/all.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo theme_url('assets/js/icheck/icheck.min.js'); ?>" charset="utf-8"></script>
<?php echo load_js_file($page_js); ?>
<script type="text/javascript">

</script>
</head>
<div id="navigation" class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo base_url(); ?>"><?php echo $config['site_name']; ?></a>
		</div>

		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="<?php echo site_url('welcome/index'); ?>">首页</a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if ($this->member_auth->is_login()): ?>
				<li>
					<a href="<?php echo site_url('auth/user/home'); ?>">用户中心</a>
				</li>
				<li>
					<a href="<?php echo site_url('auth/user/logout'); ?>">退出</a>
				</li>
				<?php else: ?>
				<li>
					<a href="<?php echo site_url('auth/user/login'); ?>">登录</a>
				</li>
				<?php endif;?>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</div>
<div class="container">
	<div class="row">
		<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success alert-page">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $this->session->flashdata('success'); ?>
		</div>
		<?php endif;?>
		<?php if ($this->session->flashdata('error') || validation_errors() != FALSE): ?>
		<div class="alert alert-danger alert-page">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $this->session->flashdata('error'); ?>
			<?php echo validation_errors() ? validation_errors() : "" ?>
		</div>
		<?php endif;?>
		<?php if ($this->session->flashdata('notice')): ?>
		<div class="alert alert-warning alert-page">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $this->session->flashdata('notice'); ?>
		</div>
		<?php endif;?>
		<?php echo $body; ?>
	</div>
	<!-- /.row -->
</div>
<!-- /.container -->
<!-- footer -->
<footer>
	<div class="container">
		<div class="row">
			<p>© <?php echo date("Y", time()); ?> Huiber Co.,Ltd , All Rights Reserved.<?php if ($sys_env != 'production'): ?><span style='color: #5cb85c; font-weight: bold;'>(<span class="glyphicon glyphicon-leaf"></span><?php echo $sys_env; ?> )</span> <?php endif;?></p>
			<p>Powered by <?php echo version(); ?></p>
			<p><?php $pos = strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');?>网页执行信息：<?php echo $this->benchmark->elapsed_time(); ?> Second ,内存消耗：{memory_usage},<?php echo count($this->db->queries); ?> queries<?php if ($pos): ?>,&nbsp;Gzip Enable.<?php endif;?></p>
		</div>
	</div>
</footer>
<!-- /footer -->
<!-- script -->
<script type="text/javascript" src="<?php echo theme_url('assets/js/common.js'); ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo theme_url('assets/js/smoothscroll.js'); ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo theme_url('assets/js/bootstrap.js'); ?>" charset="utf-8"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo theme_url('assets/js/html5shiv.min.js'); ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo theme_url('assets/js/respond.min.js'); ?>" charset="utf-8"></script>
<![endif]-->
<!-- /script -->
</body>
</html>