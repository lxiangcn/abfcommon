var huiber = huiber || {};
huiber.ajax = huiber.ajax || {};
//depend  huiber-cookie...  huiber-dialog...  huiber-upload

////////////////////////////////////////////////////////////////////////////////
//1.发送请求到后台
huiber.ajax.send = function(url, params, callback, msg, hideMask, className) {
    params['protection_key'] = huiber.cookie.read('protection_key');
    if (!hideMask) {
        huiber.dialog.showLoad(msg, className);
    }
    $.ajax({
        async: true,
        type: "POST",
        url: url,
        data: params,
        success: function(data) {
            if (!hideMask) { huiber.dialog.hideLoad(); }
            callback(data);
        },
        error: function(msg) {
            if (!hideMask) { huiber.dialog.hideLoad(); }
            if (msg.readyState != 0) {
                huiber.dialog.showAlert("服务器繁忙，请稍刷新页面后重试。");
            }
        },
        dataType: "text"
    });
}

huiber.ajax.sendSync = function(url, params, callback, msg, hideMask, className) {
    params['protection_key'] = huiber.cookie.read('protection_key');
    if (!hideMask) {
        huiber.dialog.showLoad(msg, className);
    }
    $.ajax({
        async: false,
        type: "POST",
        url: url,
        data: params,
        success: function(data) {
            if (!hideMask) { huiber.dialog.hideLoad(); }
            callback(data);
        },
        error: function(msg) {
            if (!hideMask) { huiber.dialog.hideLoad(); }
            if (msg.readyState != 0) {
                huiber.dialog.showAlert("服务器繁忙，请稍刷新页面后重试。");
            }
        },
        dataType: "text"
    });
}

huiber.ajax.sendCustom = function(url, params, callback, msg, extra, className) {
        params['protection_key'] = huiber.cookie.read('protection_key');
        huiber.dialog.showLoad(msg, className);
        $.ajax({
            async: extra && extra['REQ_ASYNC'] ? extra['REQ_ASYNC'] : true,
            type: extra && extra['REQ_TYPE'] ? extra['REQ_TYPE'] : 'POST',
            url: url,
            data: params,
            success: function(data) {
                huiber.dialog.hideLoad();
                callback(data);
            },
            error: function(msg) {
                huiber.dialog.hideLoad();
                if (msg.readyState != 0) {
                    huiber.dialog.showAlert("服务器繁忙，请稍刷新页面后重试。");
                }
            },
            dataType: "text"
        });
    }
    //2.上传文件到后台
huiber.ajax.upload = function(url, fileid, params, callback, msg, className) {
    params['protection_key'] = huiber.cookie.read('protection_key');
    huiber.dialog.showLoad(msg, className);
    $.ajaxFileUpload({
        type: 'POST',
        url: url, //用于文件上传的服务器端请求地址
        secureuri: false, //一般设置为false
        fileElementId: fileid, //文件上传组件的id属性(name属性用于php后台$_FILES的属性)
        dataType: 'string', //
        data: params, //params传递的参数可以直接在后台用POST获取
        success: function(data) {
            huiber.dialog.hideLoad();
            callback(data);
        },
        error: function(data, status, e) {
            huiber.dialog.hideLoad();
            alert(e);
        }
    });
}
