<div class="admin-content">

  <div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">影视盒子</strong> / <small>form</small></div>
  </div>

  <div class="am-tabs am-margin" data-am-tabs>
    <ul class="am-tabs-nav am-nav am-nav-tabs">
    <?php foreach ($list as $typeId => $item){ ?>
      <li class="am-active" data-typeid="<?=$typeId?>"><a href="#tab<?=$typeId?>" data-typeid="<?=$typeId?>"><?=$item['typeName']?></a></li>
    <?php }?>
    </ul>

    <div class="am-tabs-bd">
    <?php foreach ($list as $typeId => $item){ ?>
      <div class="am-tab-panel am-fade am-in am-active" id="tab<?=$typeId?>" data-typeid="<?=$typeId?>">
        <form class="am-form">
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">当前新闻</div>
            <div class="am-u-sm-12 am-u-md-10">
            <?php foreach ($item['topics'] as $k=>$topic){?>
            <p><?=$k+1?>、<?=$topic['title']?></p>
            <?php }?>
            </div>
          </div>
        
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">&nbsp;</div>
            <div class="am-hide-sm-only am-u-md-6"><a class="am-btn am-btn-default am-btn-sm js-select-news" data-typeid="<?=$typeId?>" href="javascript:void(0);">选择新闻</a></div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="hidden" class="am-input-sm" id="js-selected-news-ids-<?=$typeId?>">
            </div>
          </div>
          <hr>
          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">选中新闻<!-- （最多<?=$item['newsNum']?>条） --></div>
            <div class="am-u-sm-12 am-u-md-10" id="js-selected-news-<?=$typeId?>">
            </div>
          </div>
          <hr>
          <div class="am-g am-margin-top-sm">
          	<div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">&nbsp;</div>
          	<div class="am-u-sm-12 am-u-md-10" id="js-selected-news">
          		<button type="button" class="am-btn am-btn-primary am-btn-xs js-save-news" data-typeid="<?=$typeId?>">提交</button>
          		<button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="javascript:window.location.reload(true);">放弃</button>
            </div>
		  </div>
        </form>
      </div>
    <?php }?>
      <!-- <div class="am-tab-panel am-fade am-in am-active" id="tab1">
        <form class="am-form">
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right"><strong>新闻编号</strong></div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" class="am-input-sm" id="js-selected-news-ids">
            </div>
            <div class="am-hide-sm-only am-u-md-6"><a class="am-btn am-btn-default am-btn-sm" id="js-select-news" href="javascript:void(0);">选择新闻</a></div>
          </div>

          <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">选中新闻</div>
            <div class="am-u-sm-12 am-u-md-10" id="js-selected-news">
            </div>
          </div>
          <hr>
          <div class="am-g am-margin-top-sm">
          	<div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">&nbsp;</div>
          	<div class="am-u-sm-12 am-u-md-10" id="js-selected-news">
          		<button type="button" class="am-btn am-btn-primary am-btn-xs" id="js-save">提交保存</button>
          		<button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="">放弃保存</button>
            </div>
		  </div>
        </form>
      </div> -->

    </div>
  </div>

</div>
<div class="am-popup" id="my-popup-news">
  <div class="am-popup-inner">
    <div class="am-popup-hd">
      <h4 class="am-popup-title">选择新闻</h4>
      <span data-am-modal-close class="am-close">&times;</span>
    </div>
    <div class="am-popup-bd" id="js-fav-list">
	    <!-- <div class="am-g">
			<div class="am-u-sm-12 am-u-md-6">
				<div class="am-btn-toolbar">
					<div class="am-btn-group am-btn-group-xs">
						<button class="am-btn am-btn-default" type="button" id="js-add-submit">
							<span class="am-icon-plus"></span> 添加
						</button>
					</div>
				</div>
			</div>
		</div> -->
		<div id="js-news-list"></div>
		<div style="text-align:center">
		<!-- <button type="button" class="am-btn am-btn-primary am-btn-xs" id="js-add-submit">添加选中</button> -->
		<button type="button" class="am-btn am-btn-primary am-btn-xs" id="js-add-sure">确定</button>
		</div>
    </div>
  </div>
</div>
<script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/mbox.js"></script>