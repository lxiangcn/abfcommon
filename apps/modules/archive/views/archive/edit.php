<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("dashboard/welcome/index");?>">
			<i class="dropdown-icon fa fa-home"></i>
			&nbsp;&nbsp;管理首页
		</a>
	</li>
	<li>
		<a href="<?php echo site_url("archive/archive/index/".$data->channel_id);?>">内容列表</a>
	</li>
	<li class="active">编辑信息</li>
</ol>
<!--/导航栏-->
<script type="text/javascript" src="<?php echo theme_url('assets/js/editor/kindeditor.js'); ?>" charset="utf-8"></script>
<!--内容-->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			编辑信息
			<span class="small pull-right">
				<a href="<?php echo site_url("archive/archive/index/".$data->channel_id);?>">返回列表</a>
			</span>
		</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open(site_url('archive/admin/edit/'.$id.'/'. $page_no), 'id="validation-form" class="form-horizontal"'); ?>
			<div class="form-group">
			<label for="name" class="col-md-2 control-label">标题</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='title' id="title" value="<?php echo set_value('title') == "" ? $data->title : set_value('title'); ?>" />
				<small class="help-block">标题</small>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">关键词</label>
			<div class="col-md-6">
				<input type='text' class='form-control' name='keywords' id="keywords" value="<?php echo set_value('keywords') == "" ? $data->keywords : set_value('keywords'); ?>" />
				<small class="help-block">SEO关键词</small>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">描述信息</label>
			<div class="col-md-6">
				<textarea class="form-control" name='description' id="description" /><?php echo set_value('description') == "" ? $data->description : set_value('description'); ?></textarea>
				<small class="help-block">描述信息</small>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label">所在分类</label>
			<div class="col-md-6">
					<?php echo form_dropdown('category_id', $parents, $parent_id, "class='form-control'"); ?>
					<small class="help-block">选择分类</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">是否显示</label>
			<div class="col-md-6">
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_1" value="1" <?php if($data->published): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">显示</span>
					</label>
				</div>
				<div class="radio">
					<label>
						<input class="px" type="radio" name="published" id="published_0" value="0" <?php if(!$data->published): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">隐藏</span>
					</label>
				</div>
				<small class="help-block">设置是否显示</small>
			</div>
		</div>
		<div class="form-group">
			<label for="user_account_attributes_location" class="col-md-2 control-label">推荐类型</label>
			<div class="col-md-6">
				<div class="checkbox">
					<label>
						<input class="px" type="checkbox" name="recent" id="recent" value="1" <?php if($data->recent): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">最新</span>
					</label>
				</div>
				<div class="radio">
					<label>
						<input class="px" type="checkbox" name="hot" id="hot" value="1" <?php if($data->hot): ?> checked="checked" <?php endif; ?> />
						<span class="lbl">热门</span>
					</label>
				</div>
				<small class="help-block">设置文章推荐类型</small>
			</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label">封面图</label>
			<div class="col-md-6">
				<div class="input-group">
					<input name="image" id="image" class="form-control" type="text" size="40" value="<?php echo set_value('image') == "" ? $data->image : set_value('image'); ?>" readonly="readonly" />
					<span class="input-group-btn">
						<button id="showpic" class="btn btn-default" type="button">上传图片</button>
					</span>
				</div>
					<?php echo load_imagebtn('image', 'showpic'); ?>
				</div>
		</div>
		<div class="form-group">
			<label for="url" class="col-md-2 control-label">内容</label>
			<div class="col-md-10">
					<?php echo load_editor($data->content); ?>
				</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-9">
				<button class="btn btn-primary" name="commit" type="submit">更新文章</button>
			</div>
		</div>
		</form>
	</div>
</div>
<!--内容-->
<script type="text/javascript">
$("#validation-form").validate({
	focusInvalid: false,
	rules: {
		'title': {
			required: true,
			minlength: 2,
			maxlength: 20
		},
		'keywords':{
			required: false,
			minlength: 0,
			maxlength: 20
		},
		'description':{
			required: false,
			minlength: 0,
			maxlength: 40
		},
		'category_id':{
			number: true
		}
	},
	messages: {
		'title': '不为空，长度为2-20个字符!',
		'keywords':'关键词长度最大允许20个字符',
		'description':'描述信息长度最大允许40个字符',
		'category_id':'请选择分类'
	}
});
</script>