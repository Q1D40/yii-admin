<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">贴纸管理</strong> / <small>新增</small></div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8">
        <form class="am-form am-form-horizontal" id="js-addsticker-form" method="POST">
          <div class="am-form-group">
            <label for="user-QQ" class="am-u-sm-3 am-form-label">贴纸</label>
            <div class="am-u-sm-9">
              <textarea id="data" name="data" placeholder="每个贴纸占一行，格式：名称,URL" rows="20"></textarea>
            </div>
          </div>
          
          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">分组</label>
            <div class="am-u-sm-9">
            	<select id="group" name="group">
            		<?php foreach ($groups as $tag){ ?>
            		<option value="<?=$tag['id']?>"><?=$tag['name']?></option>
            		<?php }?>
            	</select>
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
		var options = {
			url : $CONFIG['home'] + $CONFIG['script'] + '/sticker/add',
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
			},
			beforeSubmit : function() {
			}
		};
		$('#js-addsticker-form').ajaxForm(options);
  });
  </script>