/**
 * abfcommon
 *
 * @package readlog
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-03-31 11:32:46
 * @author Alex Liu<lxiangcn@gmail.com>
 */

'use strict';

var huiber = huiber || {};
huiber.readlog = huiber.readlog || {};

//variable which request from php
huiber.readlog.__listDat = [];
huiber.readlog.__listDatBak = [];
huiber.readlog.__queryDate = '';
//record loadOps type-item, to avoid IE for in error-sequence
////////////////////////////////////////////////////////////////////////////////
huiber.readlog.init = function(){
    huiber.datefield.init($('#f_date'));
    $('#f_date').val(huiber.readlog.u_now_day());

    huiber.readlog.initHtml();
    huiber.readlog.toFilter();
}
huiber.readlog.u_now_day = function(){
    var now = new Date();
    var y = now.getFullYear(), m = now.getMonth()+1, d=now.getDate();
    return y+'-'+(m>9?m:'0'+m)+'-'+(d>9?d:'0'+d);
}
huiber.readlog.readlog = function(){
    var date = $.trim( $('#f_date').val() );
    if(date==''){
        huiber.dialog.showAlert('请先选择要读取日志的日期！');  return;
    }
    huiber.readlog.__queryDate = date;
    var params = { date:date };
    var url = $('#V_BASE_URL').val() + 'console/console/index/read';
    var callback = function(data){
        data = JSON.parse(data);
        if(!data['success']){
            huiber.dialog.showAlert(data['message']);  return;
        }
        data['errs'].sort(function(a,b){ return a['at']-b['at']<0; });
        huiber.readlog.__listDat = data['errs'];
        huiber.readlog.__listDatBak = data['errs'];
        huiber.readlog.toFilter();
    };
    var msg = '正在读取日志内容中..';
    huiber.ajax.send(url,params,callback,msg);
}

huiber.readlog.downlog = function(){
    var date = $.trim( $('#f_date').val() );
    if(date==''){
        huiber.dialog.showAlert('请先选择要下载日志的日期！');  return;
    }
    huiber.readlog.__queryDate = date;
    var params = { date:date };
    var url = $('#V_BASE_URL').val() + 'console/console/index/zip';
    var callback = function(data){
        data = JSON.parse(data);
        if(data['success']){
            var downUrl = $('#V_BASE_URL').val() + 'huiberconsole/downlog/'+date;
            //jquery 对象转换到 dom 对象，需要使用get(0)
            $(window).get(0).open(downUrl); return;
        }else{
            huiber.dialog.showAlert(data['message']);  return;
        }
    };
    var msg = '正在下载日志内容中..';
    huiber.ajax.send(url,params,callback,msg);
}

huiber.readlog.downerrorlog = function(){
    var date = $.trim( $('#f_date').val() );
    if(date==''){
        huiber.dialog.showAlert('请先选择要下载日志的日期！');  return;
    }
    huiber.readlog.__queryDate = date;
    var params = { date:date };
    var url = $('#V_BASE_URL').val() + 'console/console/readlog/ziperror';
    var callback = function(data){
        data = JSON.parse(data);
        if(data['success']){
            var downUrl = $('#V_BASE_URL').val() + 'huiberconsole/downerrorlog/'+date;
      console.log(downUrl);
            //jquery 对象转换到 dom 对象，需要使用get(0)
            $(window).get(0).open(downUrl); return;
        }else{
            huiber.dialog.showAlert(data['message']);  return;
        }
    };
    var msg = '正在下载日志内容中..';
    huiber.ajax.send(url,params,callback,msg);
}

huiber.readlog.initHtml = function(){
    //1.bind table order by
    huiber.grid.makeOrderBy(
        $('#logTab'),
        'huiber.readlog.__listDat',
        ['none', 'at', 'nums', 'time', 'msg'],   ['none','num','num','str','str'],
        huiber.readlog.toPager
    );
    huiber.grid.makeOrderBy(
        $('#logMonthTab'),
        'huiber.readlog.__logMonthList',
        ['none', 'month', 'days', 'logs'],   ['none','str','num','num'],
        huiber.readlog.toPagerLogMonth
    );
    huiber.grid.makeOrderBy(
        $('#logDayTab'),
        'huiber.readlog.__logDayList',
        ['none', 'date', 'errs', 'debugs', 'infos', 'warns', 'sqls'],
        ['none', 'str', 'num', 'num', 'num', 'num', 'num'],
        huiber.readlog.toPagerLogDay
    );

    //bind html elem event
    $('#f_title').bind('keydown', function(e){
        if (e.keyCode=="13"){  huiber.readlog.toFilter();  }
    });
    $('#imp_year_slt, #imp_month_slt').change(huiber.readlog.u_env_imp_chg);
    $('#chk_day_all').click(function(){
        var isCheck = $('#chk_day_all').is(':checked');
        $('#impDayTab').find('td :checkbox').each(function(i,elem){
            $(elem).attr('checked',isCheck);
        });
    });
}

