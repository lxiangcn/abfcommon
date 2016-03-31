var huiber = huiber || {};
huiber.dialog = huiber.dialog||{};

////////////////////////////////////////////////////////////////////////////////
huiber.dialog.__index = 10000;
huiber.dialog.__index_alert = 9998;
huiber.dialog.__index_confirm = 9997;
huiber.dialog.__index_load = 10000;
//0.mask...(hide win with mask)
huiber.dialog.showMask = function(ix){
	if(ix==0){ ix = huiber.dialog.__index; }
	$('body').append('<div class="divMask" style="z-index:'+ix+';"></div>');
	var wHeight = $(window).height(); //浏览器时下窗口可视区域高度
	var dHeight = $(document).height(); //浏览器时下窗口文档的高度
	var curHt = Math.max(wHeight,dHeight);
	$('.divMask').height(curHt);
	//如果为IE浏览器则生成frame用来防止object对象显示在div上方
	if($.browser&&$.browser.msie){
		$('.divMask').html(
			'<iframe style="height:100%;width:100%;"></iframe>'
		);
	}
};
huiber.dialog.hideMask = function(){
	var sum = $('div.divMask').length;
	if(sum==0) return;
	$('div.divMask').eq(sum-1).remove();
	$('.loading').hide();
};
huiber.dialog.hideWin = function(win){
	if(win){ win.hide();   huiber.dialog.hideMask(); }
}

