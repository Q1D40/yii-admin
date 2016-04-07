<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "tvdanmu");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	//echo $echoStr;
        	//exit;
            $this->responseMsg();
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";             
				if(!empty( $keyword ))
                {
                    $dbconn = mysql_connect('127.0.0.1', 'root', '12345');
                    mysql_select_db('tv_danmu', $dbconn);
                    mysql_query("set names 'UTF8'");
                    if(substr($keyword, 0, 11) == ':set title '){
                        $sql = "UPDATE `setting` SET `title` = '" . str_replace(':set title ', '', $keyword) . "' WHERE `id` = '1' LIMIT 1";
                        $respStr = '标题设置成功';
                    }else{
                        $sql = "INSERT INTO `danmu` (`words`) VALUES ('$keyword')";
                    }
                    mysql_query($sql);
                    
                    if($respStr != ''){
                        $msgType = "text";
                        $contentStr = $respStr;
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                        echo $resultStr;
                    }
                }else{
                	echo "正在输入...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
