<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php if($heading) echo $heading;else {?>Error<?php }?></title>

<style type="text/css">
::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }
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
		<h2><?php echo $heading; ?></h2>
		<div class="alert alert-danger" role="alert">
			<?php echo $message; ?>
		</div>
	</div>
</body>
</html>