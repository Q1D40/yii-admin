<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">明星管理</strong> / <small>Table</small></div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/ins/add"])?>"><span class="am-icon-plus"></span> 新增抓取</a>
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/ins/batchadd"])?>"><span class="am-icon-plus"></span> 新增批量</a>
          </div>
        </div>
      </div>
      <div class="am-u-sm-12 am-u-md-3">
        <div class="am-form-group">
          <select id="js-select-order">
            <option value="ctime asc">按时间正序</option>
            <option value="ctime desc" <?php if($order=='ctime desc'){?>selected<?php } ?>>按时间倒序</option>
            <option value="followers desc" <?php if($order=='followers desc'){?>selected<?php } ?>>按粉丝数倒序</option>
            <option value="image_percent asc" <?php if($order=='image_percent asc'){?>selected<?php } ?>>按完整度正序</option>
            <option value="image_percent desc" <?php if($order=='image_percent desc'){?>selected<?php } ?>>按完整度倒序</option>
          </select>
        </div>
      </div>
      <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field" id="search-name">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button" id="search-star" data-url="<?=Url::toRoute(['/index.php/ins'])?>">搜索</button>
          </span>
        </div>
      </div>
    </div>

    <div class="am-tabs am-margin">
    <ul class="am-tabs-nav am-nav am-nav-tabs">
      <li<?php if($type=='all'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/ins','type'=>'all'])?>">全部</a></li>
      <li<?php if($type=='untag'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/ins','type'=>'untag'])?>">未分组</a></li>
    </ul>
    
    
    <div class="am-g am-tabs-bd">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check am-hide"><input type="checkbox" id="select-all"/></th>
                <th class="table-title">昵称</th>
                <th class="table-type">INS账号</th>
                <th class="table-type">粉丝数</th>
                <th class="table-type">描述</th>
                <th class="table-type">Ins总数</th>
                <th class="table-type">6666总数</th>
                <th class="table-type am-hide">视频总数</th>
                <th class="table-type">完整度</th>
                <th class="table-author">状态</th>
                <th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
          <?php if($list){ ?>
          <?php foreach ($list as $item){ ?>
          <tr>
              <td class="am-hide"><input type="checkbox" data-user-id="<?=$item['user_id']?>"/></td>
              <td><a href="<?= Url::toRoute(['/index.php/ins/ulist','uid'=>$item['user_id']]) ?>"><?= Html::encode($item['cnname']) ?></a> | <a style="font-size: 1.2rem;" href="<?= $item['baike_url'] ?>" target="_blank">百科</a></td>
              <td><a href="<?=$item['ins_url']?>" target="_blank"><?= Html::encode($item['username']) ?></a></td>
              <td><?= Html::encode($item['followers']) ?></td>
              <td><?= Html::encode($item['desc']) ?></td>
              <td><?= $item['media_counts']?:0 ?></td>
              <td><?= $item['image_counts']?:0 ?></td>
              <td class="am-hide"><?= $item['other_counts']?:0 ?></td>
              <td><?= ($item['image_percent']?:0)."%" ?></td>
              <td class=""><?=$statuses[$item['status']]?>（<?= $item['status'] ?>）</td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <?php if($item['status']==-1){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-fetch-pause" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>" data-id="<?=(string)$item['_id']?>">暂停抓取</a>
                    <?php }elseif($item['status']==0){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-fetch-goon" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>" data-id="<?=(string)$item['_id']?>">继续抓取</a>
                    <?php }elseif($item['status']==1){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-fetch-off" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>" data-id="<?=(string)$item['_id']?>">下线</a>
                    <?php }elseif($item['status']==2){ ?>
                    <?php }elseif($item['status']==3){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-fetch-on" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>" data-id="<?=(string)$item['_id']?>">上线</a>
                    <?php }elseif($item['status']==4){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-fetch-on" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>" data-id="<?=(string)$item['_id']?>">上线</a>
                    <?php }?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-fetch-edit" href="<?=Url::toRoute(['/index.php/ins/edit','mid'=>(string)$item['_id']])?>">编辑</a>
                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only js-del-item" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>" data-id="<?=(string)$item['_id']?>"><span class="am-icon-trash-o"></span> 删除</a>
                    <?php if($item['status']!=-1){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only js-fetch-again" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>" data-id="<?=(string)$item['_id']?>"><span class="am-icon-repeat"></span> 重抓</a>
                    <?php }?>
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
          <p></p>
        </form>
      </div>

    </div>
    </div>
  </div>
  <?php if($tags){ ?>
  <div class="am-popup" id="my-popup-tags">
  	<div class="am-popup-inner">
	    <div class="am-popup-hd">
	      <h4 class="am-popup-title">选择分类</h4>
	      <span data-am-modal-close class="am-close">&times;</span>
	    </div>
	    <div class="am-popup-bd">
			<div id="js-tags-list"  data-id="" data-user-id="">
			<table class="am-table am-table-striped am-table-hover table-main">
			<!-- <thead><tr><th class="table-check"><input type="checkbox" id="select-all-tags"></th><th class="table-title">分类</th></tr></thead> -->
				<tbody>
				<?php foreach ($tags as $tag){ ?>
					<tr>
						<td width="10%"><input type="checkbox" data-tag-id="<?=$tag['id']?>" data-field-name="<?=$tag['field_name']?>"
							data-tag-name="<?=$tag['name']?>"></td>
						<td width="50%"><?=$tag['name']?></td>
					</tr>
				<?php }?>
				</tbody>
			</table>
			</div>
			<div style="text-align:center">
			<button type="button" class="am-btn am-btn-primary am-btn-xs" id="js-add-tag">确定</button>
			</div>
	    </div>
	  </div>
	</div>
  <?php }?>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>
  <script>
	document.onkeydown = function (e) {
		var theEvent = window.event || e;
		var code = theEvent.keyCode || theEvent.which;
		if (code == 13) {
			$("#search-star").click();
		}
	} 
  </script>