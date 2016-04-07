<?php use yii\helpers\Html;?>
<?php use app\models\Base;?>
  
<div class="admin-content">

  <div class="am-cf am-padding">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">标签管理</strong> / <small>编辑</small></div>
  </div>

  <div data-am-tabs="" class="am-tabs am-margin">
    <ul class="am-tabs-nav am-nav am-nav-tabs">
      <li class=""><a href="#tab1">成员管理</a></li>
      <li class=""><a href="#tab2">基本信息</a></li>
    </ul>

    <div class="am-tabs-bd">
    
      <div id="tab1" class="am-tab-panel am-fade am-active am-in">
        <div class="am-g">
		  <div class="am-u-lg-6">
		    <div class="am-input-group">
		      <div>
		        <textarea placeholder="" id="js-selected-names" rows="2" cols="50" class=""></textarea>
		        <input type="hidden" name="js-selected-ids" id="js-selected-ids" value="">
		        <button class="am-btn am-btn-default am-input-sm" id="js-select-stars" type="button">选择明星</button>
		        <button class="am-btn am-btn-default am-input-sm" id="submit-users" type="button">提交</button>
		      </div>
		    </div>
		  </div>
		</div>
        <form class="am-form">
	        <table class="am-table am-table-striped am-table-hover table-main">
	            <thead>
	              <tr>
	                <th class="table-check am-hide"><input type="checkbox" id="select-all"/></th>
	                <th class="table-title">昵称</th>
	                <th class="table-type">INS账号</th>
	                <th class="table-set">操作</th>
	              </tr>
	          </thead>
	          <tbody>
	          	<?php if($tagusers){ ?>
	          		<?php foreach ($tagusers as $item){ ?>
	          		  <tr>
		                <td class="table-check am-hide"><input type="checkbox"/></td>
		                <td class=""><?=$item['cnname']?></td>
		                <td class=""><?=$item['username']?></td>
		                <td>
			                <div class="am-btn-toolbar">
			                  <div class="am-btn-group am-btn-group-xs">
			                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only js-taguser-remove" href="javascript:void(0);" data-user-id="<?=$item['user_id']?>"><span class="am-icon-trash-o"></span> 删除</a>
			                  </div>
			                </div>
			              </td>
		              </tr>
	          		<?php }?>
	          	<?php }?>
	          </tbody>
	        </table>
        </form>
      </div>
      
      <div id="tab2" class="am-tab-panel am-fade">
        <form class="am-form" id="js-edittag-form" method="POST">
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">名称</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" class="am-input-sm" id="name" name="name" value="<?=Html::encode($info['name'])?>">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">说明</div>
            <div class="am-u-sm-8 am-u-md-4">
              <textarea class="am-input-sm" id="desc" name="desc"><?=Html::encode($info['desc'])?></textarea>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>

          <?php if($info['type']==2){ ?>
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">banner图片</div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end col-end">
              <input type="file" name="banner" id="banner" placeholder="" value="" class="am-input-sm">
              <small><a href="<?=Yii::$app->params['qiniuDomain'][Base::env()].$info['banner_key']?>" target="_blank">图片地址</a></small>
            </div>
          </div>
          <?php }?>

          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">顺序 </div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" id="order_num" name="order_num" placeholder="" class="am-input-sm" value="<?=$info['order_num']?>">
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <?php if($info['type']==1){ ?>
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">类型 </div>
            <div class="am-u-sm-8 am-u-md-4">
              <select id="level" name="level">
              	<option value="1" <?php if($info['level']==1){?>selected<?php }?>>无</option>
              	<option value="2" <?php if($info['level']==2){?>selected<?php }?>>最热</option>
              	<option value="3" <?php if($info['level']==3){?>selected<?php }?>>最新</option>
              </select>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          <?php }?>
          
          <?php if($info['type']==1){ ?>
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">尺寸 </div>
            <div class="am-u-sm-8 am-u-md-4">
              <select id="size" name="size">
              	<option value="1" <?php if($info['size']==1){?>selected<?php }?>>正常</option>
              	<option value="2" <?php if($info['size']==2){?>selected<?php }?>>大图</option>
              </select>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          <?php }?>

          <?php if($info['type']==1){ ?>
          <input type="hidden" name="start_time" id="start_time" value="<?=$info['start_time']?>"/>
          <input type="hidden" name="end_time" id="end_time" value="<?=$info['end_time']?>"/>
          <?php }elseif($info['type']==2){ ?>
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">起始时间</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" id="start_time" name="start_time" placeholder="" class="am-input-sm" value="<?=$info['start_time']?>">
            </div>
            <div class="am-u-sm-12 am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">结束时间</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="text" id="end_time" name="end_time" placeholder="" class="am-input-sm" value="<?=$info['end_time']?>">
            </div>
            <div class="am-u-sm-12 am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">打开方式</div>
            <div class="am-u-sm-8 am-u-md-4">
              <input type="radio" value="member" id="flag_member" name="flag_radio" <?php if($info['flag']=="member"){ ?>checked="checked"<?php }?>>成员列表
              <input type="radio" value="url" id="flag_url" name="flag_radio" <?php if($info['flag']=="url"){ ?>checked="checked"<?php }?>>活动链接
              <input type="text" class="form-datetime am-form-field" name="activity_url" value="<?=$info['url']?>">
            </div>
            <div class="am-u-sm-12 am-u-md-6"></div>
          </div>
          <?php }?>
          
          <div class="am-g am-margin-top">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">分级 </div>
            <div class="am-u-sm-8 am-u-md-4">
              <select id="content_level" name="content_level">
              	<option value="0" <?php if($info['content_level']==0){?>selected<?php }?>>正常</option>
              	<option value="1" <?php if($info['content_level']==1){?>selected<?php }?>>少儿不宜</option>
              </select>
            </div>
            <div class="am-hide-sm-only am-u-md-6"></div>
          </div>
          
          <div class="am-g am-margin-top">
          	<div class="am-u-sm-12 am-u-md-2 am-text-right admin-form-text">&nbsp;</div>
          	<div class="am-u-sm-12 am-u-md-10">
          		<input type="hidden" name="mid" id="mid" value="<?=$mid?>"/>
          		<input type="hidden" name="type" id="type" value="<?=$info['type']?>"/>
          		<button type="submit" class="am-btn am-btn-primary am-btn-xs">保存</button>
            </div>
		  </div>

        </form>
      </div>

      

    </div>
  </div>
</div>
<div class="am-popup" id="my-popup-news">
  <div class="am-popup-inner">
    <div class="am-popup-hd">
      <h4 class="am-popup-title">选择明星</h4>
      <span data-am-modal-close class="am-close">&times;</span>
    </div>
    <div class="am-g">
	  <div class="am-u-lg-6">
	    <div class="am-input-group">
	      <input type="text" class="am-form-field" id="aj-search-name">
	      <span class="am-input-group-btn">
	        <button class="am-btn am-btn-default" type="button" id="aj-search-star">搜索</button>
	      </span>
	    </div>
	  </div>
	</div>
    <div class="am-popup-bd" id="js-fav-list">
		<div id="js-news-list"></div>
		<div style="text-align:center">
		<button type="button" class="am-btn am-btn-primary am-btn-xs" id="js-add-sure">确定</button>
		</div>
    </div>
  </div>
</div>
<script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/ins.js"></script>