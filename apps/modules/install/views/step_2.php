<?php if ($errors): ?>
<div class="alert alert-danger" role="alert">
<?php foreach ($errors as $error): ?>
	<p><?php echo $error; ?></p>
<?php endforeach;?>
</div>
<?php endif;?>
<h3 style="margin: 10px 0;">
	<b>1. 请设置你的PHP设置，以符合下列要求。</b>
</h3>
<table class='table table-hover'>
	<colgroup>
		<col/>
		<col width="20%" />
		<col width="20%" />
		<col width="10%" />
	</colgroup>
	<thead>
		<tr>
			<th class="align_left">PHP 配置</th>
			<th>当前设置</th>
			<th>需求设置</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>PHP Version:</td>
			<td class="align_center"><?php echo phpversion(); ?></td>
			<td class="align_center">5.1.6+</td>
			<td class="align_center">
				<?php echo ((phpversion() >= '5.1.6') ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
		<tr>
			<td>Register Globals:</td>
			<td class="align_center"><?php echo (ini_get('register_globals')) ? 'On' : 'Off'; ?></td>
			<td class="align_center">Off</td>
			<td class="align_center">
				<?php echo ((!ini_get('register_globals')) ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
		<tr>
			<td>Magic Quotes GPC:</td>
			<td class="align_center"><?php echo (ini_get('magic_quotes_gpc')) ? 'On' : 'Off'; ?></td>
			<td class="align_center">Off</td>
			<td class="align_center">
				<?php echo ((!ini_get('magic_quotes_gpc')) ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
		<tr>
			<td>File Uploads:</td>
			<td class="align_center"><?php echo (ini_get('file_uploads')) ? 'On' : 'Off'; ?></td>
			<td class="align_center">On</td>
			<td class="align_center">
				<?php echo ((ini_get('file_uploads')) ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
		<tr>
			<td>Session Auto Start:</td>
			<td class="align_center"><?php echo (ini_get('session_auto_start')) ? 'On' : 'Off'; ?></td>
			<td class="align_center">Off</td>
			<td class="align_center">
				<?php echo ((!ini_get('session_auto_start')) ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
	</tbody>
</table>
<h3 style="margin: 10px 0;">
	<b>2. 请确保安装下面列出的扩展。</b>
</h3>
<table class='table table-hover'>
	<colgroup>
		<col/>
		<col width="20%" />
		<col width="20%" />
		<col width="10%" />
	</colgroup>
	<thead>
		<tr>
			<th class="align_left">扩展</th>
			<th>当前设置</th>
			<th>需求设置</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>MySQL:</td>
			<td class="align_center"><?php echo extension_loaded('mysql') ? 'On' : 'Off'; ?></td>
			<td class="align_center">On</td>
			<td class="align_center">
				<?php echo ((extension_loaded('mysql')) ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
		<tr>
			<td>GD:</td>
			<td class="align_center"><?php echo extension_loaded('gd') ? 'On' : 'Off'; ?></td>
			<td class="align_center">On</td>
			<td class="align_center">
				<?php echo ((extension_loaded('gd')) ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
		<tr>
			<td>cURL:</td>
			<td class="align_center"><?php echo extension_loaded('curl') ? 'On' : 'Off'; ?></td>
			<td class="align_center">On</td>
			<td class="align_center">
				<?php echo ((extension_loaded('curl')) ? '<font color="green"><span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red"><span class="glyphicon glyphicon-remove"></span></font>'); ?>
			</td>
		</tr>
	</tbody>
</table>
<h3 style="margin: 10px 0;">
	<b>3. 请确保以下目录设置正确的权限。</b>
</h3>
<table class='table table-hover'>
	<colgroup>
		<col/>
		<col width="10%"/>
	</colgroup>
	<thead>
		<tr>
			<th class="align_left">目录</th>
			<th>状态</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($writable_dirs as $path => $is_writable): ?>
		<tr>
			<td><?php echo $path; ?></td>
			<td class="align_center"><?php echo $is_writable ? '<font color="green">可写<span class="glyphicon glyphicon-ok"></span></font>' : '<font color="red">不可写<span class="glyphicon glyphicon-remove"></span></font>'; ?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
    <?php echo form_open(); ?>
    <input class="btn btn-primary btn-block" type="submit" name="submit" value="下一步" />
    <?php echo form_close(); ?>
	</div>
</div>