<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("welcome/index");?>">管理首页</a>
	</li>
	<li>
		<a href="<?php echo site_url("navigation/index");?>">导航管理</a>
	</li>
	<li>
		<a href="<?php echo site_url("navigation_cat/index");?>">导航分类</a>
	</li>
	<li class="active">添加分类</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">添加分类</h3>
	</div>
	<div class="panel-body">
		<form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('navigation_cat/add/' . $page_no); ?>" accept-charset="UTF-8">
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">分类标识</label>
				<div class="col-md-6">
					<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? $data['name'] : set_value('name'); ?>" datatype="s2-20" nullmsg="必填，长度为2-20个字符" errormsg="必填，长度为2-20个字符" sucmsg=" " />
					<?php if (form_error('name')): ?><?php echo form_error('name', '<small class="help-block error">', '</small>'); ?><?php else: ?><small class="help-block">分类标识，只允许为字母</small><?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-md-2 control-label">分类名称</label>
				<div class="col-md-6">
					<input type='text' class='form-control' name='name' id="name" value="<?php echo set_value('name') == "" ? $data['name'] : set_value('name'); ?>" datatype="s2-20" nullmsg="必填，长度为2-20个字符" errormsg="必填，长度为2-20个字符" sucmsg=" " />
					<?php if (form_error('name')): ?><?php echo form_error('name', '<small class="help-block error">', '</small>'); ?><?php else: ?><small class="help-block">分类名称</small><?php endif; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_account_attributes_location" class="col-md-2 control-label">链接类型</label>
				<div class="col-md-6">
					<label class="radio-inline">
						<input type="radio" name="published" id="published_1" value="1" <?php if($data['published']): ?> checked="checked" <?php endif; ?> />
						显示
					</label>
					<label class="radio-inline">
						<input type="radio" name="published" id="published_0" value="0" <?php if(!$data['published']): ?> checked="checked" <?php endif; ?> />
						隐藏
					</label>
					<small class="help-block">设置是否显示</small>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-9">
					<button class="btn btn-primary" name="commit" type="submit">添加分类</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!--内容-->














<!--内容-->
<div class="content-tab-wrap">
	<div id="floatHead" class="content-tab">
		<div class="content-tab-ul-wrap">
			<ul>
				<li>
					<a href="javascript:;" onclick="tabs(this);" class="selected">基本信息</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="tab-content">
	<dl>
		<dt>分类标识：</dt>
		<dd>
			<input type='text' class='input normal' name='cat_key' id="cat_key" value="<?php echo set_value('cat_key'); ?>" datatype="/^[a-zA-Z_-]{2,10}$/i" nullmsg="必填，请输入字母、“_”、“-”等字串,长度为2-10个字符" errormsg="必填，请输入字母、“_”、“-”等字串,长度为2-10个字符" sucmsg=" " />
            <?php if(form_error('cat_key')): ?><?php echo form_error('cat_key', '<span class="Validform_checktip">', '</span>'); ?><?php endif; ?>
        </dd>
	</dl>
	<dl>
		<dt>分类名称：</dt>
		<dd>
			<input type='text' class='input normal' name='name' id="name" value="<?php echo set_value('name'); ?>" datatype="s2-20" nullmsg="必填，长度为2-20个字符" errormsg="必填，长度为2-20个字符" sucmsg=" " />
            <?php if(form_error('name')): ?><?php echo form_error('name', '<span class="Validform_checktip">', '</span>'); ?><?php endif; ?>
        </dd>
	</dl>
	<dl>
		<dt>是否启用：</dt>
		<dd>
			<div class="rule-multi-radio">
				<input type="radio" name="published" id="published_1" value="1" <?php if($published): ?> checked="checked" <?php endif; ?> />
				<label for="published_1">已启用</label>
				<input type="radio" name="published" id="published_0" value="0" <?php if(!$published): ?> checked="checked" <?php endif; ?> />
				<label for="published_0">未启用</label>
			</div>
			<span class="Validform_checktip">设置是否启用</span>
		</dd>
	</dl>
	<dl>
		<dt>分类描述：</dt>
		<dd>
			<textarea class="input" name='descript' id="descript" datatype="*0-20" nullmsg="最大长度为20个字符" sucmsg=" " /><?php echo set_value('descript'); ?></textarea>
            <?php if(form_error('descript')): ?><?php echo form_error('descript', '<span class="Validform_checktip">', '</span>'); ?><?php endif; ?>
        </dd>
	</dl>
</div>
<!--内容-->
<!--工具栏-->
<div class="page-footer">
	<div class="btn-list">
		<input class="btn" type="button" name="submit" value="提交" onclick="do_edit();" />
		<input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back(-1);" />
	</div>
	<div class="clear"></div>
</div>
<!--/工具栏-->
<script type="text/javascript" charset="utf-8">
    function do_edit() {
        var cat_key = $("[name='cat_key']").val();
        var name = $("[name='name']").val();
        var published = $("[name='published']").val();
        var descript = $("[name='descript']").val();

        var datas = {
            'cat_key': cat_key,
            'name': name,
            'published': published,
            'descript': descript,
            'do': 'add'
        };

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('navigation_cat/ajax') ?>",
            data: datas,
            async: false,
            dataType: "JSON",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                header_message("<?php echo lang('error'); ?>", "<?php echo lang('err_unknown_error'); ?>");
            },
            success: function (data) {
                if (data['msg']) {
                    header_message("", data['msg'], data['status'], 3);
                }
                if (data['jmp_url']) {
                    ajax_load_page(data['jmp_url']);
                }
            }
        });
    }
</script>