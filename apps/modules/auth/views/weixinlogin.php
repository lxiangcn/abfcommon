<div class="container login-div">
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading ">
				<h3>亲，感谢您加入工委会之家,小会哥哥欢迎你!</h3>
			</div>
			<div class="panel-body">
				<?php echo form_open("auth/weixinlogin/login/".$openid, 'class="form-horizontal"'); ?>
				<?php if ($message): ?>
				<div class="alert alert-danger"><?php echo $message;?></div>
				<?php endif; ?>
				<div class="form-group">
					<label class="col-md-2 control-label" for="nickname" >姓名</label>
					<div class="col-md-10">
						<?php echo form_input($nickname,'',"class='form-control' datatype='s1-5' "); ?>
						<span class="help-block red"><?php //echo form_error('nickname');?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="mobile" >手机号</label>
					<div class="col-md-10">
						<?php echo form_input($mobile,'',"class='form-control' datatype='m'"); ?>
						<span class="help-block red"><?php //echo form_error('mobile');?></span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-9">
						<?php echo form_submit('submit', "  绑定  " ,"class='btn btn-primary btn-lg btn-block'");?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-2 col-md-9">
						<a   class="btn btn-danger btn-lg btn-block" href="<?php echo site_url('leave/leave/index')?>">无法绑定？去申诉</a>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>