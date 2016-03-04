/**
 *
 */
var huiber = huiber || {};
/**
 * 按照类型检查字符串
 * @param str
 * @param type
 */
huiber.checkVal = function(str, type) {
    var result = false;
    switch(type) {
    case "mobile":
        var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
        if (reg.test(str)) {
            result = true
        }
        break;
    case "html":
        var reg = /<(.*)>(.*)<\/(.*)>|<(.*)\/>/;
        if (reg.test(str)) {
            result = true
        }
        break;
    case "number":
        var reg = /^[0-9]*$/;
        if (reg.test(str)) {
            result = true
        }
        break;
    case "email":
        var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        if (reg.test(str)) {
            result = true
        }
        break;
    case "tel":
        var reg = /^(\(\d{3,4}-)|\d{3.4}-)?\d{7,8}$/;
        if (reg.test(str)) {
            result = true
        }
        break;
    case "idcard":
        var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        if (reg.test(str)) {
            result = true
        }
        break;
    default:
    }
    return result;
};

huiber.removenode = function(obj) {
    $(obj).parent().parent().remove();
    var count = $('.item').length;
    var i = 1, j = 1, a = 1, b = 1;
    while (i < count && a < count) {
        $('.item').each(function() {
            //$(this).attr('name', 'item' + i);
            $(this).attr('id', 'item' + i);
            i++;
        });
        $('.item-title').each(function() {
            $(this).html('投票选项' + j);
            j++;
        });
        $('.sort_order').each(function() {
            //$(this).attr('name', 'sort_order' + a);
            $(this).attr('id', 'sort_order' + a);
            a++;
        });
        $('.vcount').each(function() {
            //$(this).attr('name', 'vcount' + b);
            $(this).attr('id', 'vcount' + b);
            b++;
        });
    }
    $("#counts").html(i - 1);
    $("#counts").html(count);
}

$(document).ready(function() {
    $("#add").click(function() {
        $("#file_add").before('<div class="form-group"><label class="col-md-2 control-label item-title">投票选项</label><div class="col-md-4"><input class="form-control item" id="item" name="item[]" type="text" value="" /><small class="help-block">请填写选项标题</small></div><div class="col-md-1"><input class="form-control sort_order" id="sort_order" name="sort_order[]" type="text" value="0" /><small class="help-block">排序</small></div><div class="col-md-1"><input class="form-control vcount" id="vcount" name="vcount[]" type="text" value="0" /><small class="help-block">票数</small></div><div class="col-md-1"><a class="btn btn-danger" onclick="huiber.removenode(this)" href="javascript:;">删除</a></div></div>');
        var i = 1, j = 1, a = 1, b = 1;
        $('.item').each(function() {
            //$(this).attr('name', 'item' + i);
            $(this).attr('id', 'item' + i);
            i++;
        });
        $('.item-title').each(function() {
            $(this).html('投票选项' + j);
            j++;
        });
        $('.sort_order').each(function() {
            //$(this).attr('name', 'sort_order' + a);
            $(this).attr('id', 'sort_order' + a);
            a++;
        });
        $('.vcount').each(function() {
            //$(this).attr('name', 'vcount' + b);
            $(this).attr('id', 'vcount' + b);
            b++;
        });
        $("#counts").html(i - 1);
    });
});

huiber.checkDesc = function() {
    var t = true;
    $('.item').each(function() {
        var n = $(this).attr('name').substring(4);
        if ($(this).val() == '') {
            alert('投票选项' + n + "不允许为空");
            t = false;
            return;
        }
    });
    return t;
};
