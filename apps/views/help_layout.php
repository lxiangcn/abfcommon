<!DOCTYPE html>
<html>
<head>
<meta content='' name='description'>
<meta charset='UTF-8'>
<meta content='True' name='HandheldFriendly'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title>帮助文档 - <?php echo $config['site_name']; ?></title>
<base href="<?php echo base_url() ?>" />
<link href="<?php echo theme_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo theme_url('assets/css/todc-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo theme_url('assets/css/help.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo theme_url('assets/js/jquery/jquery-1.10.2.min.js'); ?>" charset="utf-8"></script>
</head>
<body>
	<div class="navbar navbar-masthead navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
				<a class="navbar-brand" href="<?php echo base_url();?>">帮助文档</a>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
	<div class="container">
		<?php echo $body; ?>
	</div>
	<!-- /.container -->
	<!-- footer -->
	<footer>
		<div class="container">
			<div class="row">
				<p>© <?php echo date("Y", time()) ; ?> Huiber Co.,Ltd , All Rights Reserved.<?php if($sys_env!='production'):?><span style='color: #5cb85c; font-weight: bold;'>(<span class="glyphicon glyphicon-leaf"></span><?php echo $sys_env; ?> )</span> <?php endif;?></p>
				<p>Powered by <?php echo version();?></p>
				<p><?php $pos = strpos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'); ?>网页执行信息：{elapsed_time} Second ,内存消耗：{memory_usage},<?php echo count($this->db->queries); ?> queries<?php if ($pos) : ?>,&nbsp;Gzip Enable.<?php endif;?></p>
			</div>
		</div>
	</footer>
	<!-- /footer -->
	<!-- script -->
	<script type="text/javascript" src="<?php echo theme_url('assets/js/bootstrap.js'); ?>" charset="utf-8"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/html5shiv.min.js'); ?>" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo theme_url('assets/js/respond.min.js'); ?>" charset="utf-8"></script>
	<![endif]-->
	<!-- /script -->
</body>
</html>