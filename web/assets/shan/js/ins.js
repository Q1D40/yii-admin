$(function() {
	var _csrf = $("#_csrf").val();

	var $modal = $("#my-popup-news");
	var $modalTags = $("#my-popup-tags");
	var $selectIds = [];
	var $selectTitles = [];

	// 新增
	var addfromOptions = {
		url : $CONFIG['home'] + $CONFIG['script'] + '/ins/add',
		dataType : 'json',
		success : function(data) {
			if (data.status == 100000) {
				setTimeout(function() {
					alert(data.info);
					window.location.href = data.data;
				}, 1000);
			} else {
				alert(data.info);
			}
		},
		beforeSubmit : function() {
		}
	};
	$('#js-add-form').ajaxForm(addfromOptions);

	// 新增批量
	var bataddfromOptions = {
		url : $CONFIG['home'] + $CONFIG['script'] + '/ins/batchadd',
		dataType : 'json',
		success : function(data) {
			if (data.status == 100000) {
				alert(data.info);
				setTimeout(function() {
					// window.location.href = data.data;
				}, 1000);
			} else {
				alert(data.info);
			}
		},
		beforeSubmit : function() {
		}
	};
	$('#js-addbatch-form').ajaxForm(bataddfromOptions);
	
	// 搜索明星
	$("#search-star").on("click",function(){
		var name = $("#search-name").val();
		var url = $(this).attr("data-url");
		url = url + "?key=" + name;
		//alert(url);
		/*if($CONFIG['cururl'].indexOf("?")){
			$CONFIG['cururl'] = $CONFIG['cururl'] + "?key=" + name;
		}else{
			$CONFIG['cururl'] = $CONFIG['cururl'] + "&key=" + name;
		}*/
		//alert($CONFIG['cururl']);
		window.location.href = url;
	});
	
	$("#js-select-order").on("change",function(){
		var order = $(this).val();
		window.location.href="/index.php/ins?order=" + order;
	});
	
	// 重抓明星
	$("a.js-fetch-again").on("click", function() {
		var uid = $(this).attr("data-user-id");
		if (confirm("确定重新抓取该明星吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/refetch',
				data : {
					"_csrf" : _csrf,
					"uid" : uid
				},
				success : function(data) {
					data = $.parseJSON(data);
					if (data.status == 100000) {
						alert(data.info);
						window.location.reload(true);
					} else {
						alert(data.info);
					}
				}
			});
		}
	});
	
	// 编辑
	var editfromOptions = {
		url : $CONFIG['home'] + $CONFIG['script'] + '/ins/edit',
		dataType : 'json',
		success : function(data) {
			if (data.status == 100000) {
				alert(data.info);
				setTimeout(function() {
					window.location.href = data.data;
				}, 1000);
			} else {
				alert(data.info);
			}
		},
		beforeSubmit : function() {
		}
	};
	$('#js-edit-form').ajaxForm(editfromOptions);

	// 新增标签
	var addtagfromOptions = {
		url : $CONFIG['home'] + $CONFIG['script'] + '/ins/addtag',
		dataType : 'json',
		success : function(data) {
			if (data.status == 100000) {
				alert(data.info);
				setTimeout(function() {
					window.location.href = data.data;
				}, 1000);
			} else {
				alert(data.info);
			}
		}
	};
	$('#js-addtag-form').ajaxForm(addtagfromOptions);

	// 编辑标签
	var edittagfromOptions = {
		url : $CONFIG['home'] + $CONFIG['script'] + '/ins/edittag',
		dataType : 'json',
		success : function(data) {
			if (data.status == 100000) {
				alert(data.info);
				setTimeout(function() {
					window.location.href = data.data;
				}, 1000);
			} else {
				alert(data.info);
			}
		}
	};
	$('#js-edittag-form').ajaxForm(edittagfromOptions);

	// 删除标签
	$("a.js-deltag-item").on("click", function() {
		var mid = $(this).attr("data-id");
		if (confirm("确定删除吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/removetag',
				data : {
					"_csrf" : _csrf,
					"mid" : mid
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

	// 删除
	$("a.js-del-item").on("click", function() {
		var mid = $(this).attr("data-id");
		var uid = $(this).attr("data-user-id");
		if (confirm("确定删除吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/remove',
				data : {
					"_csrf" : _csrf,
					"uid" : uid,
					"mid" : mid
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

	// 暂停抓取
	$("a.js-fetch-pause").on("click", function() {
		var mid = $(this).attr("data-id");
		var uid = $(this).attr("data-user-id");
		if (confirm("确定暂停吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/pause',
				data : {
					"_csrf" : _csrf,
					"uid" : uid,
					"mid" : mid
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

	// 继续抓取
	$("a.js-fetch-goon").on("click", function() {
		var mid = $(this).attr("data-id");
		var uid = $(this).attr("data-user-id");
		if (confirm("确定继续吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/goon',
				data : {
					"_csrf" : _csrf,
					"uid" : uid,
					"mid" : mid
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

	
	// 上线弹出选择分组弹层
	$("a.js-fetch-on").on("click", function() {
		var mid = $(this).attr("data-id");
		var uid = $(this).attr("data-user-id");
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/getuser',
			data : {
				"_csrf" : _csrf,
				"uid" : uid
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					$("#js-tags-list").attr("data-id",mid);
					$("#js-tags-list").attr("data-user-id",uid);
					$("#js-tags-list tbody :checkbox").each(function(index,domEle){
						if($.inArray($(domEle).attr("data-field-name"),data.data)!=-1){
							//$(domEle).prop("checked", true);
							$(domEle).trigger("click");
						}
					});
					$modalTags.modal();
				} else {
					alert(data.info);
				}
			}
		});
	});
	
	var $selectTags = [];
	// 上线选择标签checkbox选中与取消选中事件
	$("#js-tags-list tbody :checkbox").on("click", function() {
		var tag = $(this).attr("data-field-name");
		if ($(this).prop('checked')) {
			$selectTags.push(tag);
			//$selectTitles.push(title);
		} else {
			var index = $selectTags.indexOf(tag);
			if (index > -1) {
				$selectTags.splice(index, 1);
				//$selectTitles.splice(index, 1);
			}
		}
		//alert($selectTags.length);
	});
	
	// 上线
	$("#js-add-tag").on("click", function() {
		var mid = $("#js-tags-list").attr("data-id");
		var uid = $("#js-tags-list").attr("data-user-id");
		if (confirm("确定上线吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/on',
				data : {
					"_csrf" : _csrf,
					"uid" : uid,
					"mid" : mid,
					"tags" : $selectTags
				},
				success : function(data) {
					data = $.parseJSON(data);
					if (data.status == 100000) {
						$modalTags.modal('close');
						window.location.reload(true);
					} else {
						alert(data.info);
					}
				}
			});
		}
	});
	// 下线
	$("a.js-fetch-off").on("click", function() {
		var mid = $(this).attr("data-id");
		var uid = $(this).attr("data-user-id");
		if (confirm("确定下线吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/off',
				data : {
					"_csrf" : _csrf,
					"uid" : uid,
					"mid" : mid
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

	// suggest
	$("#addstar").on("keyup", function() {
		var name = $(this).val();
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/suggest',
			data : {
				"_csrf" : _csrf,
				"name" : name
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {

				} else {
					alert(data.info);
				}
			}
		});
	});

	// 将某人加到某标签
	$(".js-addstar").on("click", function() {
		var uid = $("#addstar").val();
		var tagid = $("#mid").val();
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/taguser',
			data : {
				"_csrf" : _csrf,
				"tagid" : tagid,
				"uid" : uid
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

	$(".js-taguser-remove").on(
			"click",
			function() {
				if (confirm("确定删除吗")) {
					var uid = $(this).attr("data-user-id");
					var tagid = $("#mid").val();
					$.ajax({
						async : false,
						type : "POST",
						url : $CONFIG['home'] + $CONFIG['script']
								+ '/ins/taguserremove',
						data : {
							"_csrf" : _csrf,
							"tagid" : tagid,
							"uid" : uid
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

	// 标签上线
	$("a.js-tag-on").on("click", function() {
		var mid = $(this).attr("data-id");
		if (confirm("确定上线吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/tagon',
				data : {
					"_csrf" : _csrf,
					"mid" : mid
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

	// 标签下线
	$("a.js-tag-off").on("click", function() {
		var mid = $(this).attr("data-id");
		if (confirm("确定下线吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/tagoff',
				data : {
					"_csrf" : _csrf,
					"mid" : mid
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

	/*$(".js-chg-order").on("click", function() {
		var orderSpan = $(this).siblings(".js-order-span");
		orderSpan.removeClass("am-hide");
		var orderInput = orderSpan.find(".js-order-input");
	});

	$(".js-order-input").on("blur", function() {
		var mid = $(this).attr("data-id");
		var v = $(this).val();
		var that = $(this);
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/tagorder',
			data : {
				"_csrf" : _csrf,
				"mid" : mid,
				"order" : v
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					that.parent().addClass("am-hide");
					that.parent().siblings(".js-chg-order").text(v);
					// window.location.reload(true);
				} else {
					alert(data.info);
				}
			}
		});
	});*/

	// 选择新闻弹层
	$("#js-select-stars").on("click", function() {
		var _csrf = $("#_csrf").val();
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/loadstars',
			data : {
				"_csrf" : _csrf
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					$("#js-news-list").html(data.data);
					// 检查当前页选中的项
					$("tbody :checkbox").each(function(index, domEle) {
						var thisid = $(domEle).attr('data-user-id');
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

	// 翻页
	$("#my-popup-news").on("click", ".js-chg-page", function() {
		var _csrf = $("#_csrf").val();
		var page = $(this).attr("data-p");
		$.ajax({
			async : false,
			type : "GET",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/loadstars',
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
						var thisid = $(domEle).attr('data-user-id');
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
	
	// 搜索明星
	$("#my-popup-news").on("click", "#aj-search-star", function(){
		var _csrf = $("#_csrf").val();
		var name = $("#aj-search-name").val();
		$.ajax({
			async : false,
			type : "GET",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/loadstars',
			data : {
				"_csrf" : _csrf,
				'key' : name
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					$("#js-news-list").html(data.data);
					// 检查当前页选中的项
					$("tbody :checkbox").each(function(index, domEle) {
						var thisid = $(domEle).attr('data-user-id');
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

	// 确定按钮
	$("#js-add-sure").on("click", function() {
		var ids = "";
		var names = "";
		$.each($selectIds, function(i, n) {
			// alert("Name: " + i + ", Value: " + n);
			// alert("Title: " + $selectTitles[i]);
			ids += n + ",";
			names += $selectTitles[i] + ',';
		});
		// alert(ids);
		// alert(names);
		$("#js-selected-ids").val(ids);
		$("#js-selected-names").val(names);
		$modal.modal('close');
	});

	// checkbox选中与取消选中事件
	$("#my-popup-news").on("click", "tbody :checkbox", function() {
		var id = $(this).attr("data-user-id");
		var title = $(this).attr("data-cnname");
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

	// 提交按钮事件
	$("#submit-users").on("click", function() {
		var names = $("#js-selected-names").val();
		var tagid = $("#mid").val();
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/taguser',
			data : {
				"_csrf" : _csrf,
				"tagid" : tagid,
				"names" : names
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					alert(data.info);
					window.location.reload(true);
				} else {
					alert(data.info);
				}
			}
		});
	});
	
	// 选择全部
	$("#my-popup-news").on("click", "#select-all", function() {
		if ($(this).prop('checked')) {
			$("#my-popup-news tbody :checkbox").each(function(index, domEle) {
				$(domEle).prop("checked", true);
				var id = $(domEle).attr("data-user-id");
				var title = $(domEle).attr("data-cnname");
				$selectIds.push(id);
				$selectTitles.push(title);
			});
		} else {
			$("#my-popup-news tbody :checkbox").each(function(index, domEle) {
				$(domEle).removeAttr("checked");
				var id = $(domEle).attr("data-user-id");
				var index = $selectIds.indexOf(id);
				if (index > -1) {
					$selectIds.splice(index, 1);
					$selectTitles.splice(index, 1);
				}
			});
		}
	});
	
	//明星图片管理
	$("a.js-img-manage").on("click",function(){
		var opt = $(this).attr("data-opt");
		var mid = $(this).attr("data-id");
		if (confirm("确定操作吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/imgmanage',
				data : {
					"_csrf" : _csrf,
					"mid" : mid,
					"opt" : opt
				},
				success : function(data) {
					data = $.parseJSON(data);
					if (data.status == 100000) {
						//alert(data.info);
						window.location.reload(true);
					} else {
						alert(data.info);
					}
				}
			});
		}
	});
	
	// 标签批量保存
	$("#js-save-tags").on("click",function(){
		var submits = [];
		$("#js-tags-table tbody tr").each(function(index,domEle){
			var thisValues = {};
			var id = $(domEle).attr("data-id");
			var orderNum = $(domEle).find(".js-order").val();
			var size = $(domEle).find(".js-size").val();
			var level = $(domEle).find(".js-level").val();
			var status = $(domEle).find(".js-status").val();
			var content_level = $(domEle).find(".js-content-level").val();
			thisValues['id'] = id;
			thisValues['orderNum'] = orderNum;
			thisValues['size'] = size;
			thisValues['level'] = level;
			thisValues['status'] = status;
			thisValues['content_level'] = content_level;
			submits.push(thisValues);
			// console.log(id,orderNum,size);
		});
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/ins/edittags',
			data : {
				"_csrf" : _csrf,
				"submits" : submits
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					alert(data.info);
					window.location.reload(true);
				} else {
					alert(data.info);
				}
			}
		});
	});
	
	//活动贴纸管理
	$("a.js-tagimg-manage").on("click",function(){
		var opt = $(this).attr("data-opt");
		var mid = $(this).attr("data-id");
		if (confirm("确定操作吗")) {
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/ins/tagimgmanage',
				data : {
					"_csrf" : _csrf,
					"mid" : mid,
					"opt" : opt
				},
				success : function(data) {
					data = $.parseJSON(data);
					if (data.status == 100000) {
						//alert(data.info);
						window.location.reload(true);
					} else {
						alert(data.info);
					}
				}
			});
		}
	});
	
});