<!--导航栏-->
<ol class="breadcrumb breadcrumb-no-padding">
	<li>
		<a href="<?php echo site_url("welcome/index");?>">管理首页</a>
	</li>
	<li>
		<a href="<?php echo site_url("navigation/index");?>">导航管理</a>
	</li>
	<li class="active">导航树</li>
</ol>
<!--/导航栏-->
<!-- 内容 -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">导航管理</h3>
	</div>
	<div class="panel-body">
		<ul class="pagetitle">
			<div style="clear: both;" class="content" id="tree_box">
				<ol class="sortable ui-sortable">
				<?php echo $info_list;?>
				</ol>
			</div>
		</ul>
	</div>
</div>
<!-- /内容 -->
<script type="text/javascript">
    $(document).ready(function(){
        $('ol.sortable').nestedSortable({
            disableNesting: 'no-nest',
            forcePlaceholderSize: true,
            handle: 'div span.sortable_handle',
            helper: 'clone',
            items: 'li',
            maxLevels: 50,
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            update: function(event, ui) { 
                dataString = $('ol.sortable').nestedSortable('serialize');
                dataString += '&<?php echo $csrf_name; ?>=<?php echo $csrf_token; ?>';
                $.ajax({  
                    type: "POST",  
                    url: "<?php echo site_url("navigation_cat/save_items")?>",  
                    data: dataString,
                    dataType : 'json',
                    success: function(json) {  
                    	alert(json.msg);
                    },
                    error:function (xhr, ajaxOptions, thrownError){
                        alert('Status: ' + xhr.responseText);
                    }   
                });   
            }
        });

        // 删除 listner
        $('.delete').click(function() {
            return confirm('Are you sure you want to delete this item and any sub items it may have?');
        });
    });
</script>