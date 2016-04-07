<div class="admin-content">

  <div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">分组管理</strong> / <small>新增分组</small></div>
  </div>

  <div data-am-tabs="" class="am-tabs am-margin">
    <div class="am-tabs-bd">
      <div id="tab1" class="am-tab-panel am-fade">
        <form class="am-form" id="js-addgroup-form" method="POST">
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">名称</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" class="am-input-sm" id="name" name="name">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">类型</div>
            <div class="am-u-sm-8 am-u-md-4">
              <select name="type" id="type">
              <option value="web">网页端</option>
              <option value="phone">手机端</option>
              </select>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
          	<div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">&nbsp;</div>
          	<div class="am-u-sm-12 am-u-md-10">
          		<button type="submit" class="am-btn am-btn-primary am-btn-xs">保存</button>
            </div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
	var options = {
		url : $CONFIG['home'] + $CONFIG['script'] + '/sticker/groupadd',
		dataType : 'json',
		success : function(data) {
			if (data.status == 100000) {
				//alert(data.info);
				setTimeout(function() {
					window.location.href = data.data;
				}, 1000);
			} else {
				alert(data.info);
			}
		}
	};
	$('#js-addgroup-form').ajaxForm(options);
  });
</script>