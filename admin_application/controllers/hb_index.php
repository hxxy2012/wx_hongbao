<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//红包控制器
class hb_index extends MY_Controller {
	var $data;
	function hb_index(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_hb_hongbao_set','hbset');
		$this->load->model('M_hb_hongbao_list','hblist');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//红包列表页
	function index(){
		// $item = $this->randBonus(100,10,1);
		$get = $this->input->get();
		$pageindex= $this->input->get_post("per_page");
		if ($pageindex <= 0) {
			$pageindex = 1;
		}
		$pagesize = 15;
		$search = array();
		$search_val = array();
		$search_val["title"] = "";
		$orderby["id"] = "desc";
		if(!empty($get["search_title"])){
			$search["title"] =  trim($get["search_title"]);
			$search_val["title"] = $get["search_title"];
		}		
		$list = $this->hbset->GetInfoList($pageindex,$pagesize,$search,$orderby);
		$this->data["list"] = $list["list"];		
		$this->data["pager"] = $list["pager"];	
		$this->data["search_val"] = $search_val;	
		$this->data["isjichu"] = count($list["list"])==0;		
		// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
		$this->load->view(__TEMPLET_FOLDER__."/hb/index/list",$this->data);		
	}
	
	//编辑红包页
	function edit(){		
		$post = $this->input->post();		
		if(is_array($post)){
			$ls = empty($post["backurl"])?site_url("hb_index/index"):$post["backurl"];//site_url("hb_index/edit")."?id=".$id;//
			if ($post['curr']==1&&$this->checkhasingnoajax($post["id"])) {//如果添加的是正在进行的红包活动则判断
				echo "<script>
				parent.tip_show('已经存在正在进行的红包活动，请先停止该红包活动再添加',0,1000);
				var url = \"$ls\";
				parent.flushpage(url);					
				setTimeout(\"top.topManager.closePage();\",1000);
				</script>";			
				die();
			}
			$id = $post["id"];	
			$oldmodel = $this->hbset->GetModel($id);//之前的数据
			if ($oldmodel['curr'] == '1') {//如果是正在进行的红包活动则只能修改标题跟结束活动
				$model['id'] = $id;
				$model["title"] = trim($post["title"]);//主题
				$model['curr']  = $post['curr'];
			} else {//更新所有字段以及重新计算随机红包
				$model['id'] = $id;
				$model["title"] = trim($post["title"]);//主题
				$model['curr']  = $post['curr'];//是否正在进行
				$model["suiji"] = $post["suiji"];//随机还固定
				$model["hongbao_shu"] = $post["hongbao_shu"];//红包数
				$model["jine"]  = $post["jine"];//总金额
				$model["qibu_jine"]   = $post["qibu_jine"];//最低红包数量	
				/*if ($oldmodel['suiji']!=$model['suiji']||$oldmodel['hongbao_shu']!=$model['hongbao_shu']
					||$oldmodel['jine']!=$model['jine']||$oldmodel['qibu_jine']!=$model['qibu_jine']) {
					//如果有其中一个的修改了就进行重新计算红包金额插入到hb_prize表中
					$temp = 1;
				}*/
				//删除之前的红包金额，以及通过算法计算出每个红包金额插入到hb_prize表中
				$this->M_common->del_data("delete from hb_hongbao_list where set_id='$id'");
				$type = $model["suiji"]=='0'?1:2;//红包算法类型1代表随机其他代表平均
				$qibu_jine = $model['qibu_jine'];
				if (ceil($model['qibu_jine'])==floor($model['qibu_jine'])) {//如果最小金额跟其整数相等则取其第一个2位小数
					$qibu_jine += 0.01;
				}
				$item = $this->sendRandBonus($model["jine"],$model["hongbao_shu"],$type,$qibu_jine);
				while (in_array('0', $item)||in_array('0.00', $item)) {//直到不出现0为止
					$item = $this->sendRandBonus($model["jine"],$model["hongbao_shu"],$type,$qibu_jine);
				}
				foreach ($item as $key => $value) {//添加红包金额到中奖记录表中
					$tpmodel = array('jine' => $value, 'set_id' => $id);
					$tpnewid = $this->hblist->add($tpmodel);
				}
			}
				
			$this->hbset->update($model);
			//showmessage("修改成功",$ls,1,1);
			echo "<script>
			parent.tip_show('修改成功',1,1000);
			var url = \"$ls\";
			parent.flushpage(url);					
			setTimeout(\"top.topManager.closePage();\",1000);
			</script>";			
			die();	
		}
		else{
			$get = $this->input->get();
			$id = empty($get["id"])?0:$get["id"];
			if(!is_numeric($id)){
				$id = 0;	
			}
			if($id==0){
				showmessage("无数据","hb_index/index",3,0);			
			}
			$model = $this->hbset->GetModel($id);
			$this->data['model'] = $model;
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_index/index");	
			}
			$this->data["id"] = $id;
			$this->load->view(__TEMPLET_FOLDER__."/hb/index/edit",$this->data);			
		}
	}
	//添加红包页
	function add(){	
		$post = $this->input->post();
		if(is_array($post)){
			$ls = empty($post["ls"])?site_url("hb_index/index"):$post["ls"];
			if ($post['curr']==1&&$this->checkhasingnoajax()) {//如果添加的是正在进行的红包活动则判断
				showmessage("已经存在正在进行的红包活动，请先停止该红包活动再添加",$ls,3,0);exit;
			}
			$model["title"] = trim($post["title"]);//红包活动名称
			$model['curr']  = $post['curr'];//正在进行
			$model["suiji"] = $post["suiji"];//随机固定
			$model["hongbao_shu"] = $post["hongbao_shu"];//红包总数
			$model["jine"]  = $post["jine"];//总金额
			$model["qibu_jine"]   = $post["qibu_jine"];//最低红包金额
			$newid = $this->hbset->add($model);
			//通过算法计算出每个红包金额插入到hb_prize表中
			$type = $model["suiji"]=='0'?1:2;//红包算法类型1代表随机其他代表平均
			$qibu_jine = $model['qibu_jine'];
			if (ceil($model['qibu_jine'])==floor($model['qibu_jine'])) {//如果最小金额跟其整数相等则取其第一个2位小数
				$qibu_jine += 0.01;
			}
			$item = $this->sendRandBonus($model["jine"],$model["hongbao_shu"],$type,$qibu_jine);
			while (in_array('0', $item)||in_array('0.00', $item)) {//直到不出现0为止
				$item = $this->sendRandBonus($model["jine"],$model["hongbao_shu"],$type,$qibu_jine);
			}
			foreach ($item as $key => $value) {//添加红包金额到中奖记录表中
				$tpmodel = array('jine' => $value, 'set_id' => $newid);
				$tpnewid = $this->hblist->add($tpmodel);
			}
			showmessage("新增成功",$ls,3,1);
			die();
		}
		else{
			//用户类型
			// $usertype = $this->cd->GetModelList_orderby('9',0);
			if($this->data["ls"]==""){
				$this->data["ls"]=site_url("hb_index/index");	
			}
			$this->load->view(__TEMPLET_FOLDER__."/hb/index/add",$this->data);		
		}
	}
	
	//删除红包
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				//删除红包活动
				$this->M_common->del_data("delete from hb_hongbao_set where id='$v'");
				//删除中奖记录
				$this->M_common->del_data("delete from hb_hongbao_list where set_id='$v'");
			}
		}
		//echo "ok";
	}
	

	//ajax查询是否存在正在进行的红包,
	function checkhasing() {
		$result = array('code' => '-1', 'info' => '服务器错误，请刷新重试');
		$get = $this->input->get();
		$where  = " curr='1' ";
		if (isset($get['id'])&&$get['id']) {
			$where .= " and id!='{$get['id']}' ";
		}
		$hbInfo = $this->hbset->getlist($where);
		if (count($hbInfo)<=0) {
			$result['code'] = 0;
			$result['info'] = '无正在进行的红包活动';
		} else {
			$result['code'] = 1;
			$result['info'] = $hbInfo[0]['title'].'红包活动正在进行，所有轮红包活动中只能存在一轮正在进行，请先停止该轮红包活动';
		}
		echo json_encode($result);
	}
	//判断是否存在正在进行的红包活动，是返回true，否则false
	private function checkhasingnoajax($id='') {
		$where  = " curr='1' ";
		if ($id) {
			$where .= " and id!='$id' ";
		}
		$hbInfo = $this->hbset->getlist($where);
		if (count($hbInfo)<=0) {
			return false;
		} else {
			return true;
		}
	}

	//获取随机的红包金额数组，$total为红包总金额，$count为红包个数,$type类型1代表随机2代表平均数,$min代表最小金额
	private function sendRandBonus($total=0, $count=3, $type=1, $min=1.01){
	  if($type==1){
	  	$input  = range(1,$total,$min);// range(0.01, $total, 0.01);//第三个参数为步长	
	    if($count>1){
	      $rand_keys = (array) array_rand($input, $count-1);
	      $last = 0;
	      foreach($rand_keys as $i=>$key){
	        $current  = $input[$key]-$last;
	        $items[]  = number_format($current, 2);
	        $last     = $input[$key];
	      }
	    }
	    $items[] = number_format($total-array_sum($items),2);
	  }else{
	    $avg = number_format($total/$count, 2);
	    $i   = 0;
	    while($i<$count){
	      $items[]  = $i<$count-1?$avg:($total-array_sum($items));
	      $i++;
	    }
	  }
	  return $items;
	}

	// $bonus_total 红包总金额
	// $bonus_count 红包个数
	// $bonus_type 红包类型 1=拼手气红包 0=普通红包
	private function randBonus($bonus_total=0, $bonus_count=3, $bonus_type=1){
	  $bonus_items  = array(); // 将要瓜分的结果
	  $bonus_balance = $bonus_total; // 每次分完之后的余额
	  $bonus_avg   = number_format($bonus_total/$bonus_count, 2); // 平均每个红包多少钱
	  $i       = 0;
	  while($i<$bonus_count){
	    if($i<$bonus_count-1){
	      $rand      = number_format($bonus_type?(rand(1, 2)/100*$bonus_balance):$bonus_avg,2); // 根据红包类型计算当前红包的金额
	      $bonus_items[] = $rand;
	      $bonus_balance -= $rand;
	    }else{
	      $bonus_items[] = $bonus_balance; // 最后一个红包直接承包最后所有的金额，保证发出的总金额正确
	    }
	    $i++;
	  }
	  return $bonus_items;
	}


}