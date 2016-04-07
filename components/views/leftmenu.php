<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
  <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
    <div class="am-offcanvas-bar admin-offcanvas-bar">
      <ul class="am-list admin-sidebar-list">
        <li><a href="<?=Url::toRoute(['/index.php/admin'])?>"><span class="am-icon-home"></span> 首页</a></li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span> 内容管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
            <li><a href="<?=Url::toRoute(['/index.php/news'])?>" class="am-cf"><span class="am-icon-check"></span> 新闻管理</a></li>
            <li><a href="<?=Url::toRoute(['/index.php/comment'])?>" class="am-cf"><span class="am-icon-check"></span> 评论管理</a></li>
            <!-- <li><a href="admin-help.html"><span class="am-icon-puzzle-piece"></span> 帮助页</a></li>
            <li><a href="admin-gallery.html"><span class="am-icon-th"></span> 相册页面<span class="am-badge am-badge-secondary am-margin-right am-fr">24</span></a></li>
            <li><a href="admin-log.html"><span class="am-icon-calendar"></span> 系统日志</a></li>
            <li><a href="admin-404.html"><span class="am-icon-bug"></span> 404</a></li> -->
          </ul>
        </li>
        <li><a href="<?=Url::toRoute(['/index.php/mbox'])?>"><span class="am-icon-table"></span> 影视盒子</a></li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav-fetch'}"><span class="am-icon-file"></span> 抓取管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav-fetch">
            <li><a href="<?=Url::toRoute(['/index.php/ins'])?>" class="am-cf"><span class="am-icon-check"></span> 明星管理</a></li>
            <li><a href="<?=Url::toRoute(['/index.php/ins/tags','type'=>1])?>" class="am-cf"><span class="am-icon-check"></span> 分类管理</a></li>
            <li><a href="<?=Url::toRoute(['/index.php/ins/tags','type'=>2])?>" class="am-cf"><span class="am-icon-check"></span> 活动分类</a></li>
            <li><a href="<?=Url::toRoute(['/index.php/inslog'])?>" class="am-cf"><span class="am-icon-check"></span> 抓取日志</a></li>
          </ul>
        </li>
        <li class="admin-parent">
          <a class="am-cf" data-am-collapse="{target: '#collapse-nav-sticker'}"><span class="am-icon-file"></span> 贴纸管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
          <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav-sticker">
            <li><a href="<?=Url::toRoute(['/index.php/sticker/index'])?>" class="am-cf"><span class="am-icon-check"></span> 贴纸管理</a></li>
            <li><a href="<?=Url::toRoute(['/index.php/sticker/group'])?>" class="am-cf"><span class="am-icon-check"></span> 分组管理</a></li>
          </ul>
        </li>
        <li class=""><a href="<?=Url::toRoute(['/index.php/version'])?>"><span class="am-icon-file"></span> 分级版本</a></li>
        <li><a href="<?=Url::toRoute(['/index.php/admin/logout'])?>"><span class="am-icon-sign-out"></span> 注销</a></li>
      </ul>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-bookmark"></span> 公告</p>
          <p>时光静好，与君语；细水流年，与君同。—— Amaze UI</p>
        </div>
      </div>

      <div class="am-panel am-panel-default admin-sidebar-panel">
        <div class="am-panel-bd">
          <p><span class="am-icon-tag"></span> wiki</p>
          <p>Welcome to the Amaze UI wiki!</p>
        </div>
      </div>
    </div>
  </div>
