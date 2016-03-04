<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li class="active">系统设置</li>
</ol>
<!--/导航栏-->
<div class="panel panel-default">
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#tab1">基本资料</a>
			</li>
			<li>
				<a data-toggle="tab" href="#tab2">附件资料</a>
			</li>
			<li>
				<a data-toggle="tab" href="#tab3">资源CDN</a>
			</li>
		</ul>
		<?php echo form_open(null, 'class="form-horizontal"'); ?>
			<div class="tab-content">
			<div id="tab1" class="tab-pane in active" role="tabpanel">
				<div class="form-group">
					<label for="" class="col-md-2 control-label">站点名称</label>
					<div class="col-md-8">
						<input type='text' class='form-control' name='site_name' id="site_name" value="<?php echo $site_basic['site_name']; ?>" datatype="*2-20" errormsg="昵称至少6个字符,最多18个字符！" nullmsg="请设置站点名称" sucmsg=" " />
						<small class="help-block Validform_checktip">*站点名称</small>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">网站地址</label>
					<div class="col-md-8">
						<input class="form-control" name="site_url" type="text" value="<?php echo $site_basic['site_url']; ?>" datatype="url" nullmsg="请设置网站域名" errormsg="请输入正确的网址" sucmsg=" ">
						<small class="help-block Validform_checktip">*结尾需加“/”，如：http://www.orzm.net/</small>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">管理员邮箱</label>
					<div class="col-md-8">
						<input name="admin_email" type="text" id="admin_email" value="<?php echo $site_basic['admin_email']; ?>" size="38" class="form-control" datatype="e" nullmsg="请输入管理员邮箱" errormsg="请输入正确的邮箱" sucmsg=" ">
						<small class="help-block Validform_checktip">*管理员邮箱</small>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">网站关键字</label>
					<div class="col-md-8">
						<input class="form-control" name="keywords" type="text" id="keywords" value="<?php echo $site_basic['keywords']; ?>" datatype="*0-100" sucmsg=" ">
						<small class="help-block Validform_checktip">网站关键词</small>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">网站描述</label>
					<div class="col-md-8">
						<textarea class="form-control" name="description" rows="2" cols="20" id="description" datatype="*0-255" sucmsg=" "><?php echo $site_basic['description']; ?></textarea>
						<small class="help-block Validform_checktip">网站描述信息</small>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">备案编号</label>
					<div class="col-md-8">
						<input class="form-control" name="site_icp" type="text" id="site_icp" value="<?php echo $site_basic['site_icp']; ?>" datatype="*0-20" sucmsg=" " />
						<small class="help-block Validform_checktip">备案编号</small>
					</div>
				</div>
				<!-- <div class="form-group">
					<label for="" class="col-md-2 control-label">首页模式</label>
					<div class="col-md-8 radio">
						<label>
							<input type="radio" name="isrewrite" id="isrewrite_1" value="1" <?php if ($site_basic['isrewrite']): ?> checked="checked" <?php endif; ?> />
							伪静态
						</label>
						<label>
							<input type="radio" name="isrewrite" id="isrewrite_2" value="0" <?php if (!$site_basic['isrewrite']): ?> checked="checked" <?php endif; ?> />
							动态模式
						</label>
						<small class="help-block">伪静态可去掉地址中的index.php 静态规则请查看 根目录下的.htaccess 文件</small>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">伪静态后缀</label>
					<div class="col-md-8">
						<select name="rewritetype" class="form-control" style="width: 100%" datatype="*" nullmsg="请选择目录形式" errormsg="请选择目录形式">
							<option value="" <?php if ($site_basic['rewritetype'] == ''): ?> selected="selected" <?php endif; ?>>目录形式</option>
							<option value=".html" <?php if ($site_basic['rewritetype'] == '.html'): ?> selected="selected" <?php endif; ?>>.html</option>
						</select>
						<small class="help-block">只有选择伪静态模式时有效，暂时只支持.html 以后会增加。 默认不使用后缀名 以目录形式访问</small>
					</div>
				</div> -->
				<div class="form-group">
					<label for="" class="col-md-2 control-label">关闭网站</label>
					<div class="col-md-8">
						<div class="radio">
							<label>
								<input class="px" type="radio" name="site_close" id="site_close_1" value="1" <?php if ($site_basic['site_close']): ?> checked="checked" <?php endif; ?> />
								<span class="lbl">是</span>
							</label>
						</div>
						<div class="radio">
							<label>
								<input class="px" type="radio" name="site_close" id="site_close_2" value="0" <?php if (!$site_basic['site_close']): ?> checked="checked" <?php endif; ?> />
								<span class="lbl">否</span>
							</label>
						</div>
						<small class="help-block">关闭网站后前台页面都将显示下面提示</small>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">提示内容</label>
					<div class="col-md-8">
						<textarea class="form-control" name="site_close_tip" rows="2" cols="20" id="site_close_tip" datatype="*0-255" sucmsg=" "><?php echo $site_basic['site_close_tip']; ?></textarea>
						<small class="help-block Validform_checktip">关闭网站提示内容</small>
					</div>
				</div>
			</div>
			<div id="tab2" class="tab-pane" role="tabpanel">
				<div class="form-group">
					<label for="comment_order" class="col-md-2 control-label">附件路径</label>
					<div class="col-md-8">
						<input class="form-control" name="upload_path" type="text" id="upload_path" value="<?php echo $site_basic['upload_path']; ?>" datatype="*" nullmsg="附件路径不能为空" sucmsg=" " />
						<small class="help-block Validform_checktip">文件上传路径。该路径必须是可写的，相对路径和绝对路径均可以</small>
					</div>
				</div>
				<div class="form-group">
					<label for="is_approve" class="col-md-2 control-label">允许类型</label>
					<div class="col-md-8">
						<input class="form-control" name="allowed_types" type="text" id="allowed_types" value="<?php echo $site_basic['allowed_types']; ?>" datatype="*0-50" sucmsg=" " />
						<small class="help-block Validform_checktip">允许上传文件的MIME类型；允许多个类型用竖线‘|’分开</small>
					</div>
				</div>
				<div class="form-group">
					<label for="settings_pagination_comments" class="col-md-2 control-label">最大文件</label>
					<div class="col-md-8">
						<div class="input-group">
							<input class="form-control" name="upload_max_size" type="text" id="upload_max_size" value="<?php echo $site_basic['upload_max_size']; ?>" />
							<span class="input-group-addon">Kb</span>
						</div>
						<small class="help-block Validform_checktip">允许上传文件大小的最大值（以Kb为单位）</small>
					</div>
				</div>
				<div class="form-group">
					<label for="settings_pagination_comments" class="col-md-2 control-label">重命名文件名</label>
					<div class="col-md-8">
						<select name="upload_encrypt_name" class="form-control" style="width: 100%">
							<option value="1" <?php if ($site_basic['upload_encrypt_name'] == 1): ?> selected="selected" <?php endif; ?>>是</option>
							<option value="0" <?php if ($site_basic['upload_encrypt_name'] == 0): ?> selected="selected" <?php endif; ?>>否</option>
						</select>
						<small class="help-block Validform_checktip">选择是上传的文件将被重命名为随机的加密字符串</small>
					</div>
				</div>
				<div class="form-group">
					<label for="upload_path_format" class="col-md-2 control-label">附件目录格式</label>
					<div class="col-md-8">
						<select id="upload_path_format" name="upload_path_format" class="form-control" style="width: 100%">
							<option value="Y-m-d" <?php if ($site_basic['upload_path_format'] == 'Y-m-d'): ?> selected="selected" <?php endif; ?>>2013-11-22</option>
							<option value="Ymd" <?php if ($site_basic['upload_path_format'] == 'Ymd'): ?> selected="selected" <?php endif; ?>>20131122</option>
							<option value="" <?php if ($site_basic['upload_path_format'] == ''): ?> selected="selected" <?php endif; ?>>不设置目录</option>
						</select>
						<small class="help-block Validform_checktip">如Y-m-d，Y/m-d，Y/m/d，Ymd等形式</small>
					</div>
				</div>
			</div>
			<div id="tab3" class="tab-pane" role="tabpanel">
				<div class="form-group">
					<label for="is_cdn" class="col-md-2 control-label">启用CDN</label>
					<div class="col-md-8">
						<div class="radio">
							<label>
								<input class="px" type="radio" name="is_cdn" id="is_cdn_1" value="1" <?php if ($site_basic['is_cdn']): ?> checked="checked" <?php endif; ?> />
								<span class="lbl">启用</span>
							</label>
						</div>
						<div class="radio">
							<label>
								<input class="px" type="radio" name="is_cdn" id="is_cdn_2" value="0" <?php if (!$site_basic['is_cdn']): ?> checked="checked" <?php endif; ?> />
								<span class="lbl">停用</span>
							</label>
						</div>
						<small class="help-block">对网站资源文件是否启用CDN</small>
					</div>
				</div>
				<div class="form-group">
					<label for="cdn_url" class="col-md-2 control-label">CDN URL</label>
					<div class="col-md-8">
						<input class="form-control" name="cdn_url" type="text" id="cdn_url" value="<?php echo $site_basic['cdn_url']; ?>" datatype="*0-50" sucmsg=" " />
						<small class="help-block Validform_checktip">CDN域名地址，请注意保证所需资源在当前CDN能正常访问。</small>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-9">
					<button class="btn btn-primary" name="commit" type="submit">提交保存</button>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>