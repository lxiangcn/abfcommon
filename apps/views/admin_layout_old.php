<!DOCTYPE html>
<html>
<head>
<meta content='' name='description'>
<meta charset='UTF-8'>
<meta content='True' name='HandheldFriendly'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title><?php echo $config['site_name']; ?> -- 网站后台管理</title>
<base href="<?php echo base_url() ?>" />
<link href="<?php echo theme_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo theme_url('assets/css/todc-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo theme_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo theme_url('assets/js/select2/css/select2.min.css'); ?>" rel="stylesheet" type="text/css" />
<?php echo load_css_file($page_css);?>
<script type="text/javascript" src="<?php echo theme_url('assets/js/jquery/jquery-1.10.2.min.js'); ?>" charset="utf-8"></script>
</head>
<body>
	<div id="navigation" class="navbar navbar-masthead navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
				<a class="navbar-brand" href="<?php echo base_url();?>"><?php echo $config['site_name'];?></a>
			</div>

			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<?php $this->acl->get_top_menu();?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<?php echo $username; ?> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a target="_blank" href="<?php echo site_url(); ?>">站点首页</a>
							</li>
							<li>
								<a href="<?php echo site_url('dashboard/welcome/index'); ?>">管理首页</a>
							</li>
							<li>
								<a target="_blank" href="<?php echo site_url('wiki'); ?>">帮助文档</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="<?php echo site_url('dashboard/system/clearcache'); ?>">清空缓存</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="<?php echo site_url('auth/logout'); ?>">退出</a>
					</li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php if ($this->session->flashdata('success') != FALSE): ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>提示：</strong>
					<p><?php echo $this->session->flashdata('success')?></p>
				</div>
				<?php endif; ?>
				<?php if ($this->session->flashdata('error') != FALSE): ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>错误：</strong>
					<p><?php echo $this->session->flashdata('error')?></p>
				</div>
				<?php endif; ?>
				<?php if ($this->session->flashdata('notice') != FALSE): ?>
				<div class="alert alert-warning alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong>警告：</strong>
					<p><?php echo $this->session->flashdata('notice')?></p>
				</div>
				<?php endif; ?>
	   			<?php //echo validation_errors(); //表单验证提示?>
   			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="list-group">
					<?php $this->acl->get_left_menu();?>
				</div>
			</div>
			<div class="col-md-9">
				<?php echo $body; ?>
			</div>
		</div>
	</div>
	<!-- /.container -->
	<!-- footer -->
	<footer>
		<div class="container">
			<div class="row">
				<p>© <?php echo date("Y", time()) ; ?> Huiber Co.,Ltd , All Rights Reserved.<?php if($sys_env!='production'):?><span style='color:#5cb85c;font-weight: bold;'>( <span class="glyphicon glyphicon-leaf"></span><?php echo $sys_env; ?> )</span> <?php endif;?></p>
				<p>Powered by <?php echo version();?></p>
				<p><?php $pos = strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'); ?>网页执行信息：{elapsed_time} Second ,内存消耗：{memory_usage},<?php echo count($this->db->queries); ?> queries<?php if ($pos) : ?>,&nbsp;Gzip Enable.<?php endif;?></p>
			</div>
		</div>
	</footer>
	<!-- /footer -->
	<!-- script -->
	<script type="text/javascript" src="<?php echo theme_url('assets/js/select2/js/select2.min.js'); ?>" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/select2/js/i18n/zh-CN.js'); ?>" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/validator/Validform_v5.3.2.js'); ?>" charset="utf-8"></script>
	<?php echo load_js_file($page_js);?>
	<script type="text/javascript">
	var CSRF_TOKEN = '<?php echo $this->security->get_csrf_token_name();?>';
	$(function(){
		$('select').select2({
			language: "zh-CN",
			placeholder: "请选择"
		});
		$(window).resize(function() {
			$('select').select2({
				language: "zh-CN",
				placeholder: "请选择"
			});
		});
		$("form").Validform({
			tiptype:3
		});
	});
	</script>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/common.js'); ?>" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/bootstrap.js'); ?>" charset="utf-8"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/html5shiv.min.js'); ?>" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/respond.min.js'); ?>" charset="utf-8"></script>
	<![endif]-->
	<!-- /script -->
</body>
</html>