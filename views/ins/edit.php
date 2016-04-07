<?php use yii\helpers\Html; ?>
<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">抓取管理</strong> / <small>编辑</small></div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8">
        <form class="am-form am-form-horizontal" id="js-edit-form" method="POST">
          <div class="am-form-group">
            <label for="user-name" class="am-u-sm-3 am-form-label">昵称</label>
            <div class="am-u-sm-9">
              <input type="text" id="cnname" name="cnname" placeholder="" value="<?=Html::encode($info['cnname'])?>">
            </div>
          </div>

          <div class="am-form-group">
            <label for="user-email" class="am-u-sm-3 am-form-label">INS帐号</label>
            <div class="am-u-sm-9">
              <input type="text" placeholder="" value="<?=Html::encode($info['username'])?>" disabled>
            </div>
          </div>

          <div class="am-form-group">
            <label for="user-phone" class="am-u-sm-3 am-form-label">百科地址</label>
            <div class="am-u-sm-9">
              <input type="text" id="baike_url" name="baike_url" placeholder="" value="<?=Html::encode($info['baike_url'])?>">
            </div>
          </div>

          <div class="am-form-group">
            <label for="user-QQ" class="am-u-sm-3 am-form-label">描述</label>
            <div class="am-u-sm-9">
              <input type="text" id="desc" name="desc" placeholder="" value="<?=Html::encode($info['desc'])?>">
            </div>
          </div>
          
          <div class="am-form-group">
            <label for="content_level" class="am-u-sm-3 am-form-label">分级</label>
            <div class="am-u-sm-9">
              <select id="content_level" name="content_level" class="js-content-level">
              	<option value="0" <?php if($info['content_level']==0){?>selected<?php }?>>正常</option>
              	<option value="1" <?php if($info['content_level']==1){?>selected<?php }?>>少儿不宜</option>
              </select>
            </div>
          </div>
          
          <div class="am-form-group">
            <label for="user-QQ" class="am-u-sm-3 am-form-label">状态</label>
            <div class="am-u-sm-9">
              <input type="text" placeholder="" value="<?=$statuses[$info['status']]?>" disabled>
            </div>
          </div>

          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
              <input type="hidden" id="mid" name="mid" value="<?=(string)$info['_id']?>"/>
              <button type="submit" class="am-btn am-btn-primary">保存</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>