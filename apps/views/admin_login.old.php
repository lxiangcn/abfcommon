<!DOCTYPE html>
<!--[if IE 8]><html class="ie8"> <![endif]-->
<!--[if IE 9]><html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="gt-ie8 gt-ie9 not-ie"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $config['site_name']; ?> -- 网站后台登录</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<link href="<?php echo theme_url('pixel/stylesheets/bootstrap.min.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/pixel-admin.min.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/pages.min.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/rtl.min.css');?>" rel="stylesheet" type="text/css">
<link href="<?php echo theme_url('pixel/stylesheets/themes.min.css');?>" rel="stylesheet" type="text/css">
<!--[if lt IE 9]>
<script src="<?php echo theme_url('pixel/javascripts/ie.min.js');?>"></script>
<![endif]-->
<style>
#signin-demo {
	position: fixed;
	right: 0;
	bottom: 0;
	z-index: 10000;
	background: rgba(0,0,0,.6);
	padding: 6px;
	border-radius: 3px;
}
#signin-demo img { cursor: pointer; height: 40px; }
#signin-demo img:hover { opacity: .5; }
#signin-demo div {
	color: #fff;
	font-size: 10px;
	font-weight: 600;
	padding-bottom: 6px;
}
</style>
</head>
<body class="theme-default page-signin">
<script>
var init = [];
init.push(function () {
// 	var $div = $('<div id="signin-demo" class="hidden-xs"><div>背景图片</div></div>'),
	    bgs  = [ '<?php echo theme_url('pixel/demo/signin-bg-1.jpg');?>', '<?php echo theme_url('pixel/demo/signin-bg-2.jpg');?>', '<?php echo theme_url('pixel/demo/signin-bg-3.jpg');?>',
	    		 '<?php echo theme_url('pixel/demo/signin-bg-4.jpg');?>', '<?php echo theme_url('pixel/demo/signin-bg-5.jpg');?>', '<?php echo theme_url('pixel/demo/signin-bg-6.jpg');?>',
				 '<?php echo theme_url('pixel/demo/signin-bg-7.jpg');?>', '<?php echo theme_url('pixel/demo/signin-bg-8.jpg');?>', '<?php echo theme_url('pixel/demo/signin-bg-9.jpg');?>' ];
// 	for (var i=0, l=bgs.length; i < l; i++) $div.append($('<img src="' + bgs[i] + '">'));
// 	$div.find('img').click(function () {
// 		var img = new Image();
// 		img.onload = function () {
// 			$('#page-signin-bg > img').attr('src', img.src);
// 			$(window).resize();
// 		}
// 		img.src = $(this).attr('src');
// 	});
// 	$('body').append($div);
});
</script>
	<!-- Page background -->
	<div id="page-signin-bg">
		<!-- Background overlay -->
		<div class="overlay"></div>
		<!-- Replace this with your bg image -->
		<img src="<?php echo theme_url('pixel/demo/signin-bg-1.jpg');?>" alt="">
	</div>
	<!-- / Page background -->
	<!-- Container -->
	<div class="signin-container">
		<!-- Left side -->
		<div class="signin-info">
			<a href="javascript:;" class="logo">
				系统管理
			</a> <!-- / .logo -->
			<div class="slogan">
				用户登录
			</div> <!-- / .slogan -->
			<ul>
				<li><i class="fa fa-sitemap signin-icon"></i> 简洁</li>
				<li><i class="fa fa-file-text-o signin-icon"></i> 高效</li>
				<li><i class="fa fa-outdent signin-icon"></i> 快捷</li>
				<li><i class="fa fa-heart signin-icon"></i> 统一</li>
			</ul> <!-- / Info list -->
		</div>
		<!-- / Left side -->
		<?php echo $body;?>
	</div>
	<!-- / Container -->
	<div class="not-a-member">
		Copyright &copy; <?php echo date("Y", time()); ?><?php if($sys_env!='production'):?><span style='color: #5cb85c; font-weight: bold;'>(<span class="fa fa-leaf"></span><?php echo $sys_env; ?> )</span><?php endif;?>
	</div>
<!-- Get jQuery from Google CDN -->
<!--[if !IE]> -->
	<script type="text/javascript"> window.jQuery || document.write('<script src="<?php echo theme_url('pixel/javascripts/jquery-1.8.3.min.js');?>">'+"<"+"/script>"); </script>
<!-- <![endif]-->
<!--[if lte IE 9]>
	<script type="text/javascript"> window.jQuery || document.write('<script src="assets/javascripts/jquery-1.8.3.min.js');?>">'+"<"+"/script>"); </script>
<![endif]-->
<!-- Pixel Admin's javascripts -->
<script src="<?php echo theme_url('pixel/javascripts/bootstrap.min.js');?>"></script>
<script src="<?php echo theme_url('pixel/javascripts/pixel-admin.min.js');?>"></script>

<script type="text/javascript">
	// Resize BG
	init.push(function () {
		var $ph  = $('#page-signin-bg'),
		    $img = $ph.find('> img');

		$(window).on('resize', function () {
			$img.attr('style', '');
			if ($img.height() < $ph.height()) {
				$img.css({
					height: '100%',
					width: 'auto'
				});
			}
		});
	});
	window.PixelAdmin.start(init);
</script>
</body>
</html>