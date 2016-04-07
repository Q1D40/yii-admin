<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">明星管理</strong> / <small><?=$user['cnname']?></small></div>
    </div>

    <ul class="am-avg-sm-2 am-avg-md-4 am-avg-lg-6 am-margin gallery-list">
      <?php foreach ($list as $item){ ?>
      <li data-id="<?=(string)$item['_id']?>" data-status="<?=$item['status']?>" data-img-id="<?=$item['id']?>">
        <a href="<?=Url::toRoute(['/index.php/ins/feedstickers','fid'=>(string)$item['_id']])?>" target="_blank">
          <img class="am-img-thumbnail am-img-bdrs" src="<?=$item['img_url']?>?imageView2/1/w/300/h/200" alt=""/>
          <div class="gallery-title"><?=$item['caption_text']?></div>
          <div class="gallery-desc"><?=date("Y-m-d H:i:s",$item['created_time'])?></div>
        </a>
        <div style="font-size:11px;">
        <?php if($item['status']==4){ ?>
	        <a class="am-badge am-radius js-img-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="on">上线</a> | 
        <?php }elseif($item['status']==1){ ?>
	        <a class="am-badge am-radius js-img-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="off">下线</a> | 
        <?php }?>
	        <a class="am-badge am-radius js-img-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="rm">删除</a> |
	        <a class="am-badge am-radius js-img-manage" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>" data-opt="reset">重抓</a>
        </div>
      </li>
      <?php }?>
    </ul>

    <?=$pageHtml?>
  </div>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>