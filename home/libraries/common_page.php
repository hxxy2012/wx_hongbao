<?php
/*
 *公共的分页方法
 *author 王建 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

class Common_page{
	var $ci  ;
	function __construct() {
		
	}
	/*
	@params $total 总数
	@params $page_num 每一页显示的条数
	@params $page 当前第几页
	@params $ajax_function ajax方法名字
	*/
 	function page_string($total,$page_num,$page,$ajax_function = 'ajax_data',$uid=0){
		$CI =& get_instance();
		$page_string = '' ;
		$CI->load->library('pagination');//加载分页类
		$CI->load->library('MY_Pagination');//加载分页类
		$config['total_rows'] = $total;
		$config['use_page_numbers'] =true; // 当前页结束样式
		$config['per_page'] = $page_num; // 每页显示数量，为了能有更好的显示效果，我将该数值设置得较小
		$config['full_tag_open'] = '<ul class="pagination">'; // 分页开始样式
		$config['full_tag_close'] = '</ul>'; // 分页结束样式
		$config['first_link'] = '首页'; // 第一页显示
		$config['last_link'] = '末页'; // 最后一页显示
		$config['next_link'] = '下一页 >'; // 下一页显示
		$config['prev_link'] = '< 上一页'; // 上一页显示
		$config['cur_tag_open'] = ' <li><a class="disabled " style="color:red;">'; // 当前页开始样式
		$config['cur_tag_close'] = '</a></li>'; // 当前页结束样式
		$config['uri_segment'] = 6;
		$config['anchor_class']='class="ajax_page" ';
		$CI->pagination->cur_page = $page ;
		$CI->pagination->initialize($config); // 配置分页
		$page_string =  $CI->pagination->create_links(true,$ajax_function,$uid);
		return $page_string ;
	} 	
	
	function page_string2($total,$page_num,$page){
		$CI2 =& get_instance();
		$page_string = '' ;
		$CI2->load->library('pagination');//加载分页类
		
		$CI2->load->library('MY_Pagination');//加载分页类
		
		$config['total_rows'] = $total;
		$config['use_page_numbers'] =true; // 当前页结束样式
		$config['per_page'] = $page_num; // 每页显示数量，为了能有更好的显示效果，我将该数值设置得较小
		$config['full_tag_open'] = '<ul class="pagination">'; // 分页开始样式
		$config['full_tag_close'] = '</ul>'; // 分页结束样式
		$config['first_link'] = '首页'; // 第一页显示
		$config['last_link'] = '末页'; // 最后一页显示
		$config['next_link'] = '下一页 >'; // 下一页显示
		$config['prev_link'] = '< 上一页'; // 上一页显示
		$config['cur_tag_open'] = ' <li><a class="disabled " style="color:red;">'; // 当前页开始样式
		$config['cur_tag_close'] = '</a></li>'; // 当前页结束样式
		$config['uri_segment'] = 6;
		$config['anchor_class']=' ';
		$config['page_query_string'] = TRUE;
		$config['num_tag_open'] = "";
		$config['num_tag_close'] = "";
		//$config['prefix'] = "aab";//前辍
		//$config['suffix'] = 'ccd';//后辍
		$config['query_string_segment'] = 'per_page';
		$parm = "";
		foreach($_GET as $k=>$v){
			if($k!=$config['query_string_segment']){
                if (is_array($v)) {
                    //处理数组,解决多选查询问题
                    foreach ($v as $item){
                        $parm.= "&{$k}[]={$item}";
                    }
                }else{
                    $parm.= "&$k=$v";
                }
			}
		}
		$config['base_url'] =current_url()."?".$parm;
		
		$CI2->pagination->cur_page = $page ;
	//print_r($config);
		$CI2->pagination->initialize($config); // 配置分页
		
		$page_string =  $CI2->pagination->create_links2(true);
		return $page_string ;
	}	
	//新分页去掉li和总共数据显示
	function page_string3($total,$page_num,$page,$select=2){
		$CI2 =& get_instance();
		$page_string = '' ;
		$CI2->load->library('pagination');//加载分页类
		
		$CI2->load->library('MY_Pagination');//加载分页类
		
		$config['total_rows'] = $total;
		$config['use_page_numbers'] =true; // 当前页结束样式
		$config['per_page'] = $page_num; // 每页显示数量，为了能有更好的显示效果，我将该数值设置得较小
		$config['full_tag_open'] = ''; // 分页开始样式
		$config['full_tag_close'] = ''; // 分页结束样式
		$config['first_link'] = '<<'; // 第一页显示
		$config['last_link'] = '>>'; // 最后一页显示
		$config['next_link'] = '>'; // 下一页显示
		$config['prev_link'] = '<'; // 上一页显示
		$config['cur_tag_open'] = ' <a class="on" href="#">'; // 当前页开始样式
		$config['cur_tag_close'] = '</a>'; // 当前页结束样式
		$config['uri_segment'] = 6;
		$config['anchor_class']=' ';
		$config['page_query_string'] = TRUE;
		$config['num_tag_open'] = "";
		$config['num_tag_close'] = "";
		//$config['prefix'] = "aab";//前辍
		//$config['suffix'] = 'ccd';//后辍
		$config['query_string_segment'] = 'per_page';
		$parm = "";
		foreach($_GET as $k=>$v){
			if($k!=$config['query_string_segment']){
				$parm.= "&$k=$v";
			}		
		}
		$config['base_url'] =current_url()."?".$parm;
		
		$CI2->pagination->cur_page = $page ;
	//print_r($config);
		$CI2->pagination->initialize($config); // 配置分页
		//根据不同的选择选择不同的样式
		switch ($select) {
			/*case 1:
				$page_string =  $CI2->pagination->create_links1(true);
				break;*/
			case 2:
				$page_string =  $CI2->pagination->create_links2(true);
				break;
			case 3://去掉li  和总数据
				$page_string =  $CI2->pagination->create_links3(true);
				break;
			default:
				$page_string =  $CI2->pagination->create_links2(true);
				break;
		}
		
		return $page_string ;
	}	
	//个人首页专用
	function page_string_ren($total,$page_num,$page,$base_url=''){
		
		$CI2 =& get_instance();
		$page_string = '' ;
		$CI2->load->library('pagination');//加载分页类
	
		$CI2->load->library('MY_Pagination');//加载分页类
	
		$config['total_rows'] = $total;
		$config['use_page_numbers'] =true; // 当前页结束样式
		$config['per_page'] = $page_num; // 每页显示数量，为了能有更好的显示效果，我将该数值设置得较小
		$config['full_tag_open'] = ''; // 分页开始样式
		$config['full_tag_close'] = ''; // 分页结束样式
		$config['first_link'] = '首页'; // 第一页显示
		$config['last_link'] = '末页'; // 最后一页显示
		$config['next_link'] = '下一页 >'; // 下一页显示
		$config['prev_link'] = '< 上一页'; // 上一页显示
		$config['cur_tag_open'] = '<a class="current">'; // 当前页开始样式
		$config['cur_tag_close'] = '</a>'; // 当前页结束样式
		$config['uri_segment'] = 6;
		$config['anchor_class']='class="ajax_page" ';
		$config['page_query_string'] = TRUE;
		//$config['prefix'] = "aab";//前辍
		//$config['suffix'] = 'ccd';//后辍
		$config['query_string_segment'] = 'per_page';
		$parm = "";
		foreach($_GET as $k=>$v){
			if($k!=$config['query_string_segment']){
				$parm.= "&$k=$v";
			}
		}
		if($base_url==""){
			$config['base_url'] = current_url()."?".$parm;
		}
		else{
			$config['base_url'] = $base_url;
			/*
			if(substr($base_url,strlen($base_url)-1,1)=="?"){
				//"/info/cid/0?";//.$parm;//current_url()."?".$parm;
			}
			else{
				$config['base_url'] = $base_url."?".$parm;
			}
			*/
		}
	
		$CI2->pagination->cur_page = $page ;
		//print_r($config);
		$CI2->pagination->initialize($config); // 配置分页		
		$page_string =  $CI2->pagination->create_links_ren(true);
		return $page_string ;
	}
	
	//微信列表页专用分页
	function page_string_wx($total,$page_num,$page,$base_url=''){
		
		$CI2 =& get_instance();
		$page_string = '' ;
		$CI2->load->library('pagination');//加载分页类
	
		$CI2->load->library('MY_Pagination');//加载分页类
	
		$config['total_rows'] = $total;
		$config['use_page_numbers'] =true; // 当前页结束样式
		$config['per_page'] = $page_num; // 每页显示数量，为了能有更好的显示效果，我将该数值设置得较小
		$config['full_tag_open'] = ''; // 分页开始样式
		$config['full_tag_close'] = ''; // 分页结束样式
		$config['first_link'] = false; // 第一页显示
		$config['last_link'] = false; // 最后一页显示
		$config['next_link'] = '下一页'; // 下一页显示
		$config['prev_link'] = '上一页'; // 上一页显示
		$config['cur_tag_open'] = '<a class="current">'; // 当前页开始样式
		$config['cur_tag_close'] = '</a>'; // 当前页结束样式
		$config['uri_segment'] = 6;
		$config['anchor_class']='class="ajax_page" ';
		$config['page_query_string'] = TRUE;
		//$config['prefix'] = "aab";//前辍
		//$config['suffix'] = 'ccd';//后辍
		$config['query_string_segment'] = 'per_page';
		$parm = "";
		foreach($_GET as $k=>$v){
			if($k!=$config['query_string_segment']){
				$parm.= "&$k=$v";
			}
		}
		if($base_url==""){
			$config['base_url'] = current_url()."?".$parm;
		}
		else{
			$config['base_url'] = $base_url;
			/*
			if(substr($base_url,strlen($base_url)-1,1)=="?"){
				//"/info/cid/0?";//.$parm;//current_url()."?".$parm;
			}
			else{
				$config['base_url'] = $base_url."?".$parm;
			}
			*/
		}
	
		$CI2->pagination->cur_page = $page ;
		//print_r($config);
		$CI2->pagination->initialize($config); // 配置分页		
		$page_string =  $CI2->pagination->create_links_wx(true);
		return $page_string ;
	}	
		
	
}
