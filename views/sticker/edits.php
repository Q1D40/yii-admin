<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">贴纸</strong> / <small>批量编辑</small></div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main" id="js-tags-table">
            <thead>
              <tr>
                <th class="">编号</th>
                <th class="">图片</th>
                <th class="">名称</th>
                <th class="">顺序</th>
              </tr>
          </thead>
          <tbody>
          <?php if($list){ ?>
          <?php foreach ($list as $item){ ?>
            <tr data-id="<?=(string)$item['_id']?>">
              <td width="5%"><?= Html::encode($item['id']) ?></td>
              <td width="5%"><img width="100" alt="" src="<?=$item['url']?>"></td>
              <td width="5%"><input class="js-name" type="text" style="width: 100%;" value="<?= Html::encode($item['name']) ?>"/></td>
              <td width="10%"><input class="js-order" type="text" style="width: 100%;" value="<?= $item['order_num'] ?>"/></td>
            </tr>
          <?php }?>
          <?php }?>
          </tbody>
        </table>
        <?=$pageHtml?>
          <hr />
          <div class="am-margin">
		    <button class="am-btn am-btn-primary am-btn-xs" type="button" id="js-save-stickers">提交</button>
		    <a class="am-btn am-btn-primary am-btn-xs" href="javascript:window.history.go(-1)">放弃</a>
		  </div>
        </form>
      </div>

    </div>
  </div>
  <script>
  $(function(){
	    var _csrf = $("#_csrf").val();
		// 批量保存
		$("#js-save-stickers").on("click",function(){
			var submits = [];
			$("#js-tags-table tbody tr").each(function(index,domEle){
				var thisValues = {};
				var id = $(domEle).attr("data-id");
				var orderNum = $(domEle).find(".js-order").val();
				var name = $(domEle).find(".js-name").val();
				thisValues['id'] = id;
				thisValues['orderNum'] = orderNum;
				thisValues['name'] = name;
				submits.push(thisValues);
				// console.log(id,orderNum);
			});
			$.ajax({
				async : false,
				type : "POST",
				url : $CONFIG['home'] + $CONFIG['script'] + '/sticker/edits',
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
  });
  </script>