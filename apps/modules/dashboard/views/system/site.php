<!--导航栏<--></-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index"); ?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">系统设置</li>
</ol>
<!--/导航栏-->
<div class="panel panel-default">
	<div class="panel-body">
		<ul id="myTab" class="nav nav-tabs">
			<?php foreach($site_basic as $k =>$v):?>
				<li  <?php if($k==1):?>class="active"<?php endif; ?>>			
					<a data-toggle="tab" href="#tab_<?php echo $k; ?>"> <?php echo __('sitting_group_' . $k); ?></a>
				</li>
			<?php endforeach ; ?>
		</ul>
		<form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('dashboard/system/site'); ?>" accept-charset="UTF-8">
				<input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">		
				<div class="tab-content">
				<?php foreach ($site_basic  as $k => $v):?>
					<div id="tab_<?php echo $k; ?>"  <?php if($k == 1):;?> class="tab-pane in active" <?php else : ?> class="tab-pane" <?php endif; ?>role="tabpanel">
						<?php foreach($v as $k2 => $v2  ): ?>
							<div class="form-group">
								<label for="" class="col-md-2 control-label"><?php echo __($v2->tag); ?></label>
								<div class="col-md-8">
									<?php if($v2 -> type =="text"):?>
									<input type='text' class='form-control' name='<?php echo $v2->tag; ?>' id="<?php echo $v2->tag; ?>" value="<?php echo $v2->value; ?>" datatype="*2-20" errormsg="昵称至少6个字符,最多18个字符！" nullmsg="请设置站点名称" sucmsg=" " />
										<?php elseif($v2->type=="radio"): ?>
										<?php $ranges=explode(',',$v2->ranges); ?>
										<?php foreach ($ranges as $k3 => $v3):?>
											<div class="radio">
												<label>
													<input class="px" type="radio" name="<?php echo $v2->tag; ?>" id="<?php echo $v2->tag; ?>" value="<?php echo $v3 ?>" <?php if ($v2->value == $v3): ?> checked="checked" <?php endif; ?> />
													<span class="lbl"><?php echo __('sitting_' . $v3); ?></span>
												</label>
											</div>
										<?php endforeach ; ?>
										<?php elseif($v2 ->type == 'select'): ?>
										<?php $ranges=workranges($v2->ranges); ?>
											<?php echo form_dropdown($v2->tag,$ranges,$v2->value,"class='form-control' datatype='n' nullmsg='请选择用户角色' sucmsg=' '"); ?>
										<?php endif ; ?>		
									<small class="help-block Validform_checktip"><?php echo $v2->comment; ?></small>
								</div>
							</div>
						<?php endforeach ; ?>
					</div>		
				<?php endforeach ; ?>	
					<div class="form-group">
						<div class="col-md-offset-2 col-md-9">
							<button class="btn btn-primary" name="commit" type="submit">提交保存</button>
						</div>
					</div>
				</div>
		</form>
	</div>
</div>