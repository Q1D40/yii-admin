<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">评论管理</strong> / <small></small></div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <!-- <button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 保存</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 审核</button> -->
            <a type="button" class="am-btn am-btn-default js-del-all"><span class="am-icon-trash-o"></span> 删除</a>
          </div>
        </div>
      </div>
      <!-- <div class="am-u-sm-12 am-u-md-3">
        <div class="am-form-group">
          <select data-am-selected="{btnSize: 'sm'}">
            <option value="option1">所有类别</option>
            <option value="option2">IT业界</option>
            <option value="option3">数码产品</option>
            <option value="option3">笔记本电脑</option>
            <option value="option3">平板电脑</option>
            <option value="option3">只能手机</option>
            <option value="option3">超极本</option>
          </select>
        </div>
      </div> -->
      
      <!-- <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field" id="search-name">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button" id="search-comment" data-url="<?=Url::toRoute(['/index.php/comment'])?>">搜索</button>
          </span>
        </div>
      </div> -->
      
    </div>

    <div class="am-g">
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
              <td><a href="<?=Url::toRoute(['/index.php/comment/user','uid'=>$item['user']['id'],'name'=>$item['user']['name']])?>"><?= Html::encode($item['user']['name']) ?></a></td>
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
  <script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/comment.js"></script>