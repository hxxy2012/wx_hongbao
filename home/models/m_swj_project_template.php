<?php
class M_swj_project_template extends M_common {
	private $table = "swj_project_template";
	private $tablename = "项目模板";
	function M_swj_project_template(){
		parent::__construct();	
		
	}


	
	function count($where){
		return $this->M_common->query_count("select count(*) as dd from ".$this->table." where $where");				
	}
	

	
	
	
	function GetModel($id){
		$sql = "select * from ".$this->table." where id=$id";
		return  $this->M_common->query_one($sql);
	}
	
	//通过模板guid获取获取模板信息
	function GetModelFromGuid($guid){
		$sql = "select * from ".$this->table." where guid='$guid'";
		return  $this->M_common->query_one($sql);
	}	
	
	//通过项目id跟用户usertype获取其提供的模板
	function GetModelFromProjectAndUtype($projectid, $usertype){
		$sql = "select spt.*,spt1.title parenttitle from swj_project_template_link sptl 
				left join swj_project_template spt on spt.guid=sptl.template_guid 
				left join swj_project_template spt1 on spt.parent_guid=spt1.guid 
				where sptl.project_id='$projectid' and sptl.usertype like '%$usertype%' 
				and spt.isdel='0' and spt.isshow='1' order by spt.orderby asc";
		return $this->M_common->querylist($sql);//模板guid
		//通过模板guid获取模板信息
	}	
	
	//通过模板guid获取对应的模板页面,$type为0获取模板页面其他为获取对应的附表名字
	function getTplByGuid($tplguid, $type=0) {
		$template = '';
		$table = '';
		switch ($tplguid) {
			case '7A48A574-223F-44F4-A06D-47E86D422C09'://电子商务园区
				$template = 'dzswyq';
				$table = 'swj_project_shenbao_yuanqu';
				break;
			case '28C5877E-AD9A-4F9A-A806-7B00763A0099'://电子商务平台
				$template = 'dzswpt';
				$table = 'swj_project_shenbao_jiaoyi_pingtai';
				break;
			case 'B048AAE0-536B-4D58-AA6E-D40B7AFC10B0'://电子商务应用项目
				$template = 'dzswyyxm';
				$table = 'swj_project_shenbao_yingyong_xiangmu';
				break;
			case '447B3AE5-0007-4701-9969-5DC8566DC4AF'://电子商务服务项目
				$template = 'dzswfwxm';
				$table = 'swj_project_shenbao_fuwu_xiangmu';
				break;
			case '3F1BF950-4201-4574-A09C-447605BCDD20'://电子商务行业协会或机构
				$template = 'dzswhyxhhjg';
				$table = 'swj_project_shenbao_xiehui_jigou';
				break;
			case 'F97C5BE0-C642-4C85-9481-D19FAC4132CD'://电子商务交易平台
				$template = 'dzswjypt';
				$table = 'swj_project_shenbao_jiaoyi_pingtai';
				break;
			case 'BA038762-97D3-4EF7-8A17-B5BE0CB33252'://电子商务综合服务平台
				$template = 'dzswzhfwpt';
				$table = 'swj_project_shenbao_zonghe_fuwu';
				break;
			default://默认为电子商务园区
				$template = 'dzswyq';
				break;
		}
		if ($type == 0) {
			return $template;
		} else {
			return $table;
		}
		
	}
}