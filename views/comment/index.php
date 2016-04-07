<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">评论管理</strong> / <small>共<?php echo $dataCount;?>条, 真实<?php echo $realCount;?>条, 运营<?php echo ($dataCount - $realCount);?>条</small></div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <!-- <button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 保存</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 审核</button> -->
            <a type="button" class="am-btn am-btn-default js-del-all"><span class="am-icon-trash-o"></span> 删除</a>
            &nbsp; &nbsp; &nbsp;<input id="js-robot-comments" data-url="<?=Url::toRoute(['/index.php/comment'])?>" type="checkbox" value="option3" <?php if($flag=="N"){ ?>checked<?php }?>> 隐藏运营人员的评论
          </div>
        </div>
      </div>
      
      <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field" id="search-name">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button" id="search-comment" data-url="<?=Url::toRoute(['/index.php/comment'])?>">搜索</button>
          </span>
        </div>
      </div>
      
    </div>

    
    <div class="am-tabs am-margin">
    <ul class="am-tabs-nav am-nav am-nav-tabs">
      <li<?php if($type=='all'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/comment','type'=>'all', 'flag' => $flag])?>">全部</a></li>
      <li<?php if($type=='grils'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/comment','type'=>'grils', 'flag' => $flag])?>">图片</a></li>
      <li<?php if($type=='news'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/comment','type'=>'news', 'flag' => $flag])?>">新闻</a></li>
      <li<?php if($type=='chartlet'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/comment','type'=>'chartlet', 'flag' => $flag])?>">P图</a></li>
    </ul>

    <div class="am-g am-tabs-bd">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check"><input type="checkbox" id="select-all"/></th><th class="table-title">内容</th><th class="table-type">作者</th><th class="table-author">发布时间</th><th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
          <?php if($list){ ?>
          <?php foreach ($list as $item){ ?>
          <tr>
              <td><input type="checkbox" data-id="<?=$item['id']?>"/></td>
              <td><?= Html::encode($item['content']) ?></td>
              <td><a href="<?=Url::toRoute(['/index.php/comment/user','uid'=>$item['user']['id']])?>"><?= Html::encode($item['user']['name']) ?></a></td>
              <td class=""><?= $item['createTime'] ?></td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <!-- <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</button> -->
                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only js-del-item" href="javascript:void(0);" data-id="<?=$item['id']?>"><span class="am-icon-trash-o"></span> 删除</a>
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
<script>
document.onkeydown = function (e) {
	var theEvent = window.event || e;
	var code = theEvent.keyCode || theEvent.which;
	if (code == 13) {
		$("#search-comment").click();
	}
} 
</script>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/comment.js"></script>
