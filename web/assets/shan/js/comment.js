$(function() {
	var homeurl = $CONFIG['home'];
	var basescript = $CONFIG['script'];
	var tmot = $CONFIG['tmot'];
	var _csrf = $("#_csrf").val();
	// 单个删除
	$("a.js-del-item").on("click", function() {
		var id = $(this).attr("data-id");
		if (confirm("确定删除吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/comment/remove',
				data : {
					"_csrf" : _csrf,
					"ids" : id + ','
				},
				success : function(data) {
					data = $.parseJSON(data);
					if (data.status == 100000) {
						window.location.reload(true);
					} else {
						alert(data.info);
					}
				}
			});
		}
	});
	// 选择全部
	$("#select-all").on("click", function() {
		if ($(this).prop('checked')) {
			$("tbody :checkbox").each(function(index, domEle) {
				$(domEle).prop("checked", true);
			});
		} else {
			$("tbody :checkbox").each(function(index, domEle) {
				$(domEle).removeAttr("checked");
			});
		}
	});
	// 批量删除
	$(".js-del-all").on("click", function() {
		var ids = "";
		$("tbody input:checked").each(function(index, domEle) {
			ids += $(domEle).attr("data-id") + ",";
		});
		if(!ids){
			alert("未选择要删除的项");
			return false;
		}
		if (confirm("确定删除吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/comment/remove',
				data : {
					"_csrf" : _csrf,
					"ids" : ids
				},
				success : function(data) {
					data = $.parseJSON(data);
					if (data.status == 100000) {
						window.location.reload(true);
					} else {
						alert(data.info);
					}
				}
			});
		}
	});
	
	// 搜索评论
	$("#search-comment").on("click",function(){
		var name = $("#search-name").val();
		var url = $(this).attr("data-url");
		url = url + "?key=" + name;
		window.location.href = url;
	});
	
	// 隐藏运营人员的评论
	$("#js-robot-comments").on("click", function() {
		var url = "";
		var cururl = window.location.href;
		var wenhao = cururl.indexOf("?");
		var flag = cururl.indexOf("flag");
		if ($(this).prop('checked')) {
			//window.location.href = $(this).attr("data-url") + "?flag=N";
			//return false;
			if(flag > 0){
				url = cururl.replace("flag=Y","flag=N");
			}else{
				if( wenhao > 0 ){
					url = cururl + "&flag=N";
				}else{
					url = cururl + "?flag=N";
				}
			}
		} else {
			//window.location.href = $(this).attr("data-url") + "?flag=Y";
			//return false;
			if(flag > 0){
				url = cururl.replace("flag=N","flag=Y");
			}else{
				if( wenhao > 0 ){
					url = cururl + "&flag=Y";
				}else{
					url = cururl + "?flag=Y";
				}
			}
		}
		window.location.href = url;
	});
});