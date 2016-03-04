<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"><![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="gt-ie8 gt-ie9 not-ie">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $config['site_name']; ?> -- 网站后台管理</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<!-- Pixel Admin's stylesheets -->
<link href="<?php echo theme_url('pixel/stylesheets/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/pixel-admin.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/widgets.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/rtl.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/themes.min.css'); ?>" rel="stylesheet" type="text/css">
<!--[if lt IE 9]>
<script src="<?php echo theme_url('pixel/javascripts/ie.min.js'); ?>"></script>
<![endif]-->
<!--[if !IE]> -->
<script type="text/javascript"> window.jQuery || document.write('<script src="<?php echo theme_url('pixel/javascripts/jquery-2.1.4.min.js'); ?>">'+"<"+"/script>"); </script>
<!-- <![endif]-->
<!--[if lte IE 9]>
<script type="text/javascript"> window.jQuery || document.write('<script src="<?php echo theme_url('pixel/javascripts/jquery-2.1.4.min.js'); ?>">'+"<"+"/script>"); </script>
<![endif]-->
<script src="<?php echo theme_url('pixel/javascripts/bootstrap.min.js'); ?>"></script>
<script src="<?php echo theme_url('pixel/javascripts/pixel-admin.min.js'); ?>"></script>
<script>var init = [];</script>
</head>
<body class="theme-default main-menu-animated main-menu-fixed main-navbar-fixed main-menu-fixed">
	<div id="main-wrapper">
		<div id="main-navbar" class="navbar navbar-inverse" role="navigation">
			<!-- Main menu toggle -->
			<button type="button" id="main-menu-toggle">
				<i class="navbar-icon fa fa-bars icon"></i>
				<span class="hide-menu-text">HIDE MENU</span>
			</button>
			<div class="navbar-inner">
				<!-- Main navbar header -->
				<div class="navbar-header">
					<!-- Logo -->
					<a href="" class="navbar-brand">
					<?php echo $config['site_name']; ?>
				</a>
					<!-- Main navbar toggle -->
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
						<i class="navbar-icon fa fa-bars"></i>
					</button>
				</div>
				<!-- / .navbar-header -->
				<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
					<div>
						<ul class="nav navbar-nav">
							<li>
								<a target="_blank" href="/">网站首页</a>
							</li>
							<!--  
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
							<ul class="dropdown-menu">
								<li><a href="#">First item</a></li>
								<li><a href="#">Second item</a></li>
								<li class="divider"></li>
								<li><a href="#">Third item</a></li>
							</ul>
						</li>
						-->
						</ul>
						<!-- / .navbar-nav -->
						<div class="right clearfix">
							<ul class="nav navbar-nav pull-right right-navbar-nav">
								<li class="nav-icon-btn nav-icon-btn-danger dropdown">
									<a href="#notifications" class="dropdown-toggle" data-toggle="dropdown">
										<span class="label">5</span>
										<i class="nav-icon fa fa-bullhorn"></i>
										<span class="small-screen-text">Notifications</span>
									</a>
									<!-- NOTIFICATIONS -->
									<!-- Javascript -->
									<script>
									init.push(function () {
										$('#main-navbar-notifications').slimScroll({ height: 250 });
									});
								</script>
									<!-- / Javascript -->
									<div class="dropdown-menu widget-notifications no-padding" style="width: 300px">
										<div class="notifications-list" id="main-navbar-notifications">
											<div class="notification">
												<div class="notification-title text-danger">SYSTEM</div>
												<div class="notification-description">
													<strong>Error 500</strong>
													: Syntax error in index.php at line
													<strong>461</strong>
													.
												</div>
												<div class="notification-ago">12h ago</div>
												<div class="notification-icon fa fa-hdd-o bg-danger"></div>
											</div>
											<!-- / .notification -->
											<div class="notification">
												<div class="notification-title text-info">STORE</div>
												<div class="notification-description">
													You have
													<strong>9</strong>
													new orders.
												</div>
												<div class="notification-ago">12h ago</div>
												<div class="notification-icon fa fa-truck bg-info"></div>
											</div>
											<!-- / .notification -->
											<div class="notification">
												<div class="notification-title text-default">CRON DAEMON</div>
												<div class="notification-description">
													Job
													<strong>"Clean DB"</strong>
													has been completed.
												</div>
												<div class="notification-ago">12h ago</div>
												<div class="notification-icon fa fa-clock-o bg-default"></div>
											</div>
											<!-- / .notification -->
											<div class="notification">
												<div class="notification-title text-success">SYSTEM</div>
												<div class="notification-description">
													Server
													<strong>up</strong>
													.
												</div>
												<div class="notification-ago">12h ago</div>
												<div class="notification-icon fa fa-hdd-o bg-success"></div>
											</div>
											<!-- / .notification -->
											<div class="notification">
												<div class="notification-title text-warning">SYSTEM</div>
												<div class="notification-description">
													<strong>Warning</strong>
													: Processor load
													<strong>92%</strong>
													.
												</div>
												<div class="notification-ago">12h ago</div>
												<div class="notification-icon fa fa-hdd-o bg-warning"></div>
											</div>
											<!-- / .notification -->
										</div>
										<!-- / .notifications-list -->
										<a href="#" class="notifications-link">MORE NOTIFICATIONS</a>
									</div>
									<!-- / .dropdown-menu -->
								</li>
								<!-- /3. $END_NAVBAR_ICON_BUTTONS -->
								<li class="dropdown">
									<a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
										<i class="dropdown-icon fa fa-user"></i>
										<span><?php echo $username; ?></span>
									</a>
									<ul class="dropdown-menu">
										<li>
											<a href="<?php echo site_url(); ?>">
												<i class="dropdown-icon fa fa-globe"></i>
												&nbsp;&nbsp;查看网站
											</a>
										</li>
										<li>
											<a href="<?php echo site_url('dashboard/welcome/index'); ?>">
												<i class="dropdown-icon fa fa-dashboard"></i>
												&nbsp;&nbsp;管理首页
											</a>
										</li>
										<li>
											<a target="_blank" href="<?php echo site_url('wiki'); ?>">
												<i class="dropdown-icon fa fa-question-circle"></i>
												&nbsp;&nbsp;帮助文档
											</a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="<?php echo site_url('dashboard/system/clearcache'); ?>">
												<i class="dropdown-icon fa fa-eraser"></i>
												&nbsp;&nbsp;清理缓存
											</a>
										</li>
										<li>
											<a href="<?php echo site_url('dashboard/system/site'); ?>">
												<i class="dropdown-icon fa fa-cog"></i>
												&nbsp;&nbsp;系统设置
											</a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="<?php echo site_url('auth/logout'); ?>">
												<i class="dropdown-icon fa fa-power-off"></i>
												&nbsp;&nbsp;退出
											</a>
										</li>
									</ul>
								</li>
							</ul>
							<!-- / .navbar-nav -->
						</div>
						<!-- / .right -->
					</div>
				</div>
				<!-- / #main-navbar-collapse -->
			</div>
			<!-- / .navbar-inner -->
		</div>
		<!-- / #main-navbar -->
		<!-- /2. $END_MAIN_NAVIGATION -->
		<div id="main-menu" role="navigation">
			<div id="main-menu-inner">
				<ul class="navigation">
				<?php $this->acl->get_left_menu();?>
			</ul>
				<!-- / .navigation -->
				<div class="menu-content animated fadeIn">
					<p>© <?php echo date("Y", time()) ; ?><?php if($sys_env!='production'):?> <span style='color: #5cb85c; font-weight: bold;'>(<i class="fa fa-leaf"></i><?php echo $sys_env; ?> )</span> <?php endif;?></p>
					<p><?php $pos = strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'); ?>网页执行信息：{elapsed_time} Second ,内存消耗：{memory_usage},<?php echo count($this->db->queries); ?> queries<?php if ($pos) : ?>,&nbsp;Gzip Enable.<?php endif;?></p>
				</div>
			</div>
			<!-- / #main-menu-inner -->
		</div>
		<!-- / #main-menu -->
		<!-- /4. $MAIN_MENU -->
		<div id="content-wrapper">
		<?php if ($this->session->flashdata('success') != FALSE): ?>
		<div class="alert alert-success alert-page">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $this->session->flashdata('success');?>
		</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error') != FALSE): ?>
		<div class="alert alert-danger alert-page">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $this->session->flashdata('error');?>
		</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('notice') != FALSE): ?>
		<div class="alert alert-warning alert-page">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $this->session->flashdata('notice');?>
		</div>
		<?php endif; ?>
		<?php if (validation_errors() != FALSE): ?>
		<div class="alert alert-danger alert-page main-menu-fixed">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo validation_errors('<p class="error">', '</p>');?>
		</div>
		<?php endif; ?>
		<?php echo $body;?>
	</div>
		<!-- / #content-wrapper -->
		<div id="main-menu-bg"></div>
	</div>
	<!-- / #main-wrapper -->

	<!-- Pixel Admin's javascripts -->
	<script type="text/javascript">
	init.push(function () {
		$("select").select2({ placeholder: '请选择...'});
		var vclass="<?php echo $css_class;?>";
		if($("a[data-class='"+vclass+"']").parent().parent().parent().length > 0){
			$("a[data-class='"+vclass+"']").parent().parent().parent().addClass('open');
		}
		if($("a[data-class='"+vclass+"']").parent().parent().parent().parent().parent().length > 0){
			$("a[data-class='"+vclass+"']").parent().parent().parent().parent().parent().addClass('open');
		}
		//console.log($("a[data-class='"+vclass+"']").parent().parent().parent().parent().parent().length);
		$('.data-confirm').on('click', function () {
			var url = $(this).attr("data-href");
			var message = $(this).attr("data-message");
			bootbox.confirm({
				message: message,
				callback: function(result) {
					if(result)
						window.location.href = url;
				},
				className: "bootbox-sm"
			});
		});
	})
	window.PixelAdmin.start(init);
</script>
</body>
</html>