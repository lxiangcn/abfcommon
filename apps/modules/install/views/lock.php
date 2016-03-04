<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>安装提示</title>
<base href="<?php echo base_url() ?>" />
<link href="<?php echo base_url('assets/css/b-min.css'); ?>" rel="stylesheet" type="text/css" />
<style type="text/css">
#container {
	width: 600px;
	margin: 10px auto;
	padding: 20px;
	border: 1px solid #D0D0D0;
	-webkit-box-shadow: 0 0 8px #D0D0D0;
}
</style>
</head>
<body>
	<div id="container">
		<h2>信息提示</h2>
		<div class="alert alert-danger" role="alert">已经成功安装了Huiber CMS，请不要重复安装!</div>
		<p>
			<a id="href" href="javascript:history.back(-1);" class="alert-link">如果您的浏览器没有自动跳转，请点击这里</a>
		</p>
	</div>
</body>
</html>