<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">抓取管理</strong> / <small>新增批量</small></div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8">
        <form class="am-form am-form-horizontal" id="js-addbatch-form" method="POST">
          <div class="am-form-group">
            <label for="user-QQ" class="am-u-sm-3 am-form-label">数据</label>
            <div class="am-u-sm-9">
              <textarea id="batchinfo" name="batchinfo" placeholder="每个明星占一行，格式：明星名字,描述,Ins地址,百科地址" rows="36"></textarea>
            </div>
          </div>
          
          <?php if($tags){ ?>
          <div class="am-form-group">
            <label for="user-QQ" class="am-u-sm-3 am-form-label">分类</label>
            <div class="am-u-sm-9">
            	<select id="tag" name="tag">
            		<option value="0">无</option>
            		<?php foreach ($tags as $tag){ ?>
            		<option value="<?=$tag['field_name']?>"><?=$tag['name']?></option>
            		<?php }?>
            	</select>
            </div>
          </div>
          <?php }?>

          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
              <button type="submit" class="am-btn am-btn-primary">保存</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>