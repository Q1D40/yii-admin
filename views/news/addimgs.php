<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">新闻管理</strong> / <small>新增</small></div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8">
        <form class="am-form am-form-horizontal" id="js-editimgs-form">
          <?php if($topic['images']){ ?>
          <?php foreach ($topic['images'] as $item){ ?>
          <div class="am-form-group" id="<?=$item['imageId']?>">
            <label for="" class="am-u-sm-3 am-form-label"></label>
            <div class="am-u-sm-9" style="border:1px solid #999;padding:15px;">
              <img alt="" src="<?=$item['imageUrl']?>" width="150" style="margin-bottom:10px;">
              <textarea  data-img="<?=$item['imageUrl']?>" class="js-img-content"  placeholder="在这里写下文字描述"><?=$item['content']?></textarea>
              <div style="font-size:11px;margin-top:10px;">
			    <a href="javascript:void(0);" data-imgid="<?=$item['imageId']?>" class="am-badge am-radius js-imgs-remove">删除这个图片</a>
		      </div>
            </div>
          </div>
          <br>
          <?php }?>
          <?php }?>
          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
              <input type="hidden" name="topicid" id="topicid" value="<?=$topic['id']?>">
              <button type="button" id="js-editimgs-submit" class="am-btn am-btn-primary">提交</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/news.js"></script>
