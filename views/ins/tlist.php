<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">贴纸</strong> / <small></small></div>
    </div>
    
    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/sticker/addfeed",'activity_id'=>$tagId])?>"><span class="am-icon-plus"></span> 新增图片</a>
          </div>
        </div>
      </div>
    </div>

    <ul class="am-avg-sm-2 am-avg-md-4 am-avg-lg-6 am-margin gallery-list">
      <?php foreach ($list as $item){ ?>
      <li data-id="<?=(string)$item['_id']?>" data-status="<?=$item['status']?>" data-img-id="<?=$item['id']?>">
        <a href="<?=$item['img_url']?>" target="_blank">
          <img class="am-img-thumbnail am-img-bdrs" src="<?=$item['img_url']?>?imageView2/1/w/300/h/200" alt=""/>
          <div class="gallery-title"><?=$item['content']?></div>
          <div class="gallery-desc"><?=date("Y-m-d H:i:s",$item['created_time'])?></div>
        </a>
        <div style="font-size:11px;">
        <?php if($item['status']==4){ ?>
	        <a class="am-badge am-radius js-tagimg-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="on">上线</a> | 
        <?php }elseif($item['status']==1){ ?>
	        <a class="am-badge am-radius js-tagimg-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="off">下线</a> | 
        <?php }?>
	        <a class="am-badge am-radius js-tagimg-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="rm">删除</a>
        </div>
      </li>
      <?php }?>
    </ul>
    <?=$pageHtml?>
  </div>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>
  <script>
	$(function(){
		
	});
  </script>