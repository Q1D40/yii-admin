<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<?php $this->params['useBS'] = true;?>
<div class="admin-content">

  <div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">新闻管理</strong> / <small>编辑</small></div>
  </div>
  
  <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="javascript:window.history.go(-1);"><span class=""></span> 返回新闻列表</a>
          </div>
        </div>
      </div>
    </div>

  <div data-am-tabs="" class="am-tabs am-margin">
    <ul class="am-tabs-nav am-nav am-nav-tabs">
      <li class=""><a href="#tab1">图集管理</a></li>
      <li class=""><a href="#tab2">基本信息</a></li>
    </ul>

    <div class="am-tabs-bd">
    
      <div id="tab1" class="am-tab-panel am-fade am-active am-in">
      <ul class="am-avg-sm-2 am-avg-md-4 am-avg-lg-6 am-margin gallery-list">
      <li>
        <a href="<?=Url::toRoute(['/index.php/news/danmu','topicid'=>$topic ['id'],'imgid'=>$topic['coverId']])?>">
          <img class="am-img-thumbnail am-img-bdrs" src="<?=$topic['coverImage']?>?imageView2/1/w/300/h/200" alt=""/>
          <div class="gallery-title">封面</div>
          <div class="gallery-desc"></div>
        </a>
      </li>
      <?php foreach ($topic['images'] as $k=>$img){ ?>
      <li>
        <a href="<?=Url::toRoute(['/index.php/news/danmu','topicid'=>$topic ['id'],'imgid'=>$img['imageId']])?>">
          <img class="am-img-thumbnail am-img-bdrs" src="<?=$img['imageUrl']?>?imageView2/1/w/300/h/200" alt=""/>
          <div class="gallery-title">图集第<?=$k+1?>张</div>
          <div class="gallery-desc"></div>
        </a>
      </li>
      <?php }?>
      </ul>
      </div>
      
      <div id="tab2" class="am-tab-panel am-fade">
        <form class="am-form" id="js-editnews-form" method="POST">
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">标题</div>
            <div class="am-u-sm-8 am-u-md-4">
            <p class="lead emoji-picker-container">
              <input type="email" class="form-control" id="title" name="title" data-emojiable="true" value="<?=Html::encode($topic['title'])?>">
            </p>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>

          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">来源</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" id="sourceName" name="sourceName" placeholder="" class="am-input-sm" value="<?=$topic['sourceName']?>">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">来源地址</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" id="source" name="source" placeholder="" class="am-input-sm" value="<?=$topic['source']?>">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">内容分级</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="radio" name="content_level" class="content_level" value="0" <?php if($topic['grade']==0){ ?>checked<?php } ?>>正常
              <input type="radio" name="content_level" class="content_level" value="1" <?php if($topic['grade']==1){ ?>checked<?php } ?>>少儿不宜
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
          	<div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">&nbsp;</div>
          	<div class="am-u-sm-12 am-u-md-10">
          		<input type="hidden" name="topicid" id="topicid" value="<?=$topic['id']?>"/>
          		<button type="button" id="save" class="am-btn am-btn-primary am-btn-xs">保存</button>
            </div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/nanoscroller.min.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/tether.min.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/config.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/util.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/jquery.emojiarea.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/emoji-picker.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/news.js"></script>

<script>
  $(function() {
    // Initializes and creates emoji set from sprite sheet
    window.emojiPicker = new EmojiPicker({
      emojiable_selector: '[data-emojiable=true]',
      assetsPath: '<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/img/',
      popupButtonClasses: 'fa fa-smile-o'
    });
    // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
    // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
    // It can be called as many times as necessary; previously converted input fields will not be converted again
    window.emojiPicker.discover();
  });
</script>
