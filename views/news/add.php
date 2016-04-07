<?php use yii\helpers\Url; ?>
<?php $this->params['useBS'] = true;?>
<style>
*{ margin:0; padding:0;}
.fileBoxUl{ margin:0; padding:0;}
#box{ margin:0 auto; width:75%;float:left;}
#demo{ margin:50px auto; width:540px; min-height:800px; background:#CF9}
li{ list-style: none;}

.parentFileBox{max-width:750px;}
.jcrop-holder{ margin-left:0px; background-color:#fff;}
.am-btn{margin-top:18px;}
.amShow{ padding-left:26.6%;}
.emojiBtn{width:50px;height:50px; display: inline-block;cursor:pointer; margin-top: 2px; position: relative; }
.emojiBtn:hover{width:48px;height:48px; display: inline-block; border:1px solid #666; }
.emojiBtn .emojiImg{width:45px;height:45px;display: inline-block; background:url("../../assets/shan/image/1f60a.png") no-repeat 0 0;background-size: contain; margin:1px 0 0 1px;}
.emojiBtn .emojiImgBox{width:500px;height:200px; position: absolute;top:-1px;right:-500px; background: #fff;border:1px solid #ccc; z-index: 888; display: none;}
@media screen and (max-width:414px){

.jcrop-holder{ margin-left:0px; background-color:#fff; float:right;}
.jcrop-tracker{ width:414px; margin:0 auto;}
.am-btn{margin-left:0px; margin-top:18px;}
}
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">新闻管理</strong> / <small>新增</small></div>
    </div>

    <hr/>

    <div class="am-g">

      <div class="am-u-sm-12 am-u-md-8">
        <form class="am-form am-form-horizontal" id="js-add-form" method="POST">
          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">新闻标题</label>
            <div class="am-u-sm-9">
            <p class="lead emoji-picker-container">
              <input type="email" class="form-control" id="title" name="title" data-emojiable="true" placeholder="">
            </p>
             </div>
          </div>
          
          <!-- <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">文字描述</label>
            <div class="am-u-sm-9">
              <textarea rows="" cols="" id="content" name="content" placeholder=""></textarea>
            </div>
          </div> -->
          
          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">新闻来源</label>
            <div class="am-u-sm-9">
              <input type="text" id="sourceName" name="sourceName" placeholder="">
            </div>
          </div>

          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">来源地址</label>
            <div class="am-u-sm-9">
              <input type="text" id="source" name="source" placeholder="">
            </div>
          </div>
          
          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">内容分级</label>
            <div class="am-u-sm-9">
              <input type="radio" name="content_level" class="content_level" value="0" checked>正常
              <input type="radio" name="content_level" class="content_level" value="1">少儿不宜
            </div>
          </div>
          
          <!-- <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">上传封面</label>
            <div class="am-u-sm-9">
              <input type="file" name="cover" id="cover" placeholder="" value="" class="am-input-sm">
              <input type="hidden" id="cover_img" name="cover_img" value=""/>
            </div>
          </div> -->
          
          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">上传封面</label>
            <div class="am-u-sm-9" id="box" >
            	<input type="hidden" id="cover_img" name="cover_img" value=""/>
            	<input type="hidden" id="x" name="x" value="" />
		        <input type="hidden" id="y" name="y" value="" />
		        <input type="hidden" id="w" name="w" value="" />
		        <input type="hidden" id="h" name="h" value="" />
				<div id="coverrrr" ></div>
			</div>
          </div>
          
          <div class="am-form-group">
            <div class="am-u-sm-9 amShow">
				<img id="element_id" src="<?= $this->params['cdnUrl'] ?>assets/shan/image/canvas.png" title="请上传封面" alt="请上传封面">
				<!-- <a class="am-btn am-btn-default" href="javascript:void(0);" id="js-save-crop-cover" style="">保存截图</a> -->
			</div>
          </div>
          
          <!-- <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">上传图集</label>
            <div class="am-u-sm-9">
              <input type="file" name="images[]" id="images[]" multiple="true" class="am-input-sm">
            </div>
          </div> -->
          
          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">上传图集</label>
            <div class="am-u-sm-9" id="box">
            	<input type="hidden" id="images" name="images" value=""/>
				<div id="imagesss"></div>
			</div>
          </div>

          <div class="am-form-group">
            <label for="" class="am-u-sm-3 am-form-label">发布策略</label>
            <div class="am-u-sm-9">
              <input type="radio" name="publish-time-radio" class="publish-time-radio"  value="now" checked>立即发布
            </div>
            <div class="am-u-sm-9">
              <input type="radio" name="publish-time-radio" class="publish-time-radio" value="timer" >定时发布
              <input size="16" type="text" value="" placeholder="时间" name="publish-time" id="publish-time" readonly class="form-datetime am-form-field">
            </div>
          </div>

          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
              <button type="button" id="nextStep" class="am-btn am-btn-primary">下一步</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script>
  $(function() {
	var jcrop_api;
    $('.form-datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii'});

    // 裁剪区域框
    $('#element_id').Jcrop({
      	boxWidth:600,
      	aspectRatio:4/3,
    		onSelect: updateCoords
	   },function(){
    	jcrop_api = this;
    });
    //点击出现enoji表情
    $("#emojiBtn").click(function(){
      $(".emojiImgBox").toggle();
    });
    // 上传封面
    $('#coverrrr').diyUpload({
    	url:'<?=Url::toRoute("/index.php/news/uploadcover")?>',
    	success:function( data ) {
    		//console.info( data );
    		//$("#element_id").attr("src",data.data);
    		//$('#element_id').show();
    		jcrop_api.setImage(data.data.imgSrc);
    		$("#cover_img").val(data.data.imgName);
    		jcrop_api.animateTo([0, 0, 500, 375]);
    		$('#x').val("0");
    		$('#y').val("0");
    		$('#w').val("500");
    		$('#h').val("375");
    	},
    	error:function( err ) {
    		console.info( err );	
    	},
    	fileNumLimit:1
    });
    
    // 上传图集
    var images = $("#images").val();
    $('#imagesss').diyUpload({
    	url:'<?=Url::toRoute("/index.php/news/uploadimgs")?>',
    	success:function( data ) {
            var sort = 0;
            for(var i = 0; i < $(".diyFileName").length; i++){
                if(data.data.name == $(".diyFileName").eq(i).html()){
                    sort = i;
                }
            }
        	images = $("#images").val();
        	images = images + sort + "|" + data.data.imgKey + ";";
        	$("#images").val(images);
    	},
    	error:function( err ) {
    		console.info( err );	
    	}
    });
    
    // 保存裁剪的图片
    $("#js-save-crop-cover").on("click",function(){
      	var x = $('#x').val();
      	var y = $('#y').val();
      	var w = $('#w').val();
      	var h = $('#h').val();
      	var imgName = $("#cover_img").val();
      	$.ajax({
  			async : false,
  			type : "POST",
  			url : $CONFIG['home'] + $CONFIG['script'] + '/news/crop',
  			data : {
  				"x" : x,
  				"y" : y,
  				"w" : w,
  				"h" : h,
  				"src" : imgName
  			},
  			success : function(data) {
    				data = $.parseJSON(data);
      				if (data.status == 100000) {
      					$("#cover_img").val(data.data);
      					alert(data.info);
      					// $("tr#" + commentid).remove();
      					// window.location.reload(true);
      				} else {
      					alert(data.info);
      				}
    			 }
  		  });
    });

    function updateCoords(c){
  		$('#x').val(c.x);
  		$('#y').val(c.y);
  		$('#w').val(c.w);
  		$('#h').val(c.h);
  	};
	   
  });
</script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/nanoscroller.min.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/tether.min.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/config.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/util.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/jquery.emojiarea.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/emoji-picker/lib/js/emoji-picker.js"></script>

<script src="<?= $this->params['cdnUrl'] ?>assets/datetimepicker/js/amazeui.datetimepicker.min.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/datetimepicker/js/locales/amazeui.datetimepicker.zh-CN.js"></script>
<script src="<?= $this->params['cdnUrl'] ?>assets/shan/js/news.js"></script>

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
