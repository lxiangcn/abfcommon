//获取参数
function getParam(paramName, url) {
	var url = url || (self.location + '');
	var urlParams = url.substring(url.indexOf("?") + 1).split("&");
	for (var i = 0; i < urlParams.length; i++) {
		var NameValueCollection = urlParams[i].split("=");
		if (NameValueCollection[0] == paramName) {
			return NameValueCollection[1];
			break;
		}
	}
	return '';
}

/**
 * 删除条目,
 * 
 */
function del_one(id, url, csrf_token, message) {
	message = message || "删除后将不可恢复，您确定要删除吗?";
	if (!confirm(message)) {
		return;
	}
	$.ajax({
		type : "POST",
		cache : false,
		url : "/" + url + "/del/" + id,
		data : CSRF_TOKEN + "=" + csrf_token,
		dataType : "json",
		success : function(msg) {
			if (msg.result == 1) {
				self.location.reload();
			} else {
				alert("操作失败，" + msg.msg);
			}
		}
	});
};
/**
 * 删除条目
 * 
 */
function del_cat(id, url, message) {
	message = message || "删除后将不可恢复，您确定要删除此分类以及分类下的所有资料吗？";
	if (!confirm(message)) {
		return;
	}
	$.ajax({
		type : "POST",
		cache : false,
		url : "/" + url + "/del/" + id,
		dataType : "json",
		success : function(msg) {
			if (msg.result == 1) {
				self.location.reload();
			} else {
				alert("操作失败，" + msg.msg);
			}
		}
	});
};
/**
 * 批量操作
 */
function batch_option(option, url) {
	var data = $(".ids").serialize();
	if (data) {
		$.ajax({
			type : "POST",
			cache : false,
			url : "/" + url + "/batch_" + option,
			data : data,
			dataType : "json",
			success : function(msg) {
				if (msg.result == 1) {
					self.location.reload();
				} else {
					alert("操作失败，" + msg.msg);
				}
			}
		});
	} else {
		alert("您没有选定任何批量操作的对象！");
		return;
	}
}
/**
 * 单个转换状态
 */
function quick_switch(option, id, status, url) {
	$.ajax({
		type : "POST",
		cache : false,
		url : "/" + url + "/set_" + option + "/" + id + "/" + status,
		dataType : "json",
		success : function(msg) {
			if (msg.result) {
				self.location.reload();
			} else {
				alert("操作失败，" + msg.msg);
			}
		}
	});
}
/**
 * 获取下拉框中显示的值
 * 
 * @return string
 */
function getSelectContent(id) {
	var val = $(id).val();
	var content = '';
	$(id).find("option").each(function() {
		if ($(this).val() == val) {
			content = $(this).text();
		}
	});
	return content;

}
// URL时间戳
function timestamp() {
	var timestamp = Date.parse(new Date());
	return "timestamp=" + timestamp;
}

$("#checkall").on('click', function() {
	$("input:checkbox.ids").prop("checked", $(this).prop("checked"));
});
