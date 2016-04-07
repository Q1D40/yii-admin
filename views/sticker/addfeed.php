<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">贴纸feed</strong> / <small>新增</small></div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8">
        <form class="am-form am-form-horizontal" id="js-addsticker-form" method="POST">
          
          <div class="am-form-group">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">图片</div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
              <input type="file" name="image" id="image" placeholder="" value="" class="am-input-sm">
            </div>
          </div>
          
          <hr>
          
          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
              <input type="hidden" name="activity_id" id="activity_id" value="<?=$activityId?>">
              <button type="submit" class="am-btn am-btn-primary">保存</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
  $(function(){
		var options = {
			url : $CONFIG['home'] + $CONFIG['script'] + '/sticker/addfeed',
			dataType : 'json',
			success : function(data) {
				if (data.status == 100000) {
					setTimeout(function() {
						window.location.href = data.data;
					}, 1000);
				} else {
					alert(data.info);
				}
			}
		};
		$('#js-addsticker-form').ajaxForm(options);
  });
  </script>