////////////////////////////////////////////////////////////////////////////////
huiber.readlog._________________x = null;
huiber.readlog.toFilter = function(){
    var f_title = $.trim( $('#f_title').val() );
    if(f_title==''){
        huiber.readlog.__listDat = huiber.readlog.__listDatBak;
    }else{
        huiber.readlog.__listDat = [];
        for(var i = 0; i < huiber.readlog.__listDatBak.length;i++){
            var po = huiber.readlog.__listDatBak[i];
            if(String(po['msg']).indexOf(f_title)!=-1){
                huiber.readlog.__listDat.push(po);
            }
        }
    }
    huiber.readlog.toPager(0);
}
huiber.readlog.toReset = function(){
    $('#f_title').val('');
    huiber.readlog.toFilter();
}
huiber.readlog.toPager = function(cur_page){
    var tdat = huiber.readlog.__listDat;
    var tbody = $('#logTbody_nd');
    var template = $('#logTbody');
    var page_size = 20;
    //1.1生成并绑定分页数据
    var min = cur_page*page_size;
    var max = cur_page*page_size+page_size-1;
    var tmp = $.grep( tdat, function(n,i){
        if(i>=min&&i<=max) return true;
    });
    var u_rend_msg = function(msg,len){
        return msg.length<=len?msg:msg.substring(0,len)+'..';
    }
    huiber.grid.bindTData(tbody,template,tmp);
    huiber.grid.makeLineNo(tbody);
    huiber.grid.makeTBodyCss(tbody);
    //1.2绑定分页信息项
    var sum = tdat.length;
    var sumPages = Math.ceil(sum/page_size);
    huiber.grid.makePager($('#mylogPager'), cur_page, sumPages,'huiber.readlog.toPager');
    $('#log_sum_01').text(sum);
    $('#log_sum_02').text(sum);
}


////////////////////////////////////////////////////////////////////////////////
huiber.readlog._________________0 = null;
huiber.readlog.toViewLog = function(at){
    var url = $('#V_BASE_URL').val() + 'console/console/index/view';
    var params = {
        date: huiber.readlog.__queryDate,   at:at
    };
    var callback = function(data){
        data = JSON.parse(data);
        if(!data['success']){
            huiber.dialog.showAlert(data['message']); return;
        }
        var logs = data['logs'];  //{ lv: INFO, at:121, time:.., msg:.. }
        var html = '';
        for(var i = 0; i < logs.length; i++){
            if(logs[i].substring(0,5)=='ERROR'){
                html += '<p style="margin:0; color:red; font-size:14px;">';
            }else{
                html += '<p style="margin:0; font-size:13px; color:#333;">';
            }
            html += logs[i]+'</p>';
        }
        $('#logViewWin div.winContent').html(html);
        huiber.dialog.showCenter($('#logViewWin'),0,true);
    };
    var msg = '正在查询日志详细信息中...';
    huiber.ajax.send(url,params,callback,msg);
}


////////////////////////////////////////////////////////////////////////////////
huiber.readlog._________________1 = null;
huiber.readlog.__logMonthList = [];
huiber.readlog.showLogMonth = function(){
    huiber.dialog.showCenter($('#logMonthWin'),0,true);
    huiber.readlog.u_reload_logmonth();
}
huiber.readlog.u_reload_logmonth = function(){
    var url = $('#V_BASE_URL').val() + 'huiberconsole/readlog/listLogMonth';
    var params = {};
    var callback = function(data){
        data = JSON.parse(data);
        if(!data['success']){
            huiber.dialog.showAlert(data['message']);  return;
        }
        huiber.readlog.__logMonthList = data['logmonth'];
        huiber.readlog.toPagerLogMonth();
    };
    var msg = '正在查询数据库导入的日志详细信息中...';
    huiber.ajax.send(url,params,callback,msg);
}
huiber.readlog.toPagerLogMonth = function(){
    var tdat = huiber.readlog.__logMonthList;
    var tbody = $('#logMonthTbody_nd');
    var template = $('#logMonthTbody');
    huiber.grid.bindTData(tbody,template,tdat);
    huiber.grid.makeLineNo(tbody);
    huiber.grid.makeTBodyCss(tbody);
    $('#logMonth_sum_01').text(tdat.length);
}

