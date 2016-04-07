<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">分类</strong> / <small>批量编辑</small></div>
    </div>

    <!-- <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/ins/addtag",'type'=>$type])?>"><span class="am-icon-plus"></span> 新增</a>
          </div>
        </div>
      </div>
    </div> -->

    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main" id="js-tags-table">
            <thead>
              <tr>
                <th class="">编号</th>
                <th class="">名称</th>
                <th class="">顺序</th>
                <?php if($type==1){ ?>
                <th class="">尺寸</th>
                <th class="">类型</th>
                <?php }?>
                <th class="">上/下线</th>
                <th class="">分级</th>
              </tr>
          </thead>
          <tbody>
          <?php if($list){ ?>
          <?php foreach ($list as $item){ ?>
            <tr data-id="<?=(string)$item['_id']?>">
              <td width="5%"><?= Html::encode($item['id']) ?></td>
              <td width="5%"><?= Html::encode($item['name']) ?></td>
              <td width="10%"><input class="js-order" type="text" style="width: 100%;" value="<?= $item['order_num'] ?>"/></td>
              <?php if($type==1){ ?>
              <td width="10%">
              <select id="size" name="size" class="js-size">
              	<option value="1" <?php if($item['size']==1){?>selected<?php }?>>正常</option>
              	<option value="2" <?php if($item['size']==2){?>selected<?php }?>>大图</option>
              </select>
              </td>
              <td width="10%">
              <select id="level" name="level" class="js-level">
              	<option value="1">无</option>
              	<option value="2" <?php if($item['level']==2){?>selected<?php }?>>最热</option>
              	<option value="3" <?php if($item['level']==3){?>selected<?php }?>>最新</option>
              </select>
              </td>
              <?php }?>
              <td width="10%">
              <select id="status" name="status" class="js-status">
              	<option value="1" <?php if($item['status']==1){?>selected<?php }?>>上线</option>
              	<option value="0" <?php if($item['status']==0){?>selected<?php }?>>下线</option>
              </select>
              </td>
              <td width="10%">
              <select id="content_level" name="content_level" class="js-content-level">
              	<option value="0" <?php if($item['content_level']==0){?>selected<?php }?>>正常</option>
              	<option value="1" <?php if($item['content_level']==1){?>selected<?php }?>>少儿不宜</option>
              </select>
              </td>
            </tr>
          <?php }?>
          <?php }?>
          </tbody>
        </table>
        <?=$pageHtml?>
          <hr />
          <div class="am-margin">
		    <button class="am-btn am-btn-primary am-btn-xs" type="button" id="js-save-tags">提交</button>
		    <a class="am-btn am-btn-primary am-btn-xs" href="javascript:window.history.go(-1)">放弃</a>
		  </div>
        </form>
      </div>

    </div>
  </div>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>