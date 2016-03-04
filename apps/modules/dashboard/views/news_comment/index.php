<!--导航栏-->
<div class="location">
    <a href="javascript:history.back(-1);" class="back"><i></i><span>返回上一页</span></a>
    <a href="<?php echo site_url("welcome/index"); ?>" class="home"><i></i><span>首页</span></a>
    <i class="arrow"></i>
    <a href="<?php echo site_url("news_comment/index"); ?>"><span>文章评论</span></a>
    <i class="arrow"></i>
    <span>评论列表</span>
</div>
<!--/导航栏-->
<!--工具栏-->
<div class="toolbar-wrap">
    <div id="floatHead" class="toolbar">
        <div class="l-list">
            <ul class="icon-list">
                <li><a class="all" href="javascript:;" onclick="checkAll(this);"><i></i><span>全选</span></a></li>
                <li><a href="<?php echo site_url("news_comment/config"); ?>">评论设置</a></li>
            </ul>
            <div class="menu-list">

            </div>
        </div>
        <div class="r-list">
            <form action="<?php echo site_url('news_comment/index/' . $page_no); ?>" method="get">
                <input type="text" class="keyword" id="keyword" name="keyword" value="<?php echo $keyword; ?>" />
                <input type="submit" class="btn-search" value="查询" />
            </form>
        </div>
    </div>
</div>
<!--/工具栏-->
<!--列表-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ltable">
    <thead>
        <tr>
            <th width="40">选择</th>
            <th align="left" width="320">被评论的文章</th>
            <th align="left">评论内容</th>
            <th align="left" width="120">发表时间</th>
            <th align="center" width="100">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($info_list) && !empty($info_list)): ?>
            <?php foreach ($info_list as $k => $v): ?>
                <tr class="data">
                    <td align="center"><input class="ids" type="checkbox" value="<?php echo $v['id']; ?>" name="ids[<?php echo $v['id']; ?>]" id="id<?php echo $v['id']; ?>" /></td>
                    <td><label for="id<?php echo $v['id']; ?>"><?php echo $v['news_id']; ?></label></td>
                    <td><?php echo $v['content']; ?></td>
                    <td><?php echo date('Y-m-d H:i', $v['created']); ?></td>
                    <td class="last handlelist">
                        <a href="javascript:quick_switch('publish',<?php echo $v['id']; ?>,<?php echo $v['published'] ? 0 : 1; ?>,'<?php echo setting("admin_folder"); ?>/news_comment');" title="点击更改发布状态"><?php if ($v['published'] == 0): ?>发布 <?php else : ?>取消发布 <?php endif; ?></a>
                        | <a href="javascript:del_one(<?php echo $v['id']; ?>,'<?php echo setting("admin_folder"); ?>/news_comment');">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr><td align="center" colspan="9">暂无记录</td></tr>
        <?php endif; ?>
</table>
<!--/列表-->
<!--内容底部-->
<div class="line20"></div>
<?php if (isset($pagestr) && !empty($pagestr)): ?>
    <div class="pagelist">
        <div class="l-btns">
            <?php echo $pagestr[1]; ?>
        </div>
        <div class="default"><?php echo $pagestr[0]; ?></div>
    </div>
<?php endif; ?>
<!--/内容底部-->