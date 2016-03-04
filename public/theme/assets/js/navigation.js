$(document).ready(function() {
	//hideSiteUrl();
    addSiteUrl();
});

var nav = nav || 0;

function addSiteUrl() {
    $.get("/admin/navigation/ajax_mo?nav=" + nav, function(data) {
        $("#mo_select").html(data);
    });
    $("#selectSiteURL").show();
    $("#mo_div").show();
    $("#outurl").hide();
}

function hideSiteUrl() {
    $("#link").val("http://");
    $("#outurl").show();
    $("#selectSiteURL").hide();
}
/**
 * 显示模块中的分类
 */
function selectMo() {
    $("#cat_div").hide();
    $("#item_div").hide();
    //设置表单中的link输入框的值：
    var mokey = $("#mo_select").val();
    $("#link").val(Mopath[mokey]);

    //更新分类下拉框内容：
    $("#cat_div").hide();
    $.get("/admin/navigation/ajax_cat?mokey=" + mokey, function(data) {
        if (data != '') {
            $("#cat_div").html(data);
            $("#cat_div").show();
            $("#cat_select").change(selectCat);
        }
    });
}

function selectCat() {
    $("#link").val($("#cat_select").val());
}