////////////////////////////////////////////////////////////////////////////////
//1.show at center left right...  ( defined ix to show mask...)
huiber.dialog.showAt = function(win,top,left,ix, mask){
	if(ix == 0){ ix=huiber.dialog.__index; }
	if(mask){ huiber.dialog.showMask(ix); }
	win.css({ top: top, left:left, 'z-index':ix+1 }).show();
}
huiber.dialog.getBasePosition = function(win){
	var xx = win.parents('div');
	var baseTop = 0,   baseLeft = 0;
	for(var i=0; i<xx.length;i++){
		var el = xx.eq(i);
		if(el.css('position')=='relative'){
			baseTop = -el.offset().top;   baseLeft = -el.offset().left;   break;
		}
	}
	return [baseTop,baseLeft];
}
huiber.dialog.showCenter = function(win,ix,mask){
	var pos = huiber.dialog.getBasePosition(win);
	var baseTop = pos[0],   baseLeft = pos[1];
	var wTop = baseTop + ($(window).height() - win.outerHeight())/2;
	var wLeft = baseLeft + ($(window).width() - win.outerWidth())/2;
	wTop = wTop + $(window).scrollTop(); //在当前窗口居中
  if(wTop < 0){
    wTop = -20;
  }
	huiber.dialog.showAt(win,wTop,wLeft,ix,mask);
}
huiber.dialog.showLeft = function(win,obj,ix,mask){
	var pos = huiber.dialog.getBasePosition(win);
	var baseTop = pos[0],   baseLeft = pos[1];
	var target = $(obj);
	var wTop = baseTop + target.offset().top + target.outerHeight();
	var wLeft = baseLeft + target.offset().left - win.outerWidth() + target.outerWidth();
	huiber.dialog.showAt(win,wTop,wLeft,ix,mask);
}
huiber.dialog.showRight = function(win,obj,ix,mask){
	var pos = huiber.dialog.getBasePosition(win);
	var baseTop = pos[0],   baseLeft = pos[1];
	var target = $(obj);
	var wTop = baseTop + target.offset().top + target.outerHeight();
	var wLeft = baseLeft + target.offset().left;
	wTop = wTop + $(window).scrollTop(); //在当前组件右侧
	huiber.dialog.showAt(win,wTop,wLeft,ix,mask);
}
huiber.dialog.showRBottom = function(win,ix,mask){
	var pos = huiber.dialog.getBasePosition(win);
	var baseTop = pos[0],   baseLeft = pos[1];
	var wTop = baseTop + $(window).height() - win.outerHeight()-5;
	var wLeft = baseLeft + $(window).width() - win.outerWidth()-5;
	wTop = wTop + $(window).scrollTop(); //在当前窗口居中
	huiber.dialog.showAt(win,wTop,wLeft,ix,mask);
}
////////////////////////////////////////////////////////////////////////////////
//2.show alert confirm load...
huiber.dialog.showAlert = function(msg,className){
	var win = $('#defAlert');
	if(win.length==0){
		$('body').append(
		'<div class="divAlert '+ className +'" id="defAlert">'+
			'<div class="winTitle">'+
				'<span class="title">提示信息</span>'+
				'<span class="close">关闭</span>'+
			'</div>'+
			'<div class="winContent"><p class="msg"></p></div>'+
			'<div class="winButtons"><a class="btn_yes">确定</a></div>'+
		'</div>'
		);
		win = $('#defAlert'); //这里需要重新获取生成的元素
		win.find('.winButtons a.btn_yes, .winTitle span.close').bind('click',function(){
			huiber.dialog.hideWin(win)
		});
	}else{
		$('#defAlert').removeClass().addClass('divAlert').addClass(className);
		if(!win.is(":hidden")){
			huiber.dialog.hideMask();  //如果当前窗口正在显示,则需要先移除之前的遮罩层
		}
	}

	win.find('.winContent p.msg').html(msg); //更新弹出框的提示信息
	huiber.dialog.showCenter(win,huiber.dialog.__index_alert,true);
};
huiber.dialog.showAlertOut = function(msg,time){
	var win = $('#defAlertOut');
	if(win.length==0){
		$('body').append(
		'<div class="divAlert" id="defAlertOut">'+
			'<div class="winTitle">'+
				'<span class="title">提示信息</span>'+
			'</div>'+
			'<div class="winContent"><p class="msg"></p></div>'+
		'</div>'
		);
		win = $('#defAlertOut'); //这里需要重新获取生成的元素
	}else if(!win.is(":hidden")){
		huiber.dialog.hideMask(); //如果当前窗口正在显示,则需要先替换文本前的遮罩层
	}
	win.find('.winContent p.msg').html(msg);
	huiber.dialog.showCenter(win,huiber.dialog.__index_alert);
	//创建time秒后自动关闭的窗口
	var call = function(){
		if(time==0){ huiber.dialog.hideWin(win); return;  }
		win.find('.winContent p.msg').html(
			msg+'(窗口将在<b class="m_red">'+time+'</b>秒后自动关闭)'
		);
		time--;
		setTimeout(call,1000);
	}
	call();
}
//弹出信息,确认后执行回调方法
huiber.dialog.showAlertBack = function(msg,callback){
	var win = $('#defAlertBack');
	if(win.length==0){
		$('body').append(
		'<div class="divAlert" id="defAlertBack">'+
			'<div class="winTitle">'+
				'<span class="title">提示信息</span>'+
			'</div>'+
			'<div class="winContent"><p class="msg"></p></div>'+
			'<div class="winButtons"><a class="btn_yes">确定</a></div>'+
		'</div>'
		);
		win = $('#defAlertBack'); //这里需要重新获取生成的元素
	}
	//这里只需要更新弹出框的提示信息,并重新绑定事情
	win.find('.winContent p.msg').html(msg);
	win.find('.winButtons a.btn_yes').unbind('click').bind('click',function(){
		huiber.dialog.hideWin(win);
		callback();
	});
	huiber.dialog.showCenter(win,huiber.dialog.__index_alert,true);
};
//右下角弹出信息窗口,指定时间后消失
huiber.dialog.__timer_bubble = null;
huiber.dialog.showAlertBubble = function(msg,time){
	var win = $('#defAlertBubble');
	if(win.length==0){
		$('body').append(
		'<div class="divAlert" id="defAlertBubble">'+
			'<div class="winContent" style="background:#FFFFCC;"><p class="msg"></p></div>'+
		'</div>'
		);
		win = $('#defAlertBubble'); //这里需要重新获取生成的元素
	}
	//这里只需要更新弹出框的提示信息
	win.find('.winContent p.msg').html(msg);
	huiber.dialog.showRBottom(win,0,false);
	clearTimeout(huiber.imgview.__timer_bubble);
	//在单击事件中添加一个setTimeout()函数，设置单击事件触发的时间间隔
	huiber.dialog.__timer_bubble = setTimeout(function(){
		win.hide();
	},time*1000);
}
huiber.dialog.showConfirm = function(msg,callback){
	var win = $('#defConfirm');
	//如果不存在alert窗口则重新生成,否则使用已有的
	if(win.length==0){
		$('body').append(
		'<div class="divAlert" id="defConfirm">'+
			'<div class="winTitle">'+
				'<span class="title">提示信息</span><span class="close">关闭</span>'+
			'</div>'+
			'<div class="winContent">'+
				'<p class="msg"></p>'+
			'</div>'+
			'<div class="winButtons">'+
				'<a class="btn_yes m_mr25">确定</a>'+
				'<a class="btn_no">取消</a></p>'+
			'</div>'+
		'</div>'
		);
		win = $('#defConfirm'); //这里需要重新获取生成的元素
		win.find('.winTitle span.close').bind('click',function(){
			huiber.dialog.hideWin(win);
		});
	}else if(!win.is(":hidden")){
		huiber.dialog.hideMask(); //如果当前窗口正在显示,则需要先替换文本前的遮罩层
	}
	//这里需要更新确认框的提示信息，以及按钮绑定的事件
	win.find('.winContent p.msg').html(msg);
	win.find('.winButtons a.btn_yes').unbind('click').bind('click',function(){
		huiber.dialog.hideWin(win);    if(callback){ callback(); }
	});
	win.find('.winButtons a.btn_no').unbind('click').bind('click',function(){
		huiber.dialog.hideWin(win);
	});
	huiber.dialog.showCenter(win, huiber.dialog.__index_confirm, true);
};
huiber.dialog.showLoad = function(msg,className){
	className = className?' '+className:'';
	var win = $('#defLoad');
	//如果不存在alert窗口则重新生成,否则使用已有的
	if(win.length==0){
		$('body').append(
		'<div class="divLoading'+ className +'" id="defLoad">'+
			'<div></div>'+
		'</div>'
		);
		win = $('#defLoad'); //这里需要重新获取生成的元素
	}else if(!win.is(":hidden")){
		huiber.dialog.hideMask(); //如果当前窗口正在显示,则需要先替换文本前的遮罩层
	}
	//这里只需要更新弹出框的提示信息
	win.find('div').html(msg);
	huiber.dialog.showCenter(win,huiber.dialog.__index_load,true);
};
/**3.3.b移除当前load加载层,当load层存在时移除*/
huiber.dialog.hideLoad = function(){
	huiber.dialog.hideWin( $('#defLoad') );
};

huiber.dialog.showPayMask = function(){
	huiber.dialog.showMask();
	$('.payMask').show();
	$('.payMask .close').unbind('click').bind('click',function(){
		huiber.dialog.hidePayMask();
	});
	$('.payMask .error').unbind('click').bind('click',function(){
		huiber.dialog.hidePayMask();
	});

}

huiber.dialog.hidePayMask = function(){
	huiber.dialog.hideMask();
	$('.payMask').hide();
}