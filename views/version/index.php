<?php use yii\helpers\Url; ?>
<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">版本管理</strong> / <small></small></div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8">
        <form class="am-form am-form-horizontal" method="POST" id="js-add-form">
          <div class="am-form-group">
            <label for="user-name" class="am-u-sm-3 am-form-label">当前版本</label>
            <div class="am-u-sm-9">
              <input type="text" id="" name="" placeholder="" disabled="disabled" value="<?=$version?>">
            </div>
          </div>

          <div class="am-form-group">
            <label for="user-email" class="am-u-sm-3 am-form-label">新版本</label>
            <div class="am-u-sm-9">
              <input type="text" id="version" name="version" placeholder="">
            </div>
          </div>

          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
              <button type="submit" class="am-btn am-btn-primary">保存</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
  $(function(){
		var addfromOptions = {
			url : $CONFIG['home'] + $CONFIG['script'] + '/version/add',
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
			}
		};
		$('#js-add-form').ajaxForm(addfromOptions);
  });
  </script>