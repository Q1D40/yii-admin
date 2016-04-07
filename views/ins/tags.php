<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">活动分类</strong> / <small>活动分类</small></div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/ins/addtag",'type'=>$type])?>"><span class="am-icon-plus"></span> 新增</a>
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/ins/edittags",'type'=>$type])?>"><span class="am-icon-edit"></span> 批量编辑</a>
          </div>
        </div>
      </div>
      <!-- <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button">搜索</button>
          </span>
        </div>
      </div> -->
    </div>

    <div class="am-tabs am-margin">
    <ul class="am-tabs-nav am-nav am-nav-tabs">
      <li<?php if($status=='-1'){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/ins/tags','type'=>$type])?>">全部</a></li>
      <li<?php if($status==1){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/ins/tags','type'=>$type,'status'=>1])?>">已上线</a></li>
      <li<?php if($status==0){ ?> class="am-active"<?php }?>><a href="<?=Url::toRoute(['/index.php/ins/tags','type'=>$type,'status'=>0])?>">未上线</a></li>
    </ul>
    
    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check am-hide"><input type="checkbox" id="select-all"/></th>
                <th class="table-title">编号</th>
                <th class="table-type">名称</th>
                <th class="table-type">明星数量</th>
                <th class="table-type">顺序</th>
                <?php if($type==2){ ?>
                <th class="table-type">有效时间</th>
                <?php }?>
                <th class="table-type">状态</th>
                <th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
          <?php if($list){ ?>
          <?php foreach ($list as $item){ ?>
          <tr>
              <td class="am-hide"><input type="checkbox" data-user-id="<?=$item['user_id']?>"/></td>
              <td><?= Html::encode($item['id']) ?></td>
              <?php if($item['type']==1){ ?>
              <td><?= Html::encode($item['name']) ?></td>
              <?php }elseif($item['type']==2){ ?>
              	<?php if($item['flag']=='member'){ ?>
              	<td><?= Html::encode($item['name']) ?></td>
              	<?php }elseif ($item['flag']=='url'){ ?>
              	<td><a href="<?=Url::toRoute(['/index.php/ins/tlist','tid'=>$item['id']])?>"><?= Html::encode($item['name']) ?></a></td>
              	<?php }?>
              <?php }?>
              <td><?= count($item['artists'])?:0 ?></td>
              <td><span class="js-chg-order am-badge am-radius"><?= $item['order_num'] ?></span></td>
              <?php if($type==2){ ?>
              <td><?= $item['start_time'] ?><br><?= $item['end_time'] ?></td>
              <?php }?>
              <td><?php if($item['status']==0){?>未上线<?php }else if($item['status']==1){?>已上线<?php }else { ?>其它<?php }?>（<?= $item['status'] ?>）</td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <?php if($item['status']==0){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-tag-on" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>">上线</a>
                    <?php }elseif($item['status']==1){ ?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary js-tag-off" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>">下线</a>
                    <?php }?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?=Url::toRoute(['/index.php/ins/edittag','mid'=>(string)$item['_id']])?>"> 编辑</a>
                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only js-deltag-item" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>"><span class="am-icon-trash-o"></span> 删除</a>
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
          <p>注：.....</p>
        </form>
      </div>

    </div>
    
    </div>
    
  </div>
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>