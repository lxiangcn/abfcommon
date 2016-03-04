<!DOCTYPE html>
<html>
<head>
<meta content='' name='description'>
<meta charset='UTF-8'>
<meta content='True' name='HandheldFriendly'>
<meta content='width=device-width, initial-scale=1.0' name='viewport'>
<title>安装向导 | Powered By Huiber CMS</title>
<base href="<?php echo base_url() ?>" />
<link href="<?php echo base_url('theme/assets/css/b-min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('theme/assets/css/style.css'); ?>" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<ol class="breadcrumb">
						<li <?php echo ($this->uri->segment(1) == 'step1' || $this->uri->segment(1) =='') ? 'class="active"' : ''; ?>>许可协议</li>
						<li <?php echo ($this->uri->segment(1) == 'step2') ? 'class="active"' : ''; ?>>配置检查</li>
						<li <?php echo ($this->uri->segment(1) == 'step3') ? 'class="active"' : ''; ?>>安装配置</li>
						<li <?php echo ($this->uri->segment(1) == 'step4') ? 'class="active"' : ''; ?>>完成</li>
					</ol>
					<div class="panel-body">
						<?php echo $content; ?>
					</div>
					<center class="panel-footer">
						Copyright &copy; <?php echo date('Y',time())?> <a href="http://www.orzm.net">ABF COMMON</a>. All Rights Reserved.
					</center>
				</div>
			</div>
		</div>
	</div>
</body>
</html>