<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">贴纸分组</strong> / <small></small></div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/sticker/groupadd"])?>"><span class="am-icon-plus"></span> 新增</a>
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/sticker/groupedits",'type'=>$type])?>"><span class="am-icon-edit"></span> 批量编辑</a>
          </div>
        </div>
      </div>
    <div class="am-u-sm-12 am-u-md-3">
        <div class="am-form-group">
          <select id="select-type">
            <option value="">按类型筛选</option>
            <option value="phone" <?php if($type == 'phone') echo 'selected';?>>phone</option>
            <option value="web" <?php if($type == 'web') echo 'selected';?>>web</option>
          </select>
        </div>
      </div>
      <!-- <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button">搜索</button>
          </span>
        </div>
      </div> -->
    </div>

    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check am-hide"><input type="checkbox" id="select-all"/></th>
                <th class="table-type">编号</th>
                <th class="table-type">名称</th>
                <th class="table-type">类型</th>
                <th class="table-type">排序</th>
                <th class="table-type">状态</th>
                <th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
          <?php if($list){ ?>
          <?php foreach ($list as $item){ ?>
          <tr>
              <td class="am-hide"><input type="checkbox" data-user-id="<?=$item['user_id']?>"/></td>
              <td><?=$item['id']?></td>
              <td><?= $item['name'] ?></td>
              <td><?= $item['type'] ?></td>
              <td><?= $item['order_num'] ?></td>
              <td><?=($item['status']?"已上线":"未上线")?>（<?= $item['status'] ?>）</td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                  <?php if($item['status']==1){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-group-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="off">下线</a>
                  <?php }else{ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-group-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="on">上线</a>
                  <?php }?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-del-item" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>">删除</a>
                  </div>
                </div>
              </td>
            </tr>
          <?php }?>
          <?php }?>
          </tbody>
        </table>
        <?=$pageHtml?>
          <hr />
          <p>注：.....</p>
        </form>
      </div>

    </div>
  </div>
  <script>
  $(function(){
        $("#select-type").on("change",function(){
            var type = $(this).val();
            window.location.href="/index.php/sticker/group?type=" + type;
        });
		// 删除
		$("a.js-del-item").on("click", function() {
			var mid = $(this).attr("data-id");
			if (confirm("确定删除吗")) {
				$.ajax({
					async : false,
					type : "POST",
					url : $CONFIG['home'] + $CONFIG['script'] + '/sticker/groupdel',
					data : {
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
		//上下线
		$("a.js-group-manage").on("click", function() {
			var mid = $(this).attr("data-id");
			var opt = $(this).attr("data-opt");
			if (confirm("确定操作吗")) {
				$.ajax({
					async : false,
					type : "POST",
					url : $CONFIG['home'] + $CONFIG['script'] + '/sticker/groupmanage',
					data : {
						"opt" : opt,
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
  });
  </script>
