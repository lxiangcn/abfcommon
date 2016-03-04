<!--导航栏-->
<div class="location">
    <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
    <a href="<?php echo site_url("welcome/index"); ?>" class="home"><i></i><span>首页</span></a>
    <i class="arrow"></i>
    <a href="<?php echo site_url("news_cat/index"); ?>"><span>文章评论</span></a>
    <i class="arrow"></i>
    <span>评论设置</span>
</div>
<!--/导航栏-->
<form action="<?php echo site_url('news_comment/config'); ?>"  method="post">
    <!--内容-->
    <div class="content-tab-wrap">
        <div id="floatHead" class="content-tab">
            <div class="content-tab-ul-wrap">
                <ul>
                    <li><a href="javascript:;" onclick="tabs(this);" class="selected">基本信息</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <dl>
            <dt>评论设置：</dt>
            <dd>
                <div class="rule-multi-radio">
                    <input type="radio" name="news_comment_status" id="news_comment_status_0" value="0" <?php if ($data->value == 0): ?> checked="checked" <?php endif; ?> />
                    <label for="news_comment_status_0">不开启评论</label>
                    <input type="radio" name="news_comment_status" id="news_comment_status_1" value="1" <?php if ($data->value == 1): ?> checked="checked" <?php endif; ?> />
                    <label for="news_comment_status_1">开启评论但需要审核</label>
                    <input type="radio" name="news_comment_status" id="news_comment_status_2" value="2" <?php if ($data->value == 2): ?> checked="checked" <?php endif; ?> />
                    <label for="news_comment_status_2">开启评论且不需要审核</label>
                </div>
                <span class="Validform_checktip">设置是否启用</span>
            </dd>
        </dl>
    </div>
    <!--内容-->
    <!--工具栏-->
    <div class="page-footer">
        <div class="btn-list">
            <input class="btn" type="submit" name="submit" value="提交" />
            <input name="btnReturn" type="button" value="返回上一页" class="btn yellow" onclick="javascript:history.back(-1);" />
        </div>
        <div class="clear">
        </div>
    </div>
    <!--/工具栏-->
</from>