var _csrf = $("#_csrf").val();

// 新增
//var addfromOptions = {
//	url : $CONFIG['home'] + $CONFIG['script'] + '/news/add',
//	dataType : 'json',
//	success : function(data) {
//		if (data.status == 100000) {
//			//alert(data.info);
//			setTimeout(function() {
//				window.location.href = data.data;
//			}, 1000);
//		} else {
//			alert(data.info);
//		}
//	}
//};
//$('#js-add-form').ajaxForm(addfromOptions);

$("#nextStep").on("click", function(){
    $.ajax({
        async : false,
        type : "POST",
        url : $CONFIG['home'] + $CONFIG['script'] + '/news/add',
        data : {
            "_csrf" : _csrf,
            "title" : $("#title").val(),
            "sourceName" : $("#sourceName").val(),
            "source" : $("#source").val(),
            "content_level" : $(".content_level:checked").val(),
            "cover_img" : $("#cover_img").val(),
            "x" : $("#x").val(),
            "y" : $("#y").val(),
            "w" : $("#w").val(),
            "h" : $("#h").val(),
            "images" : $("#images").val(),
            "publish-time-radio" : $(".publish-time-radio:checked").val(),
            "publish-time" : $("#publish-time").val()
        },
        success : function(data) {
            data = $.parseJSON(data);
            if (data.status == 100000) {
                //alert(data.info);
                setTimeout(function() {
                    window.location.href = data.data;
                }, 1000);
            } else {
                alert(data.info);
            }
        }
    });
});

$("#js-editimgs-submit").on("click",function(){
	var topicid = $("#topicid").val();
	var submits = [];
	$(".js-img-content").each(function(index,domEle){
		var thisValues = {};
		var img = $(domEle).attr("data-img");
		var content = $(domEle).val();
		thisValues['img'] = img;
		thisValues['content'] = content;
		submits.push(thisValues);
	});
	$.ajax({
		async : false,
		type : "POST",
		url : $CONFIG['home'] + $CONFIG['script'] + '/news/addimgs',
		data : {
			"_csrf" : _csrf,
			"topicid" : topicid,
			"submits" : submits
		},
		success : function(data) {
			data = $.parseJSON(data);
			if (data.status == 100000) {
				window.location.href = data.data;
			} else {
				alert(data.info);
			}
		}
	});
});

// 移除新闻图集单张图片
$(".js-imgs-remove").on("click",function(){
	if (confirm("确定删除吗")) {
		var id = $(this).attr("data-imgid");
		$("#"+id).remove();
	}
});

// 編輯基本信息
//var editfromOptions = {
//	url : $CONFIG['home'] + $CONFIG['script'] + '/news/edit',
//	dataType : 'json',
//	success : function(data) {
//		if (data.status == 100000) {
//			alert(data.info);
//			setTimeout(function() {
//				window.location.href = data.data;
//			}, 1000);
//		} else {
//			alert(data.info);
//		}
//	}
//};
//$('#js-editnews-form').ajaxForm(editfromOptions);

$("#save").on("click", function(){
    $.ajax({
        async : false,
        type : "POST",
        url : $CONFIG['home'] + $CONFIG['script'] + '/news/edit',
        data : {
            "_csrf" : _csrf,
            "title" : $("#title").val(),
            "sourceName" : $("#sourceName").val(),
            "source" : $("#source").val(),
            "content_level" : $(".content_level:checked").val(),
            "topicid" : $("#topicid").val(),
        },
        success : function(data) {
            data = $.parseJSON(data);
            if (data.status == 100000) {
                alert(data.info);
                setTimeout(function() {
                    window.location.href = data.data;
                }, 1000);
            } else {
                alert(data.info);
            }
        }
    });
});

