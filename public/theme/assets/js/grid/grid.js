var huiber = huiber || {};
huiber.grid = huiber.grid||{};

////////////////////////////////////////////////////////////////////////////////
/**1.a生成表格的序号,行号的span样式lineno*/
huiber.grid.makeLineNo = function(tbody){
	var elems = tbody.find('tr span.lineno');
	var digit = String(elems.length).length; //行号的位数
	elems.each(function(i,elem){
		var val = String(i+1);
		while(val.length<digit){ val='0'+val; }
		$(elem).text(val);
	});
};
/**1.b生成表格的样式,偶数行样式,以及鼠标移动到tr时的样式*/
huiber.grid.makeTBodyCss = function(tbody){
	tbody.find("tr:nth-child(even)").addClass("env");
	tbody.find("tr:nth-child(odd)").removeClass("env");  //odd奇数  even偶数
	//:nth-child(even) 从第1开始的偶数元素,:even的意思是指从第0开始的偶数元素
	tbody.find('tr').unbind('mouseover').bind('mouseover',function(){
		$(this).addClass('on');
	}).unbind('mouseout').bind('mouseout',function(){
		$(this).removeClass('on');
	});
};
/**1.c绑定表格数据到tbody,这里template为定义的tbody模板*/
huiber.grid.bindTData = function(tbody,template,data){
	tbody.html('');
	$(data).each(function(i,n){
		var html = template.html();
		for(k in n){
			html = html.replace(new RegExp("\#"+k+"\#","ig"),unescape(n[k]));
		}
		tbody.append(html);
	});
};
/**2.a绑定选择框的全选事件,topBox为需要绑定的全选框组件*/
huiber.grid.makeSelectAll = function(tbody,topBox,sltAllMet){
	topBox.unbind('click').bind('click',function(){
		var isCheck = topBox.is(':checked');
		tbody.find('tr :checkbox').each(function(i,elem){
			$(elem).attr('checked',isCheck);
		});
		if(sltAllMet){ sltAllMet(); }
	});
};
huiber.grid._shift_chk = '';
/**2.b绑定选择框事件(这里支持shift多选,行元素重新生成时需重新绑定)*/
huiber.grid.makeSelectOne = function(tbody, clickMet){
	tbody.find('tr :checkbox').each(function(i,elem){
		$(elem).click(function(event){
			var kid = $(elem).val();
			//0.1未按下shift键或者无参照值
			if(!event.shiftKey||huiber.grid._shift_chk==''||huiber.grid._shift_chk==kid){
				huiber.grid._shift_chk = kid;
				if(clickMet){ clickMet(elem); }  return;
			}
			//0.2如果shift键按下,且shift已有非当前元素的参照值
			var allBox = tbody.find('tr :checkbox');
			var place = -1;
			for(var a=0; a<allBox.length; a++){
				if(huiber.grid._shift_chk==allBox.eq(a).val()){ place=a; break; }
			}
			//0.2.1未找到shift元素的情况
			if(place==-1){
				if(clickMet){ clickMet(elem); }   return;
			}
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
};
/**2.c获取选中的checkbox项*/
huiber.grid.getCheckData = function(tbody){
	return tbody.find(':checkbox:checked')
}
/**u.通过window对象获取元素,这里name定义格式为xx.xx.xx*/
huiber.grid.loadWinDat = function(sign){
	var data = window;
	var names = sign.split('.');
	for(var i=0; i<names.length; i++){ data = data[ names[i] ]; }
	return data;
}
/**3.a生成分页的页码,超过5页时显示跳转项*/
huiber.grid.makePager = function(pageDiv,cur_page,sumPages,pageMetName){
	var linker = pageDiv.find('span.links').first();
	linker.text('');
	//1.如果只有1页则处理结束
	if(sumPages==1){ return; }

	//#u.1生成页码信息的方法,页码从0开始
	var getPageElem = function(pg,text){
		return '<a class="link" onclick="'+pageMetName+'('+pg+');return false;">'+text+'</a>';
	};
	//#u.2生成分页信息,从start页到end页,cur为当前页
	var fixPageElem = function(start,end,cur){
		for ( var pg = start; pg < end; pg++) {
			if(pg==cur){ linker.append('<span class="current">'+(pg+1)+'</span>'); }
			else{ linker.append(getPageElem(pg,pg+1)); }
		}
	};

	//2.固定显示分页信息,如果未定义则设置为5页
	var fixPages=5;
	//a.小于固定分页数时则直接全部显示
	if(sumPages<=fixPages){ //少于需要显示的页面则直接显示
		fixPageElem(0,sumPages,cur_page);   return;
	}
	//b.大于固定分页数时进行截取操作
	if(cur_page!=0){
		linker.append(getPageElem(0,'首页'));
	}
	if(cur_page!=0&&cur_page!=1){
		linker.append(getPageElem(cur_page-1,'上一页'));
	}
	var off = Math.floor(fixPages/2); //偏移量
	var start = (cur_page-off)<0?0:(cur_page-off); //如果偏移超出最小值则设置为0
	if(start+fixPages>sumPages){ start=sumPages-fixPages; } //如果偏移超出最大值则设置为max-fix
	fixPageElem(start,start+fixPages,cur_page);
	if(cur_page!=sumPages-1&&cur_page!=sumPages-2){
		linker.append(getPageElem(cur_page+1,'下一页'));
	}
	if(cur_page!=sumPages-1){
		linker.append(getPageElem(sumPages-1,'尾页'));
	}
	linker.append('<span class="info">共<b>'+sumPages+'</b>页</span>');

	//3.页数超出10页时,生成跳转按钮
	var pageMet = huiber.grid.loadWinDat( pageMetName );
	huiber.grid.makeRedirect(pageDiv,pageMet,sumPages);
};
/**3.b绑定分页时的跳转方法,可设置到分页的extraMet方法,页数大于5时显示*/
huiber.grid.makeRedirect = function(pageDiv,pageMet,sumPages){
	var linker = pageDiv.find('span.links').first();
	linker.append('<span class="info">跳至<input type="text" class="in_pager">页</span>');
	var redirect = linker.find('input.in_pager').first();
	var rMethod = function(){ //跳转事件
		var pg = redirect.val();
		if(isNaN(pg)){ return; }
		pg = Number(Number(pg).toFixed(0));
		if(1<=pg&&pg<=sumPages){ pageMet(pg-1); }
	};
	redirect.bind('blur',rMethod).bind('keydown',function(event){
		if (event.keyCode=="13"){ rMethod(); }
	});;
};
/**2.4.a排序时清除th项的排序样式*/
huiber.grid.clearOrderCss = function(ths){
	ths.find(".sorting_desc").each(function(i,elem){
		$(elem).removeClass('sorting_desc').addClass("sorting");
	});
	ths.find(".sorting_asc").each(function(i,elem){
		$(elem).removeClass('sorting_asc').addClass("sorting");
	});
}
/**2.4.b绑定表格的排序事件*/
huiber.grid.makeOrderBy = function(table,dataName,m_keys,m_types,pageMet){
	var ths = table.find('th');
	//注意：排序样式是i标签,但绑定事件的是th元素,绑定前先移除原有的方法
	huiber.grid.clearOrderCss(ths);
	ths.each(function(i,elem){ $(elem).unbind('click'); });

	//绑定排序,这里使用了闭包,将单元格列的序号column传递到排序元素的click方法
	ths.each(function(column,elem){
		var sort_el = $(elem).find('.sorting');
		if(sort_el.length==0){ return; }
		$(elem).click(function(){
			//方向：1=升序  -1=降序(点击后排序顺序:  默认->desc->asc->...)
			var direct = -1;
			var w_css = ['sorting_asc','sorting_desc'];
			if($(sort_el).hasClass("sorting_desc")){
				direct = 1;   w_css = ['sorting_desc','sorting_asc'];
			}
			huiber.grid.clearOrderCss(ths,elem);
			$(sort_el).removeClass('sorting '+w_css[0]).addClass(w_css[1]);

			//获取data,这里通过全局变量绑定,数据更新时不需要改变方法
			var data = huiber.grid.loadWinDat( dataName );
			//1.进行相关项的排序
			data.sort(function(a,b){	//注意,这里比较的不是对象,而是对象的元素
				var el_a = a[ m_keys[column] ]; 	var el_b = b[ m_keys[column]];
				if(m_types[column]=='str'){
					el_a=String(el_a); el_b=String(el_b);
					return el_a==el_b?0:( el_a>el_b? direct:-direct );
				}else if(m_types[column]=='num'){
					el_a=Number(el_a); el_b=Number(el_b);
					return el_a==el_b?0:( el_a>el_b? direct:-direct );
				}
			});

			//2.重新绑定页面数据
			pageMet(0);
		});
	});
};
