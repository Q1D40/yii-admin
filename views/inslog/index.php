<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">抓取日志</strong> / <small></small></div>
    </div>

    <!-- <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/ins/add"])?>"><span class="am-icon-plus"></span> 新增抓取</a>
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(["/index.php/ins/batchadd"])?>"><span class="am-icon-plus"></span> 新增批量</a>
          </div>
        </div>
      </div>
      <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field" id="search-name">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button" id="search-star" data-url="<?=Url::toRoute(['/index.php/ins'])?>">搜索</button>
          </span>
        </div>
      </div>
    </div> -->

    <div class="am-g">
      <div class="am-u-sm-12">
        <form class="am-form">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check am-hide"><input type="checkbox" id="select-all"/></th>
                <th class="" width="">程序</th>
                <th class="" width="">明星</th>
                <th class="" width="">类型</th>
                <th class="" width="">信息</th>
                <th class="" width="">数据</th>
                <th class="" width="">时间</th>
                <!-- <th class="table-set">操作</th> -->
              </tr>
          </thead>
          <tbody>
          <?php if($list){ ?>
          <?php foreach ($list as $item){ ?>
          <tr>
              <td class="am-hide"><input type="checkbox"/></td>
              <td width="10%"><a href="<?=Url::toRoute(['/index.php/inslog','action'=>$item['action']])?>" target="_blank"><?= $item['action'] ?></a></td>
              <td width="5%"><a href="<?=Url::toRoute(['/index.php/inslog','uid'=>$item['user_id']])?>" target="_blank"><?= Html::encode($item['user_name'])?:$item['user_id'] ?></a></td>
              <td width="5%"><a href="<?=Url::toRoute(['/index.php/inslog','err_type'=>$item['err_type']])?>" target="_blank"><?= $item['err_type'] ?></a></td>
              <td width="20%"><?= Html::encode($item['err_msg']) ?></td>
              <td width="20%"><?= json_encode($item['data']) ?></td>
              <td width="10%" class=""><?= date("Ymd H:i:s",$item['start_time']) ?><br><?= date("Ymd H:i:s",$item['end_time']) ?></td>
              <!-- <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" href="javascript:void(0);" data-id="<?=(string)$item['_id']?>"><span class="am-icon-trash-o"></span> 删除</a>
                  </div>
                </div>
              </td> -->
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
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>