<?php
/*
 *公共的上传图片方法
 *author 王建 
 */
if (! defined('BASEPATH')) {
    exit('Access Denied');
}

class Common_upload{
	var $ci  ;
	function __construct() {
		$this->ci =& get_instance();
		//print_r(get_instance());
	}
	/*	
	@params $upload_path 上传路径
	@params $name file表单的name值
	@params $allow_type 允许上传的文件格式
	@return string $filename ;
	*/	
	function upload_path( $upload_path = '' ,$name = 'image' , $allow_type = 'gif|jpg|png|xls'  ){	
		$image = '' ;
		if(isset($_FILES[$name]) && $_FILES[$name] ){		
			if(!empty($_FILES[$name]['name'])){
				  $config['upload_path'] = $upload_path;
				  $config['allowed_types'] = $allow_type;
				 // $config['max_size'] = '100';
				  //$config['max_width']  = '1024';
				 // $config['max_height']  = '768';
				  $config['remove_spaces']  =true ;
				  $config['file_name']  =time().mt_rand(0,1000);
				  //print_r($config);
				  //echo $_FILES[$name]['name'];	
				  //echo $_FILES[$name]['type'];			  
				  $this->ci->load->library('upload', $config);
				  if ( ! $this->ci->upload->do_upload($name)){
					exit("操作失败,{$this->ci->upload->display_errors()}");
				   } else{
						$data_ = array('upload_data' => $this->ci->upload->data());
						$image = $data_['upload_data']['orig_name'];
				  }
			}
		}
		return $image ; 
	}	
	
	//按年月建目录
	function upload_path_ym( $upload_path = '' ,$name = 'image' , $allow_type = 'gif|jpg|png|xls'  ){
		$image = '' ;
		$newpath = "";
		if(isset($_FILES[$name]) && $_FILES[$name] ){
			if(!empty($_FILES[$name]['name'])){
				$ym = date("Ym");
	
				if(substr($upload_path,strlen($upload_path)-1,1)!="/" && substr($upload_path,strlen($upload_path)-1,1)!="\\"){
					$newpath = $upload_path.$ym;
				}
				else{
					if(substr($upload_path,strlen($upload_path)-1,1)=="/"){
						$newpath = $upload_path."/".$ym;
					}
					else{
						$newpath = $upload_path."\\".$ym;
					}
				}
				if(!is_dir(realpath($newpath))){
					@mkdir($newpath);
				}
	
				$config['upload_path'] = $newpath;
				$config['allowed_types'] = $allow_type;
				// $config['max_size'] = '100';
				//$config['max_width']  = '1024';
				// $config['max_height']  = '768';
				$config['remove_spaces']  =true ;
				$config['file_name']  =time().mt_rand(0,1000);
				//print_r($config);
				//echo $_FILES[$name]['name'];
				//echo $_FILES[$name]['type']."<br/>";
				$this->ci->load->library('upload', $config);
				$this->ci->upload->initialize($config);//必须初始比，以防一个页面有两个上传框
				if ( ! $this->ci->upload->do_upload($name)){
					exit("操作失败,{$this->ci->upload->display_errors()}");
				} else{
					$data_ = array('upload_data' => $this->ci->upload->data());
					$image = $data_['upload_data']['orig_name'];
				}
			}
		}
		if($image!=""){
			if(substr($newpath,strlen($newpath)-1,1)=="/" || substr($newpath,strlen($newpath)-1,1)=="\\"){
				$image = $newpath."".$image;
				/*
					if(substr($newpath,strlen($newpath)-1,1)=="/"){
						
					}
					else{
					$image = $newpath."".$image;
					}
					*/
			}
			else{
				$image = $newpath."/".$image;
			}
			$image = str_replace("//","/", $image);
			$image = str_replace("\\\\","\\", $image);
			$image = str_replace(__ROOT__,"",$image);
		}
		return $image ;
	}	
	
	//按年月建目录 限定宽度
	function upload_path_ym_width( $upload_path = '' ,$name = 'image' , $allow_type = 'gif|jpg|png|xls' ,$width  ){
		$image = '' ;
		$newpath = "";
		if(isset($_FILES[$name]) && $_FILES[$name] ){
			if(!empty($_FILES[$name]['name'])){
				$ym = date("Ym");
	
				if(substr($upload_path,strlen($upload_path)-1,1)!="/" && substr($upload_path,strlen($upload_path)-1,1)!="\\"){
					$newpath = $upload_path.$ym;
				}
				else{
					if(substr($upload_path,strlen($upload_path)-1,1)=="/"){
						$newpath = $upload_path."/".$ym;
					}
					else{
						$newpath = $upload_path."\\".$ym;
					}
				}
				if(!is_dir(realpath($newpath))){
					@mkdir($newpath);
				}
	
				$config['upload_path'] = $newpath;
				$config['allowed_types'] = $allow_type;
				// $config['max_size'] = '100';
				//$config['max_width']  = $width;
				// $config['max_height']  = '768';
				$config['remove_spaces']  =true ;
				$config['file_name']  =time().mt_rand(0,1000);
				//print_r($config);
				//echo $_FILES[$name]['name'];
				//echo $_FILES[$name]['type']."<br/>";
				$this->ci->load->library('upload', $config);
				$this->ci->upload->initialize($config);//必须初始比，以防一个页面有两个上传框
				if ( ! $this->ci->upload->do_upload($name)){
					exit("操作失败,{$this->ci->upload->display_errors()}");
				} else{
					$data_ = array('upload_data' => $this->ci->upload->data());
					$image = $data_['upload_data']['orig_name'];
				}
			}
		}
		if($image!=""){
			
			

			
			if(substr($newpath,strlen($newpath)-1,1)=="/" || substr($newpath,strlen($newpath)-1,1)=="\\"){
				$image = $newpath."".$image;
				/*
				 if(substr($newpath,strlen($newpath)-1,1)=="/"){
	
				 }
				 else{
				 $image = $newpath."".$image;
				 }
				 */
			}
			else{
				$image = $newpath."/".$image;
			}
			$image = str_replace("//","/", $image);
			$image = str_replace("\\\\","\\", $image);
			$image = str_replace(__ROOT__,"",$image);
			
			//检查图片宽度，超过就缩小
			$sizearr = getimagesize($image);
			if($sizearr[0]>$width){
				$this->ci->load->library('image_lib');
				
				$config_img['source_image'] = $image;
				$config_img['create_thumb'] = FALSE;
				$config_img['quality'] = 100;
				$config_img['maintain_ratio'] = FALSE;
				$config_img['width'] = $width;
				$config_img['height'] = $width*$sizearr[1]/$sizearr[0];
				$this->ci->image_lib->initialize($config_img);
				$this->ci->image_lib->resize();
				$this->ci->image_lib->clear();
				
			}			
		}
		return $image ;
	}	
	
}
