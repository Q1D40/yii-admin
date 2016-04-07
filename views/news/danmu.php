<?php use yii\helpers\Url; ?>
<?php $this->params['useBS'] = true;?>
  <!-- content start -->
  <style>
  	.danmu{max-width:200px; position:absolute;top:0px;left:0;padding:4px 7px 4px 7px; text-align: left; border-radius:6px 6px 6px 6px; color:#fff; z-index:66; font-size: 12px; font-family:"冬青黑体简体中文","微软雅黑","宋体",Arial,sans-serif;}
  	.danmu P{ position:absolute;top:0;left:0; width:100%; height:100%;border-radius:6px 6px 6px 6px; background:#343434; z-index:-1; opacity:0.88;}
  	.danmu:hover{color:#fff;display:block;}
  	.jsSaveDanmu:hover{color:#444; background:}
    .emojiBtn{width:50px;height:50px; display: inline-block;cursor:pointer; margin-top: 2px;}
    .emojiBtn:hover{width:48px;height:48px; display: inline-block; border:1px solid #666; }
    .emojiBtn .emojiImg{width:45px;height:45px;  background:url("../../assets/shan/image/1f60a.png") no-repeat 0 0;background-size: contain; margin:1px 0 0 1px;}
  </style>
  <div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">新闻管理</strong> / <small>弹幕管理</small></div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <a class="am-btn am-btn-default" href="<?=Url::toRoute(['/index.php/news/edit','topicid'=>$topic['id']])?>">返回图集管理</a>
          </div>
        </div>
      </div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8 am-u-md-push-4">
        <form class="am-form am-form-horizontal">
        <?php if($index > 0):?>
         <label for="username">图片描述</label>
		  <div class="am-input-group">
    			<input type="text" class="form-control" id="imgContent" value="<?=$image['content']?>">
    			<span class="am-input-group-label" id="updateContent" style="cursor:pointer;">保存</span>
		  </div>
		  <br>
          <?php endif;?>
          <label for="username">添加弹幕</label>
		  <div class="am-input-group">
            <p class="lead emoji-picker-container">
    			<input type="email" class="form-control" data-emojiable="true" id="danmuTxt">
            </p>
    			<span class="am-input-group-label" id="pushBtn" style="cursor:pointer;">添加</span>
		  </div>
		  <br>
		  <div class="am-cf">
		  	<input type="hidden" name="" id="topicid" value="<?=$topic['id']?>" />
		  	<input type="hidden" name="" id="imgid" value="<?=$image['imageId']?>" />
		  	<input type="hidden" name="" id="iid" value="<?=$image['id']?>" />
	        <a class="jsSaveDanmu am-btn am-btn-default am-disabled" href="javascript:void(0);" id="jsSaveDanmu">保存</a>
	      </div>
	      <br>
	      <label for="username">弹幕列表</label>
	      <table class="am-table">
		    <tbody id="js-danmu-list">
		    	<?php if($image['barrage']){ ?>
		    	<?php foreach ($image['barrage'] as $item){ ?>
		        <tr id="<?=$item['id']?>">
		            <td><script>document.write(emoji.replace_unified('<?=$item['content']?>'));</script></td>
		            <td>
			            <div class="am-btn-toolbar">
		                  <div class="am-btn-group am-btn-group-xs">
		                    <a class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only js-remove-danmu" href="javascript:void(0);" data-id="<?=$item['id']?>">删除</a>
		                  </div>
		                </div>
		            </td>
		        </tr>
		    	<?php }?>
		    	<?php }?>
		    </tbody>
		</table>
		<div class="am-cf">
	        <a style="" class="am-btn am-btn-default" href="<?=Url::toRoute(['/index.php/news/danmu','topicid'=>$topic ['id'],'imgid'=>$pre])?>">上一张图 </a>
	        <a style="" class="am-btn am-btn-default" href="<?=Url::toRoute(['/index.php/news/danmu','topicid'=>$topic ['id'],'imgid'=>$next])?>">下一张图 </a>
	    </div>
	    <br>
        </form>
      </div>

      <div class="am-u-sm-12 am-u-md-4 am-u-md-pull-8">
        <div class="am-panel am-panel-default" style="height:500px;width:300px;position:relative;" id="danmuBox">
        	<span class="imgBox" style="position:relative; display:block;" >
				<img src="<?=$image['imageUrl']?>" />
				<!--<div class="danmu" style=" display:none;" data-x="" data-y=""><p></p></div>-->
			</span>
        </div>
      </div>
    </div>
  </div>
  <!-- content end -->
  <script>
  	$(document).ready(function(){
		drag($('#danmuBox .danmu'),$('#danmuBox .imgBox'));
  	});
  	
	//拖拽的封装函数
  	function drag(oBox,obj){
    	var disX=0;
    	var disY=0;
    	oBox.mousedown(function(ev){
    		disX=ev.clientX-oBox.position().left;
    		disY=ev.clientY-oBox.position().top;
    		$(document).mousemove(function(ev){
    			var l=ev.clientX-disX;
    			var t=ev.clientY-disY;
    			if(l<0){
    				l=0;
    			}else if(l>$(obj).width()-oBox.width()){
    				l=$(obj).width()-oBox.width();
    			}
    			if(t<0){
    				t=0;
    			}else if(t>$(obj).height()-oBox.height()){
    				t=$(obj).height()-oBox.height();
    			}

    			$("<img/>").attr("src",$(obj).children('img').attr("src")).load(function() {
						var oSpan=$(obj);
						var oSpanX=oSpan.width();
						var oSpanY=oSpan.height();
						var oldX = this.width;
						var oldY = this.height;
						var disX =((oSpanX-oldX)/oldX)+1;
						var disY =((oSpanY-oldY)/oldY)+1;
						var objY=t/oldY/disY;
						var objx=l/oldX/disX;

						$(oBox).attr("data-y",objY);
						$(oBox).attr("data-x",objx);
						/*
						alert(oSpanX);
						alert(oSpanY);
						alert(oldX);
						alert(oldY);
						alert(disX);
						alert(disY);
						*/
				});
    			oBox.css({left:l+"px",top:t+"px"});
    		});
    		$(document).mouseup(function(){
    			$(document).unbind('mousemove');
    			$(document).unbind('mouseup');
    			oBox.get(0).releaseCapture&&oBox.get(0).releaseCapture();
    		});
    		oBox.get(0).setCapture&&oBox.get(0).setCapture();
    		return false;
    	});
    }
  </script>
<script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/news.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/nanoscroller.min.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/tether.min.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/config.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/util.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/jquery.emojiarea.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/emoji-picker.js"></script>
<script>
  $(function() {
    // Initializes and creates emoji set from sprite sheet
    window.emojiPicker = new EmojiPicker({
      emojiable_selector: '[data-emojiable=true]',
      assetsPath: '<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/img/',
      popupButtonClasses: 'fa fa-smile-o'
    });
    // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
    // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
    // It can be called as many times as necessary; previously converted input fields will not be converted again
    window.emojiPicker.discover();
  });
</script>
