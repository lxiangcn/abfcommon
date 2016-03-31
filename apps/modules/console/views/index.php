<link rel="stylesheet" type="text/css" href="<?php echo site_url() . 'theme/assets/js/dialog/dialog.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url() . 'theme/assets/js/grid/grid.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url() . 'theme/assets/css/font-img/font-img.css'; ?>">
<script type="text/javascript" src="<?php echo site_url() . 'theme/assets/js/dialog/dialog.js' ?>"></script>
<script type="text/javascript" src="<?php echo site_url() . 'theme/assets/js/ajax/ajax.js' ?>"></script>
<script type="text/javascript" src="<?php echo site_url() . 'theme/assets/js/cookie/cookie.js' ?>"></script>
<script type="text/javascript" src="<?php echo site_url() . 'theme/assets/js/grid/grid.js' ?>"></script>
<script type="text/javascript" src="<?php echo site_url() . 'theme/assets/js/readlog.js' ?>"></script>

<input type="hidden" id="V_BASE_URL" name="V_BASE_URL" value="<?php echo site_url(); ?>" />

<div class="form-group col-xs-12 m_mt10 m_pd0">
    <div class="col-xs-2">
        <input type="text" class="form-control" id="f_date" value="2016-03-31">
    </div>
    <div class="col-xs-3" style="margin-left:-35px;">
        <a onclick="huiber.readlog.readlog();" class="btn btn-danger btn-xs">读取日志</a>
        <a onclick="huiber.readlog.downlog();" class="btn btn-success btn-xs m_ml5">下载日志</a>
        <a onclick="huiber.readlog.downerrorlog();" class="btn btn-success btn-xs m_ml5">下载错误日志</a>
    </div>
    <div class="col-xs-3">
        <input type="text" placeholder="请输入日志内容查询" class="form-control"
            style="height:28px;margin:-3px 0 0 -15px;" id="f_title">
    </div>
    <div class="col-xs-4" style="margin-left:-35px;">
        <a onclick="huiber.readlog.toFilter()" class="btn btn-success btn-xs">查找</a>
        <a onclick="huiber.readlog.toReset()" class="btn btn-primary btn-xs m_ml10">清空</a>
        <span class="m_ml40">共有记录<b id="log_sum_01">0</b>个</span>
        <a class="btn btn-danger btn-xs" style="position:absolute; right:-65px;"
            onclick="huiber.readlog.showLogMonth()">数据库日志</a>
    </div>
</div>

<table class="mytable" id="logTab" style="width:100%;">
    <tr><th style="width:45px;"></th>
        <th style="width:160px;">最新行号<i class="sorting"></i></th>
        <th style="width:160px;">出现次数<i class="sorting"></i></th>
        <th style="width:160px;">异常时间<i class="sorting"></i></th>
        <th>日志消息<i class="sorting"></i></th>
    </tr>
    <tbody id="logTbody_nd"></tbody>
    <tbody id="logTbody" style="display:none;">
    <tr>
        <td><span class="lineno"></span></td>
        <td>#at#
            <i class="icon-query" style="cursor:pointer; float:right;"
                onclick="huiber.readlog.toViewLog('#at#');"></i></td>
        <td>#nums#</td>
        <td>#time#</td>
        <td>#msg#</td>
    </tr>
    </tbody>
</table>
<div class="mypager" id="mylogPager">
    <span class="links"></span>
    <span class="info">共有记录<b id="log_sum_02">0</b>个</span>
</div>

<!-- u.x logs view win -->
<div style="width:750px; height:auto;" id="logViewWin" class="divAlert m_hide">
    <div class="winTitle">
        <span class="title">日志上下文详细信息</span>
        <span class="close" onclick="huiber.dialog.hideWin($('#logViewWin'));">关闭</span>
    </div>
    <div class="winContent" style="height:450px; overflow:auto;"></div>
    <div class="winButtons" style="margin-top:-15px;">
        <span id="ishow_btn"></span>
        <a onclick="huiber.dialog.hideWin($('#logViewWin'));" class="btn btn-primary btn-sm">确定</a>
    </div>
</div>

