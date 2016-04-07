<?php use yii\helpers\Url; ?>
<style>
  .newsFigure{ position: relative;display:block;}
  .maskOffline{ width:100%;height:100%;position: absolute; left:0; top:0; background:#000;opacity:0.8; filter:alpha(opacity=50);}
  .maskOfflineTxt{width:50px;height:26px;position: absolute; left:50%; top:50%; margin-left:-25px;margin-top: -13px; color:#fff; }
  .childNot{width:40%;height:25%;position: absolute; right:-1px; top:0; background:url("<?= $this->params['cdnUrl'] ?>assets/shan/image/childNot.png") no-repeat 0 0;background-size: contain; }
  .childNot img{width:100%;height:100%;}

  .maskBox{/* display: none; */}
  .childNot{/* display: none; */}
</style>
<div class="admin-content">

  <div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">新闻管理</strong> / <small></small></div>
  </div>

  <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(['/index.php/news/add'])?>"><span class="am-icon-plus"></span> 添加新闻</a>
          </div>
        </div>
      </div>
      <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field" id="search-name">
          <span class="am-input-group-btn">
            <button type="button" class="am-btn am-btn-default" id="search-news" data-url="<?=Url::toRoute(['/index.php/news','type'=>'search'])?>">搜索</button>
          </span>
        </div>
      </div>
    </div>

  <div class="am-tabs am-margin">
    <ul class="am-tabs-nav am-nav am-nav-tabs">
      <li<?php if($type=='all'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/news','type'=>'all'])?>">全部</a></li>
      <li<?php if($type=='on'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/news','type'=>'on'])?>">已上线</a></li>
      <li<?php if($type=='off'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/news','type'=>'off'])?>">已下线</a></li>
      <li<?php if($type=='timer'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/news','type'=>'timer'])?>">定时发布队列</a></li>
    </ul>

    <div class="am-tabs-bd">
      <div class="am-tab-panel am-fade am-in am-active" id="">
        <ul class="am-avg-sm-2 am-avg-md-4 am-avg-lg-6 am-margin gallery-list">
        	<?php if($list){ ?>
		    <?php foreach ($list as $item){ ?>
		      <li data-id="<?=$item['id']?>">
		        <a href="<?=Url::toRoute(['/index.php/news/edit','topicid'=>$item['id']])?>" style="display:block;">
		          <span class="newsFigure">
		          <?php if($item['online']=="N"){ ?>
                  <div id="maskBox" class="maskBox">
                    <div class="maskOffline" style=""></div>
                    <div class="maskOfflineTxt">已下线</div>
                  </div>
		          <?php } ?>
		          <?php if($item['grade']){ ?>
                  <div class="childNot" id="childNot"></div>
		          <?php } ?>
                  <img class="am-img-thumbnail am-img-bdrs" src="<?=$item['coverImage']?>?imageView2/1/w/300/h/200" alt=""/>
              	  </span>
		          <div class="gallery-title"><?=$item['title']?></div>
		          <div class="gallery-desc"><?=$item['timer']?$item['timer']:$item['uploadTime']?></div>
		        </a>
		        <div style="font-size:11px;">
                   <?php if($item['online']=="Y"){ ?>
                      <a class="am-badge am-radius js-news-manage am-badge-danger" href="javascript:void(0);" data-id="<?=$item['id']?>" data-opt="off" style="color:#fff;">下线</a> 
                   <?php }elseif($item['online']=="N"){ ?>
                      <a class="am-badge am-radius js-news-manage am-badge-success" href="javascript:void(0);" data-id="<?=$item['id']?>" data-opt="on" style="color:#fff;">上线</a>
                   <?php }?>
			          <a class="am-badge am-radius js-news-manage" href="javascript:void(0);" data-id="<?=$item['id']?>" data-opt="rm" style="color:#fff;">删除</a>
		        </div>
		      </li>
		    <?php }?>
		    <?php }?>
	    </ul>
	    <?=$pageHtml?>
      </div>

      <div class="am-tab-panel am-fade" id="tab2">
      </div>

      <div class="am-tab-panel am-fade" id="tab3">
      </div>

    </div>
  </div>

</div>
<script>
document.onkeydown = function (e) {
	var theEvent = window.event || e;
	var code = theEvent.keyCode || theEvent.which;
	if (code == 13) {
		$("#search-news").click();
	}
}
</script>
<script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/news.js"></script>
