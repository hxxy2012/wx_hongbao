<?php
if (! defined('BASEPATH')) {
	exit('Access Denied');
}
ini_set( "display_errors" ,"On");
class cm extends MY_Controller{	
	function cm(){
		parent::__construct();		
		$this->load->library('weixin');
		$this->load->model('M_user','user');	
			
	}

	function index(){		
		if( $_SERVER['REQUEST_METHOD']=="POST"){
			$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
			if (!empty($postStr)){	
				$postObj = simplexml_load_string($postStr,
							'SimpleXMLElement',
							LIBXML_NOCDATA);
				if(trim($postObj->Event)=="subscribe"){
					//关注事件
					$this->guanzhu($postObj);
				}
				if(trim($postObj->Event)=="unsubscribe"){
					//取消关注事件
					$this->guanzhu_cancel($postObj);
				}
			}
		}
	}

	//关注
	function guanzhu($model){
		//发送欢迎语
		/*
		 *<img src='http://".$_SERVER['HTTP_HOST']."/home/views/zx99_wx/images/wx_about.jpg'>
		 *<img src='http://".$_SERVER['HTTP_HOST']."/home/views/zx99_wx/images/wx_website.jpg'>
		 *<img src='http://".$_SERVER['HTTP_HOST']."/home/views/zx99_wx/images/wx_shop.jpg'>
		 *<img src='http://".$_SERVER['HTTP_HOST']."/home/views/zx99_wx/images/wx_mingpian.jpg'>
		 *<img src='http://".$_SERVER['HTTP_HOST']."/home/views/zx99_wx/images/wx_news.jpg'>
		*/
				
				
		$arr[0]["title"] = "马上进入摇一摇红包";
		$arr[0]["picurl"] = $this->config->item("home_img")==""?("http://".$_SERVER['HTTP_HOST']."/home/views/static/hb/images/hb.jpg"):($this->config->item("home_img")."/hb.jpg");
		$arr[0]["url"] = "http://mp.weixin.qq.com/s?__biz=MjM5NDM1NjUzNg==&mid=2652798408&idx=1&sn=862e9d17ee356519f21536fb98601862&chksm=bd63d19f8a14588989491dbf1e74791ddf6560dc71e2a62a43e1bd04ae523812af76c2746a2a#rd";


		$xml = $this->weixin->tuwen(
		$arr,
		$model->FromUserName,
		$model->ToUserName);
		
		echo $xml;
		//file_put_contents("f:aa.txt",$xml);
		
	}
	//取消关注事件
	function guanzhu_cancel($model){
		//清空字段
        /*
		$list = $this->user->GetUserList("openid='".$model->FromUserName."'");
		//file_put_contents("f:aa.txt","openid='".$model->FromUserName."'");
		if(count($list)>0){
			$list[0]["openid"]="";
			$this->user->update($list[0]);
		}
        */
	}
	function cmenu(){
		echo $this->weixin->createmenu();
	}	
	
	function wxvail(){
		$this->index();
		$this->weixin->index();
	}
	//发送文 本信息
	function SendTxtToUser($userid,$msg)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->weixin->getacc();		
		$mb = '{
				"touser":"%s",
				"msgtype":"text",
				"text":
				{
					 "content":"%s"
				}
		}
		';
		$msg = sprintf($mb,$userid,$msg);		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
		$aaa= curl_exec($ch);
		
		//file_put_contents("f:aa.txt",$aaa);
		curl_close($ch);
		
	}


	
	
	
	
		
}
?>