<!-- u.0 import log win (import) -->
<div style="width:750px; height:auto;" id="importWin" class="divAlert m_hide">
    <div class="winTitle">
        <span class="title">日志导入到数据库窗口</span>
        <span class="close" onclick="huiber.dialog.hideWin($('#importWin'));">关闭</span>
    </div>
    <div class="winContent" style="height:240px; overflow:auto;">
        <div id="imp_month_div">
            <input type="checkbox" id="chk_day_all" style="margin:0;">
            <span style="color:#333;">全选</span>
            <span style="font-weight:bold; margin-left:15px;">请选择导入月份:</span>
            <select id="imp_year_slt" class="form-control myslt" style="width:105px; display:inline;
                    height:27px; line-height:27px;padding-top:3px;">
                <option value="2016">2016</option>    <option value="2015">2015</option>
                <option value="2014">2014</option>
            </select>
            <span style="margin:0 5px; color:#333;">年</span>
            <select id="imp_month_slt" class="form-control myslt" style="width:75px; display:inline;
                    height:27px; line-height:27px;padding-top:3px;">
                <option value="1">1</option>    <option value="2">2</option>
                <option value="3">3</option>    <option value="4">4</option>
                <option value="5">5</option>    <option value="6">6</option>
                <option value="7">7</option>    <option value="8">8</option>
                <option value="9">9</option>    <option value="10">10</option>
                <option value="11">11</option>    <option value="12">12</option>
            </select>
            <span style="margin:0 5px; color:#333;">月</span>
        </div>
        <div id="imp_day_div" class="m_mt5">
            <table style="width:99%;" class="mytable" id="impDayTab"></table>
        </div>
    </div>
    <div class="winButtons" style="margin-top:-15px;">
        <a onclick="huiber.readlog.importDB();" class="btn btn-primary btn-sm">导入数据库</a>
        <a onclick="huiber.dialog.hideWin($('#importWin'));" class="btn btn-default btn-sm">取消</a>
    </div>
</div>

<!-- u.1 list log by month win (month) -->
<div style="width:750px; height:auto;" id="logMonthWin" class="divAlert m_hide">
    <div class="winTitle">
        <span class="title">导入日志到数据库</span>
        <span class="close" onclick="huiber.dialog.hideWin($('#logMonthWin'));">关闭</span>
    </div>
    <div class="winContent" style="height:420px; overflow:auto;">
        <div class="m_mb5">
            <a class="btn btn-primary btn-xs" onclick="huiber.readlog.showImport()">导入日志到数据库</a>
            <span class="m_ml15">记录数:<span id="logMonth_sum_01">0</span>个</span>
        </div>
        <table class="mytable" id="logMonthTab" style="width:100%;">
            <tr><th style="width:45px;"></th>
                <th>日志月份<i class="sorting"></i></th>
                <th>日志天数<i class="sorting"></i></th>
                <th>总记录数<i class="sorting"></i></th>
            </tr>
            <tbody id="logMonthTbody_nd"></tbody>
            <tbody id="logMonthTbody" style="display:none;">
            <tr>
                <td><span class="lineno"></span></td>
                <td>#month#
                    <i class="icon-query" style="cursor:pointer; float:right;"
                        onclick="huiber.readlog.toShowLogDay('#table#');"></i></td>
                <td>#days#</td>
                <td>#logs#</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="winButtons" style="margin-top:-15px;">
        <a onclick="huiber.dialog.hideWin($('#logMonthWin'));" class="btn btn-primary btn-sm">确定</a>
    </div>
</div>

<!-- u.2 list log by day win (day) -->
<div style="width:750px; height:auto;" id="logDayWin" class="divAlert m_hide">
    <div class="winTitle">
        <span class="title"><b id="top_log_month" class="m_red"></b>数据库日志信息</span>
        <span class="close" onclick="huiber.dialog.hideWin($('#logDayWin'));">关闭</span>
        <input type="hidden" id="hid_view_logtable">
    </div>
    <div class="winContent" style="height:300px; overflow:auto;">
        <div class="m_mb5">
            <span>记录数:<span id="logDay_sum_01">0</span>个</span>
        </div>
        <table class="mytable" id="logDayTab" style="width:100%;">
            <tr><th style="width:45px;"></th>
                <th>日期<i class="sorting"></i></th>
                <th>errs<i class="sorting"></i></th>
                <th>debugs<i class="sorting"></i></th>
                <th>infos<i class="sorting"></i></th>
                <th>warns<i class="sorting"></i></th>
                <th>sqls<i class="sorting"></i></th>
            </tr>
            <tbody id="logDayTbody_nd"></tbody>
            <tbody id="logDayTbody" style="display:none;">
            <tr>
                <td><span class="lineno"></span></td>
                <td>#date_s#</td>    <td>#errs#</td>    <td>#debugs#</td>
                <td>#infos#</td>   <td>#warns#</td>   <td>#sqls#</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="winButtons" style="margin-top:-15px;">
        <a onclick="huiber.dialog.hideWin($('#logDayWin'));" class="btn btn-primary btn-sm">确定</a>
    </div>
</div>

