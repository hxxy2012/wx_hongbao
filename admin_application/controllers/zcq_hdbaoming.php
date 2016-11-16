<?php 

if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//活动控制器
class zcq_hdbaoming extends MY_Controller {
	var $data;
	function zcq_hdbaoming(){
		parent::__construct();
		$this->load->model('M_common');
		$this->load->model('M_user','user');
		$this->load->model('M_zcq_hdbaoming','hdbaoming');
		$this->load->model('M_zcq_huodong','huodong');
		$this->load->model('M_common_category_data','cd');				
		$get = $this->input->get();
		$this->data["ls"] = $this->input->get_post("backurl");
		$this->data["sess"] = $this->parent_getsession();
		
	}	

	//活动报名列表页
	function index(){
		$get = $this->input->get();
		if (!empty($get['action'])&&$get['action']=='export') {//导出excel操作
			$this->export($get["search_title"]);
		} else {
			$pageindex= $this->input->get_post("per_page");
			if ($pageindex <= 0) {
				$pageindex = 1;
			}
			$pagesize = 20;
			$search = array();
			$search_val = array();
			$search_val["title"] = "";
			$orderby["id"] = "desc";
			if(!empty($get["search_title"])){
				$search["title"] =  trim($get["search_title"]);
				$search_val["title"] = $get["search_title"];
			}		
			$list_hdzt = $this->huodong->GetInfoList(0,99999,array(),$orderby);//活动主题信息
			$this->data["list_hdzt"] = $list_hdzt['list'];
			if (!empty($get['huodong_id'])) {//活动id不为空，
				$search['huodong_id'] = $get['huodong_id'];
				$search_val["huodong_id"] = $get['huodong_id'];
			} else {//活动id为空默认显示最后一个活动主题
				$length = count($this->data["list_hdzt"]);
				$search_val["huodong_id"] = '';
				if ($length > 0) {
					$search['huodong_id'] = $this->data["list_hdzt"][0]['id'];
					$search_val["huodong_id"] = $search['huodong_id'];
				}
			}
			$list = $this->hdbaoming->GetInfoList($pageindex,$pagesize,$search,$orderby);
			$this->data["list"] = $list["list"];		
			$this->data["pager"] = $list["pager"];	
			$this->data["search_val"] = $search_val;	
			$this->data["isjichu"] = count($list["list"])==0;		
			// $this->data["isadmin"] = is_super_admin();//permition_for("swj_shenbao","check_look");
			$this->load->view(__TEMPLET_FOLDER__."/zcq/huodong/list_hdbaoming",$this->data);
		}
	}
	
	//导出excel,$title搜索内容
	private function export($title) {
		$search = array();
		$orderby["id"] = "desc";
		if (!empty($title)) {
			$search['title'] = $title;
		}
		//获取活动报名信息
		$list = $this->hdbaoming->GetInfoList(0,99999,$search,$orderby);
		require_once 'include/PHPExcel.php';
		require_once 'include/PHPExcel/Writer/Excel2007.php';
		$objPHPExcel = new PHPExcel();
		
		$i=1;	
		//设置表格宽度
		for ($j='B';$j<='F';$j++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($j)->setWidth(28);     
		}	
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "编号");
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, "活动主题");
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, "公司名称");
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, "联系人");
		$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, "手机");
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, "报名时间");
		
		foreach($list["list"] as $k=>$v){
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $this->convertUTF8($v['id']),PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $this->convertUTF8($v['title']));
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $this->convertUTF8($v['company']));
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $this->convertUTF8($v['realname']));
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $i, $this->convertUTF8($v['tel']),PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $this->convertUTF8(date('Y-m-d H:i:s', $v['createtime'])));
		}
		 
		
		// $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//或者$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel); 非2007格式			
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="活动报名表汇总'.date("Y-m-d").'.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	}

	//删除活动报名
	function del(){
		$get = $this->input->get();
		$ids = !empty($get["idlist"])?$get["idlist"]:"";
		$arr = explode(",",$ids);
		//file_put_contents("e:aa.txt","gggg=".print_r($arr,true));
		if($ids!=""){
			foreach($arr as $v){
				$this->hdbaoming->del($v);
			}
		}
		echo "ok";
	}

	private function convertUTF8($str)
	{
	   if(empty($str)) return '';
	   //return  iconv('gb2312','utf-8', $str);
	   return $str;
	}
}