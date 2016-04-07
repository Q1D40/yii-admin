$(function() {
	var homeurl = $CONFIG['home'];
	var basescript = $CONFIG['script'];
	var tmot = $CONFIG['tmot'];
	var _csrf = $("#_csrf").val();

	var $modal = $("#my-popup-news");

	var $selectIds = [];
	var $selectTitles = [];

	// 选择新闻弹层
	$(".js-select-news").on("click", function() {
		var _csrf = $("#_csrf").val();
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/mbox/loadnews',
			data : {
				"_csrf" : _csrf
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					$("#js-news-list").html(data.data);
					// 检查当前页选中的项
					$("tbody :checkbox").each(function(index, domEle) {
						var thisid = $(domEle).attr('data-id');
						if ($selectIds.indexOf(thisid) > -1) {
							$(domEle).prop("checked", true);
						}
					});
					$modal.modal();
				} else {
					alert(data.info);
				}
			}
		});
	});

	// checkbox选中与取消选中事件
	$("#my-popup-news").on("click", ":checkbox", function() {
		var id = $(this).attr("data-id");
		var title = $(this).attr("data-title");
		if ($(this).prop('checked')) {
			$selectIds.push(id);
			$selectTitles.push(title);
		} else {
			var index = $selectIds.indexOf(id);
			if (index > -1) {
				$selectIds.splice(index, 1);
				$selectTitles.splice(index, 1);
			}
		}
		// alert($selectIds.length);
	});

	// 添加选中
	$("#my-popup-news").on("click", "#js-add-submit", function() {
		var typeid = $("li[class='am-active']").attr("data-typeid");
		var ids = $("#js-selected-news-ids-" + typeid).val();
		var list = $("#js-selected-news-" + typeid).html();
		$("#my-popup-news tbody input:checked").each(function(index, domEle) {
			ids += $(domEle).attr("data-id") + ",";
			list += '<p>' + $(domEle).attr("data-title") + '</p>';
		});
		$("#js-selected-news-" + typeid).html(list);
		$("#js-selected-news-ids-" + typeid).val(ids);
	});

	$("#js-add-sure").on("click", function() {
		var typeid = $("li[class='am-active']").attr("data-typeid");
		var ids = "";
		var list = "";
		$.each($selectIds, function(i, n) {
			// alert("Name: " + i + ", Value: " + n);
			// alert("Title: " + $selectTitles[i]);
			ids += n + ",";
			list += '<p>' + (i + 1) + "、" + $selectTitles[i] + '</p>';
		});
		// alert(ids);
		// alert(list);
		$("#js-selected-news-" + typeid).html(list);
		$("#js-selected-news-ids-" + typeid).val(ids);
		$modal.modal('close');
	});

	// 翻页
	$("#my-popup-news").on("click", ".js-chg-page", function() {
		var _csrf = $("#_csrf").val();
		var page = $(this).attr("data-p");
		$.ajax({
			async : false,
			type : "GET",
			url : $CONFIG['home'] + $CONFIG['script'] + '/mbox/loadnews',
			data : {
				"_csrf" : _csrf,
				'p' : page
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					$("#js-news-list").html(data.data);
					// 检查当前页选中的项
					$("tbody :checkbox").each(function(index, domEle) {
						var thisid = $(domEle).attr('data-id');
						if ($selectIds.indexOf(thisid) > -1) {
							$(domEle).prop("checked", true);
						}
					});
				} else {
					alert(data.info);
				}
			}
		});
	});

	// 保存
	$(".js-save-news").on("click", function() {
		var _csrf = $("#_csrf").val();
		var typeid = $(this).attr("data-typeid");
		var ids = $("#js-selected-news-ids-" + typeid).val();
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/mbox/save',
			data : {
				"_csrf" : _csrf,
				"typeid" : typeid,
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
	});
});