////////////////////////////////////////////////////////////////////////////////
huiber.readlog._________________2 = null;
huiber.readlog.showImport = function(){
    var now = new Date();
    $('#imp_year_slt').val(now.getFullYear());
    $('#imp_month_slt').val(now.getMonth()+1);
    huiber.readlog.u_env_imp_chg();
    huiber.dialog.showCenter($('#importWin'),huiber.dialog.__index+2,true);
}
huiber.readlog.u_env_imp_chg = function(){
    var year = Number( $('#imp_year_slt').val() );
    var month = Number( $('#imp_month_slt').val() );
    var count = huiber.readlog.u_count_days(year,month);
    var trHtmls = '';
    for(var i = 1; i <= count; i++){
        if(i%7==1){ trHtmls += '<tr>'; }
        var date = (i>9?'':'0')+i;
        trHtmls += '<td><input type="checkbox" value="'+date+'">'+date+'日</td>';
        if(i==count && count%7!=0){
            for(var x = 0; x < 7-count%7; x++){ trHtmls += '<td></td>'; }
        }
        if(i%7==0){ trHtmls += '</tr>'; }
    }
    $('#impDayTab').html(trHtmls);
    //bind td select checkbox
    huiber.readlog._shift_chk = '';
    $('#impDayTab').find('td :checkbox').each(function(i,elem){
        $(elem).click(function(event){
            var kid = $(elem).val();
            //0.1未按下shift键或者无参照值
            if(!event.shiftKey||huiber.readlog._shift_chk==''||huiber.readlog._shift_chk==kid){
                huiber.readlog._shift_chk = kid;
            }
            //0.2如果shift键按下过,且shift已有非当前元素的参照值
            var allBox = $('#impDayTab').find('td :checkbox');
            var place = -1;
            for(var a=0; a<allBox.length; a++){
                if(huiber.readlog._shift_chk==allBox.eq(a).val()){ place=a; break; }
            }
            //0.2.1未找到shift元素的情况
            if(place==-1){ return; }

            //0.2.2已找到shift元素时,选中状态参考shift点击的选项
            var isCheck = $(elem).is(':checked');
            var shifts = [];  //记录shift操作的元素
            var min = Math.min(i,place), max = Math.max(i,place);
            for ( var b=min; b<=max; b++) {
                var el = allBox.eq(b);
                el.attr('checked',isCheck);
                shifts.push(el);
            }
        });
    });
}
huiber.readlog.u_count_days = function(year,month){
    switch(month){
        case 1: case 3: case 5: case 7: case 8: case 10: case 12:
            return 31;
        case 4: case 6: case 9: case 11:
            return 30;
        case 2:
            var isLeap = year%400==0?true:(year%100!=0&&year%4==0);
            return isLeap?29:28;
    }
}
huiber.readlog.importDB = function(){
    var year = $('#imp_year_slt').val();
    var month = $('#imp_month_slt').val();
    month = (Number(month)>9?'':'0')+month;
    var chks = $('#impDayTab').find('td :checkbox[checked]');
    var dateList = [];
    for(var i = 0; i < chks.length; i++){
        var date = year+'-'+month+'-'+chks.eq(i).val();
        dateList.push(date);
    }
    if(dateList.length==0){
        huiber.dialog.showAlert('请先指定需要导入的日期项!');  return;
    }
    //record logs to database
    var url = $('#V_BASE_URL').val() + 'huiberconsole/readlog/importLogDB';
    var params = { dates: dateList };
    var callback = function(data){
        data = JSON.parse(data);
        if(!data['success']){
            huiber.dialog.showAlert(data['message']);  return;
        }
        huiber.dialog.hideWin($('#importWin'));
        huiber.readlog.u_reload_logmonth();
    };
    var msg = '正在查询指定月份的日志详细信息中...';
    huiber.ajax.send(url,params,callback,msg);
}

////////////////////////////////////////////////////////////////////////////////
huiber.readlog._________________3 = null;
huiber.readlog.__logDayList = [];
huiber.readlog.toShowLogDay = function(table){
    $('#top_log_month').text(table.substring(12,16)+'年'+table.substring(16,18)+'月');
    huiber.dialog.showCenter($('#logDayWin'),huiber.dialog.__index+2,true);
    $('#hid_view_logtable').val(table);
    huiber.readlog.u_reload_logday();
}
huiber.readlog.u_reload_logday = function(table){
    var url = $('#V_BASE_URL').val() + 'huiberconsole/readlog/listLogDay';
    var params = { table: $('#hid_view_logtable').val() };
    var callback = function(data){
        data = JSON.parse(data);
        if(!data['success']){
            huiber.dialog.showAlert(data['message']);  return;
        }
        huiber.readlog.__logDayList = data['logday'];  //{ lv: INFO, at:121, time:.., msg:.. }
        huiber.readlog.toPagerLogDay();
    };
    var msg = '正在查询指定月份的日志详细信息中...';
    huiber.ajax.send(url,params,callback,msg);
}
huiber.readlog.toPagerLogDay = function(){
    var tdat = huiber.readlog.__logDayList;
    var tbody = $('#logDayTbody_nd');
    var template = $('#logDayTbody');
    for(var i = 0;i < tdat.length; i++){
        tdat[i]['date_s'] = huiber.readlog.u_fmt_date(tdat[i]['date']);
    }
    huiber.grid.bindTData(tbody,template,tdat);
    huiber.grid.makeLineNo(tbody);
    huiber.grid.makeTBodyCss(tbody);
    $('#logDay_sum_01').text(tdat.length);
}
huiber.readlog.u_fmt_date = function(date){
    return date.substring(0,4)+'年'+date.substring(4,6)+'月'+date.substring(6,8)+'日';
}