// 上下线、删除等
$("a.js-news-manage").on("click", function() {
	var opt = $(this).attr("data-opt");
	var topicid = $(this).attr("data-id");
	if (confirm("确定操作吗")) {
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/news/manage',
			data : {
				"_csrf" : _csrf,
				"topicid" : topicid,
				"opt" : opt
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

$("a.js-news-push").on("click",function(){
	var topicid = $(this).attr("data-id");
	var content = $(this).attr("data-content");
	if (confirm("确定推送吗？？？？？？")) {
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/news/push',
			data : {
				"_csrf" : _csrf,
				"topicid" : topicid,
				"content" : content
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					alert(data.info);
				} else {
					alert(data.info);
				}
			}
		});
	}
});

//点击添加弹幕按钮
$('#pushBtn').click(function(){
	$('#danmuBox .danmu').show();
	//$('#jsSaveDanmu').css('opacity','1');
	var damuTxts=$('#danmuTxt').val();
	var danMu='<div id="js-danmu" class="danmu" data-x="" data-y="">' + emoji.replace_unified(damuTxts) + '<p></p></div>'
    $("#js-danmu").remove();
	$("#danmuBox .imgBox").append(danMu);
	drag($('#danmuBox .danmu'),$('#danmuBox .imgBox'));
	$("#jsSaveDanmu").removeClass("am-disabled");
	$("#pushBtn").addClass("am-disabled");
});

// 保存弹幕
$('#jsSaveDanmu').on('click', function() {
	var topicid = $("#topicid").val();
	var imgid = $("#imgid").val();
	var xAxis = $("#js-danmu").attr("data-x");
	var yLine = $("#js-danmu").attr("data-y");
	var content = $("#danmuTxt").val();
	$.ajax({
		async : false,
		type : "POST",
		url : $CONFIG['home'] + $CONFIG['script'] + '/news/adddanmu',
		data : {
            "_csrf" : _csrf,
			"topicid" : topicid,
			"content" : content,
			"imgid" : imgid,
			"xAxis" : xAxis,
			"yLine" : yLine
		},
		success : function(data) {
			data = $.parseJSON(data);
			if (data.status == 100000) {
				// alert(data.info);
				var html = '<tr id="'+ data.data +'"> \
		            <td>'+ emoji.replace_unified(content) +'</td> \
		            <td> \
			            <div class="am-btn-toolbar"> \
		                  <div class="am-btn-group am-btn-group-xs"> \
		                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only js-remove-danmu" href="javascript:void(0);" data-id="'+ data.data +'">删除</a> \
		                  </div> \
		                </div> \
		            </td> \
		        </tr>';
				$("#js-danmu-list").append(html);
				$("#jsSaveDanmu").addClass("am-disabled");
				$("#pushBtn").removeClass("am-disabled");
				$("#js-danmu").remove();
				$("#danmuTxt").val("");
			} else {
				alert(data.info);
			}
		}
	});
});

// 删除弹幕
$("#js-danmu-list").on("click","a.js-remove-danmu", function() {
	if (confirm("确定操作吗")) {
		var commentid = $(this).attr("data-id");
		$.ajax({
			async : false,
			type : "POST",
			url : $CONFIG['home'] + $CONFIG['script'] + '/news/rmdanmu',
			data : {
				"_csrf" : _csrf,
				"commentid" : commentid
			},
			success : function(data) {
				data = $.parseJSON(data);
				if (data.status == 100000) {
					// alert(data.info);
					$("tr#" + commentid).remove();
					// window.location.reload(true);
				} else {
					alert(data.info);
				}
			}
		});
	}
});

// 搜索新闻
$("#search-news").on("click", function() {
	var name = $("#search-name").val();
	var url = $(this).attr("data-url");
	url = url + "&key=" + name;
	window.location.href = url;
});

// 更新图片描述
$("#updateContent").on("click", function(){
    $.ajax({
        async : false,
        type : "POST",
        url : $CONFIG['home'] + $CONFIG['script'] + '/news/img-content-update',
        data : {
            "_csrf" : _csrf,
            "content" : $("#imgContent").val(),
            "iid" : $("#iid").val()
        },
        success : function(data) {
            data = $.parseJSON(data);
            if (data.status == 100000) {
                alert(data.info);
                setTimeout(function() {
                    window.location.href = data.data;
                }, 1000);
            } else {
                alert(data.info);
            }
        }
    });
});
