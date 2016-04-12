<!--导航栏-->
<ol class="breadcrumb">
    <li>
        <a href="<?php echo site_url("dashboard/welcome/index"); ?>">管理首页</a>
    </li>
    <li>
        <a href="<?php echo site_url("auth/admin/index"); ?>">管理员管理</a>
    </li>
    <li class="active">审核</li>
</ol>
<!--/导航栏-->
<!--内容-->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            审核用户
            <span class="small pull-right">
                <a href="<?php echo site_url("auth/admin/index"); ?>">返回列表</a>
            </span>
        </h3>
    </div>
    <div class="panel-body">
        <form novalidate="novalidate" method="post" class="simple_form form-horizontal" action="<?php echo site_url('auth/admin/audit/' . $data->id . '/' . $page_no); ?>" accept-charset="UTF-8">
            <input type="hidden" name="<?php echo $csrf_name; ?>" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="role_id" value="<?php echo $gid; ?>">
            <div class="form-group">
                <label for="username" class="col-md-2 control-label">用户名</label>
                <div class="col-md-8">
                    <input type='text' class='form-control' name='username' id="username" value="<?php echo set_value('username') == "" ? $data->username : set_value('username'); ?>" readonly />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">昵称</label>
                <div class="col-md-8">
                    <input type='text' class='form-control' name='mobile' id="mobile" value="<?php echo set_value('nickname') == "" ? $data->nickname : set_value('nickname'); ?>" readonly />
                </div>
            </div>
            <div class="form-group">
                <label for="user_account_attributes_location" class="col-md-2 control-label">审核</label>
                <div class="col-md-8">
                    <div class="radio">
                        <label>
                            <input class="px" type="radio" name="active" id="active_1" value="1" <?php if ($data->active): ?> checked="checked" <?php endif;?> />
                            <span class="lbl">审核通过</span>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input class="px" type="radio" name="active" id="active_2" value="2" <?php if (!$data->active): ?> checked="checked" <?php endif;?> />
                            <span class="lbl">审核不通过</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-9">
                    <button class="btn btn-primary" name="commit" type="submit">审核</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--内容-->