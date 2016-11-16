<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include __ROOT__ . "/include/function/common_function.php";  //包含公用的方法 前台和后台公用
/*
 * 后台常见的方法
 * author 王建
 * time 2014_01_20
 * 
 */
//获取登录的用户名
if (!function_exists("login_name")) {

    function login_name() {
        $data = decode_data();
        if (isset($data['username'])) {
            return $data['username'];
        } else {
            return '';
        }
    }

}

//获取登录的用户所在的群组
if (!function_exists("group_name")) {

    function group_name() {
        $data = decode_data();
        if (isset($data['group_name'])) {
            return $data['group_name'];
        } else {
            return '';
        }
    }

}
//获取登录的用户所在的角色ID 
if (!function_exists("role_id")) {

    function role_id() {
        $data = decode_data();
        if (isset($data['role_id'])) {
            return $data['role_id'];
        } else {
            return '';
        }
    }

}

//获取登录的用户的uid
if (!function_exists("admin_id")) {

    function admin_id() {
        $data = decode_data();
        if (isset($data['admin_id'])) {
            return $data['admin_id'];
        } else {
            return '';
        }
    }

}

//判断当前登录的用户是不是超级管理员
if (!function_exists("is_super_admin")) {

    function is_super_admin() {
        $data = decode_data();
        if (isset($data['isadmin']) && $data['isadmin']) {
            return true;
        } else {
            return false;
        }
    }

}

/*
 * @记录系统操作日志文件到数据库里面 
 * *sql 是要插入数据库中的 log_sql的值 
 * $action 动作
 * $person 操作人
 * $ip ip地址
 * status 操作是否成功 1成功 0失败
 * message 失败信息
 * groupname_ 定义数据库连接信息的时候的 groupname
 */
if (!function_exists("write_action_log")) {

    function write_action_log($sql, $url = '', $person = '', $ip = '', $status = '1', $message = '', $groupname_ = "real_data") {
        if (!config_item('is_write_log_to_database')) {//是否记录日志文件到数据表中
            return false;
        }

        $sql = str_replace("\\", "", $sql); // 把\进行过滤掉
        $sql = str_replace("%", "\%", $sql); // 把 '%'前面加上\
        $sql = str_replace("'", "\'", $sql); // 把 ''过滤掉
        $message = daddslashes($message);
        $time = date("Y-m-d H:i:s", time());
        $time_table = date("Ym", time());


        $table_pre = table_pre($groupname_);

        $sql_table = <<<EOT
CREATE TABLE IF NOT EXISTS `{$table_pre}common_log_{$time_table}` (
  `log_id` mediumint(8) NOT NULL auto_increment,
  `log_url` varchar(50) NOT NULL,
  `log_person` varchar(16) NOT NULL,
  `log_time` datetime NOT NULL,
  `log_ip` char(15) NOT NULL,
  `log_sql` text NOT NULL,
  `log_status` tinyint(1) NOT NULL default '1',
  `log_message` varchar(255) NOT NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;		
EOT;
        $ci = &get_instance(); //初始化 为了用方法
        $d = $ci->load->database($groupname_, true);
        $d->query($sql_table);
        $sql_log = "INSERT INTO `{$table_pre}common_log_{$time_table}`(`log_url`,`log_person`,`log_time`,`log_ip`,`log_sql`,`log_status`,`log_message`)VALUES('{$url}','{$person}','{$time}','{$ip}','{$sql}','{$status}','{$message}')";
        $d->query($sql_log);
    }

}



/**
 * 将数据格式化成树形结构
 * @author 王建
 * @param array $items
 * @return array
 */
if (!function_exists("genTree9")) {

    function genTree9($items, $id = 'id', $pid = 'pid', $child = 'children') {
        $tree = array(); //格式化好的树
        foreach ($items as $item)
            if (isset($items[$item[$pid]]))
                $items[$item[$pid]][$child][] = &$items[$item[$id]];
            else
                $tree[] = &$items[$item[$id]];
        return $tree;
    }

}

/**
 * 格式化select
 * @author 王建
 * @param array $parent
 * @deep int 层级关系 
 * @return array
 */
function getChildren($parent, $deep = 0) {
    foreach ($parent as $row) {
        $data[] = array("id" => $row['id'], "name" => $row['name'], "pid" => $row['parentid'], 'deep' => $deep, 'url' => $row['url']);
        if (isset($row['childs']) && !empty($row['childs'])) {
            $data = array_merge($data, getChildren($row['childs'], $deep + 1));
        }
    }
    return $data;
}

/**
 * 格式化select,生成options
 * @author 王建
 * @param array $parent
 * @deep int 层级关系 
 * @return array
 */
function getChildren2($parent, $deep = 0, $id = 'id', $pid = 'pid', $name = 'typename', $children = 'children') {
    foreach ($parent as $row) {
        $data[] = array("id" => $row[$id], "name" => $row[$name], "pid" => $row[$pid], 'deep' => $deep);
        if (isset($row[$children]) && !empty($row[$children])) {
            $data = array_merge($data, getChildren2($row[$children], $deep + 1, $id, $pid, $name, $children));
        }
    }
    return $data;
}

/**
 * 格式化数组，
 * @author 王建
 * @param array $list
 * @return array
 */
function tree_format(&$list, $pid = 0, $level = 0, $html = '--', $pid_string = 'pid', $id_string = 'id') {
    static $tree = array();
    foreach ($list as $v) {
        if ($v[$pid_string] == $pid) {
            $v['sort'] = $level;
            $v['html'] = str_repeat($html, $level);
            $tree[] = $v;
            tree_format($list, $v[$id_string], $level + 1, $html);
        }
    }
    return $tree;
}

/**
 * 显示页面
 * @author 王建
 * @param string $message 错误信息
 * @param string $url 页面跳转地址
 * @param string $timeout 时间
 * @param string $iserror 是否错误 1正确 0错误
 * @param string $params 其他参数前面加? 例如?id=122&time=333
 */
if (!function_exists('showmessage')) {

    //跳转	$template模板以哪个模板进行跳转（2016年3月26日10:31:56）
    function showmessage($message = '', $url = '', $timeout = '3', $iserror = 1, $params = '',$template='') {
        if ($iserror == 1) {//正确
            include APPPATH . '/errors/showmessage.php';
        } else {
            include APPPATH . "/errors/showmessage_error$template.php";
        }

        die();
    }

}
/**
 * 获取后台登陆的数据，其中参数主要是为了 ，有时候用插件上传图片的时候 登陆状态消失
 * @author 王建
 * @param $string 解密的值
 * @return array
 */
if (!function_exists("decode_data")) {

    function decode_data($string = '') {
		//改为直接读数据库
		$ckey = "swj_admin";
		$session_id = isset($_COOKIE[$ckey])?$_COOKIE[$ckey]:"";
		$data = array();
		if($session_id!=""){
			$sql = "select * from admin_user_session where session_id='".$session_id."'";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)){
				$array = unserialize($row["user_data"]);
				$array["session_id"] = $session_id;
				$array["role_id"] = $array["gid"];
				$data = $array;
			}
		}
		//print_r($data);
		
		
		/*
        $data = array();
        $encode_string = '';
		$ckey = "swj_admin";//admin_auth
        $encode_string = ($string != "" ) ? $string : (isset($_COOKIE[$ckey]) ? $_COOKIE[$ckey] : '');

        //$encode_string = isset($_COOKIE['admin_auth'])?$_COOKIE['admin_auth']:'' ;
        if (empty($encode_string)) {
            return $data;
        }
        $encode_string = auth_code($encode_string, "DECODE", config_item("s_key"));
        $data = unserialize($encode_string);
        //file_put_contents("e:aa.txt","bbbb=".print_r($data,true));
		*/
        return $data;
		
    }

}
/**
 * 获取ids数据
 * @author 王建
 * @param $array array(1,3,4,5,6,7)
 * @return String 1,3,4,5,6,7
 */
if (!function_exists("get_ids")) {

    function get_ids($ids = '') {
        if ($ids) {
            $id = '';
            for ($i = 0; $i < count($ids); $i++) {
                if (intval($ids[$i]) <= 0) {
                    continue;
                }
                $id.=intval($ids[$i]) . ",";
            }
            $id = rtrim($id, ",");
            return $id;
        } else {
            return '';
        }
    }

}
/**
 * 获取当前网址，含端口号
 * @author WEI
 * @param $array array(1,3,4,5,6,7)
 * @return String 1,3,4,5,6,7
 */
if (!function_exists("get_url")) {

    function get_url() {
		if (!isset($_SERVER['REQUEST_URI']))
 		{
			$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'],1 );
	       if (isset($_SERVER['QUERY_STRING'])) { $_SERVER['REQUEST_URI'].='?'.$_SERVER['QUERY_STRING']; }
 		}
 
        $url = 'http://' . $_SERVER['SERVER_NAME']; //
        if ($_SERVER["SERVER_PORT"] != 80) {
            $url.=":" . $_SERVER["SERVER_PORT"];
        }
		if(substr($_SERVER["REQUEST_URI"],0,1)=="/"){
	        $url .= $_SERVER["REQUEST_URI"];
		}
		else
		{
			$url .= "/".$_SERVER["REQUEST_URI"];
		}
        return $url;
    }

}
/**
 * 查询空白的时候有没有做别的任务
 * @param type $starttime 开始
 * @param type $endtime 结束
 * @param type $id 模块id
 * @return type 返回时间段数组
 */
if (!function_exists("get_space_work")) {

    function get_space_work($starttime, $endtime, $id) {
        $sql = "   
            select 
            from_unixtime(starttime) as starttime
            ,from_unixtime(endtime) as endtime
            ,userhours
            ,mokuai_id
            from 
            rwfp_mokuai_usertime as usertime
            ,57sy_common_system_user as sysuser
            where
            sysuser.gid=8 
            and  sysuser.id=usertime.mokuai_userid
            and  starttime >= {$starttime}
            and
            endtime <= {$endtime}
            and endtime !=0
            and mokuai_userid=(select mokuai_userid from rwfp_mokuai where id={$id})
            order by starttime    
        ";
//            echo $sql;exit;
        $result = mysql_query($sql);
        $count = mysql_num_rows($result);
        if ($count > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $arr[] = $row;
            }
            return $arr;
        } else {
            return array();
        }
    }

}

/**
 * 获取工作进度条
 * @param type $arr 空白条中的工作任务数组
 * @param type $starttime 开始时间
 * @param type $endtime 结束时间
 */
if (!function_exists("get_work_tiao")) {
    function get_work_tiao($arr,$starttime,$endtime) {
        $f = 1;
        $count = count($arr);
        foreach ($arr as $arrkey => $arrval) {
            //之前的任务到别的任务开始
            if ($f == 1) {
                $space2_time = (strtotime($arrval['starttime']) - strtotime($starttime) ) / 3600;
                $space2 = round($space2_time / 24 * 100, 1) . '%';
                $spaceWidthcolor = '#FFF';
                echo "<div  style='float:left;background-color:" . $spaceWidthcolor . ";width: " . $space2 . "'>&nbsp;</div>";
                echo "<span style='display:none'>{$arrval['starttime']}----{$starttime}</span>";
            }
            //任务时间
            $space2_time = (strtotime($arrval['endtime']) - strtotime($arrval['starttime'])) / 3600;
            $space2 = round($space2_time / 24 * 100, 1) . '%';
            
            $spaceWidthcolor = '#CC00CC';
            echo "<div title='{$arrval['mokuai_id']}'  style='float:left;background-color:" . $spaceWidthcolor . ";width: " . $space2 . "'>&nbsp;</div>";
            echo "<span style='display:none'>{$arrval['endtime']}----{$arrval['starttime']}</span>";
            
            //任务与任务之间的空白
            if ($count == 1 || $f == $count) {
                $space2_time = (strtotime($endtime) - strtotime($arrval['endtime'])) / 3600;
                $show_starttime = $endtime;
            } else {
                $space2_time = (strtotime($arr[$arrkey + 1]['starttime']) - strtotime($arrval['endtime'])) / 3600;
                $show_starttime = $arr[$arrkey + 1]['starttime'];
            }
            $space2 = round($space2_time / 24 * 100, 1) . '%';
            $spaceWidthcolor = '#FFF';
            echo "<div  style='float:left;background-color:" . $spaceWidthcolor . ";width: " . $space2 . "'>&nbsp;</div>";
            echo "<span style='display:none'>{$show_starttime}----{$arrval['endtime']}</span>";
            $f++;
        }
    }

}



if (!function_exists('cn_substr')) {

    function cn_substr($str, $slen, $startdd = 0) {
        global $cfg_soft_lang;
        if ($cfg_soft_lang == 'utf-8') {
            return cn_substr_utf8($str, $slen, $startdd);
        }
        $restr = '';
        $c = '';
        $str_len = strlen($str);
        if ($str_len < $startdd + 1) {
            return '';
        }
        if ($str_len < $startdd + $slen || $slen == 0) {
            $slen = $str_len - $startdd;
        }
        $enddd = $startdd + $slen - 1;
        for ($i = 0; $i < $str_len; $i++) {
            if ($startdd == 0) {
                $restr .= $c;
            } else if ($i > $startdd) {
                $restr .= $c;
            }

            if (ord($str[$i]) > 0x80) {
                if ($str_len > $i + 1) {
                    $c = $str[$i] . $str[$i + 1];
                }
                $i++;
            } else {
                $c = $str[$i];
            }

            if ($i >= $enddd) {
                if (strlen($restr) + strlen($c) > $slen) {
                    break;
                } else {
                    $restr .= $c;
                    break;
                }
            }
        }
        return $restr;
    }

}

if (!function_exists('getImgsFormEditor')) {
	function getImgsFormEditor($content,$order='ALL'){
		$pattern="/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
		preg_match_all($pattern,$content,$match);
		if(isset($match[1])&&!empty($match[1])){
			if($order==='ALL'){
				return $match[1];
			}
			if(is_numeric($order)&&isset($match[1][$order])){
				return $match[1][$order];
			}
		}
		return '';
	}
}
if (!function_exists('create_guid')) {
       function create_guid() {
		$charid = strtolower(md5(uniqid(mt_rand(), true)));
		$hyphen = chr(45);// "-"
	//	$uuid = chr(123)// "{"
		$uuid=
		substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12);
		//.chr(125);// "}"
		return $uuid;
	}
}
   
/**
 * 创建多级不存在目录
 * @param type $dir 相对目录也可以
 * @param type $mode
 * @return void
 */
if (!function_exists('mkdirs')) {
function mkdirs($dir, $mode = 0777) {
    $dirArray = explode("/", $dir);
    $dirArray = array_filter($dirArray);
    $created = "";
    foreach ($dirArray as $key => $value) {
        if (!empty($created)) {
            $created .= "/" . $value;
            if (!is_dir($created)) {
                @mkdir($created, $mode);
            }
        } else {
            if (!is_dir($value)) {
                @mkdir($value, $mode);
            }
            $created .= $value;
        }
    }
}
}

//保存远程图片
function GrabImage($url,$filename="") {
	if($url==""):return false;endif;

	if($filename=="") {
		$ext=strrchr($url,".");
		if($ext!=".gif" && $ext!=".jpg"):return false;endif;
		$filename=date("dMYHis").$ext;
	}

	ob_start();
	readfile($url);
	$img = ob_get_contents();
	ob_end_clean();
	$size = strlen($img);

	$fp2=@fopen($filename, "a");
	fwrite($fp2,$img);
	fclose($fp2);

	return $filename;
}

//从内容中将远程图片下载到本地
function downloadpic_content($content){
	$isopen = true;
	if(!$isopen){
		return $content;
	}
	$matches = array();
	preg_match_all("/<img([^>]*)\s*src=('|\")(http:\/\/)([^'\"]+)('|\")/",
	$content,$matches);//带引号
	//preg_match_all("/<img([^>]*)\ssrc=([^\s>]+)/",$string,$matches);//不带引号
	$new_arr = "";
	if(count($matches)>0){
		$new_arr=array_unique($matches[4]);//去除数组中重复的值	
	}
	$new_file_arr = array();
	for($i=0;$i<count($new_arr);$i++){
		$yuancheng = "http://".$new_arr[$i];
		//判断是否为本站，如果不是，就保存
		if(strstr($yuancheng,"http://".$_SERVER['HTTP_HOST'])!==false){
			
			
		}
		else{
			$filetype = explode(".",$yuancheng);
			$filetype = $filetype[count($filetype)-1];
			//新文 件名
			$newfilename = strtolower(create_guid()).".".$filetype;
			$path = "data/upload/news/".date("Ym");
			if(!is_dir($path)){
				@mkdir($path);
			}
			GrabImage($yuancheng,$path."/".$newfilename);
			$new_file_arr[$i] = "/".$path."/".$newfilename;
			$content = str_replace($yuancheng, $new_file_arr[$i], $content);
		}
	}		
	return $content;
}



/**
 * 创建多级不存在目录
 * @param type $dir 相对目录也可以
 * @param type $mode
 * @return void
 */
function mkdirs($dir, $mode = 0777) {
    $dirArray = explode("/", $dir);
    $dirArray = array_filter($dirArray);
    $created = "";
    foreach ($dirArray as $key => $value) {
        if (!empty($created)) {
            $created .= "/" . $value;
            if (!is_dir($created)) {
                @mkdir($created, $mode);
            }
        } else {
            if (!is_dir($value)) {
                @mkdir($value, $mode);
            }
            $created .= $value;
        }
    }
}



if (!function_exists('create_guid')) {
	function create_guid() {
		$charid = strtolower(md5(uniqid(mt_rand(), true)));
		$hyphen = chr(45);// "-"
	//	$uuid = chr(123)// "{"
		$uuid=
		substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12);
		//.chr(125);// "}"
		return $uuid;
	}
}



function sendmail_help($mail_to, $mail_subject, $mail_message) {


	$bfconfig = Array (
			'sitename' => '中山市走出去综合服务平台'//'中山市电子商务信息管理系统',
	);

	$mail = Array (
			'state' => 1,
			'server' => 'smtp.163.com',
			'port' => 25,
			'auth' => 1,
			'username' => '18022156863@163.com',
			'password' => 'admin123123',
			'charset' => 'utf-8',
			'mailfrom' => '18022156863@163.com'
	);

	date_default_timezone_set('PRC');

	$mail_subject = '=?'.$mail['charset'].'?B?'.base64_encode($mail_subject).'?=';
	$mail_message = chunk_split(base64_encode(preg_replace("/(^|(\r\n))(\.)/", "\1.\3", $mail_message)));

	$headers = "";
	$headers .= "MIME-Version:1.0\r\n";
	$headers .= "Content-type:text/html\r\n";
	$headers .= "Content-Transfer-Encoding: base64\r\n";
	$headers .= "From: ".$bfconfig['sitename']."<".$mail['mailfrom'].">\r\n";
	$headers .= "Date: ".date("r")."\r\n";
	list($msec, $sec) = explode(" ", microtime());
	$headers .= "Message-ID: <".date("YmdHis", $sec).".".($msec * 1000000).".".$mail['mailfrom'].">\r\n";

	if(!$fp = fsockopen($mail['server'], $mail['port'], $errno, $errstr, 30)) {
		exit("CONNECT - Unable to connect to the SMTP server");
	}

	stream_set_blocking($fp, true);

	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != '220') {
		exit("CONNECT - ".$lastmessage);
	}

	fputs($fp, ($mail['auth'] ? 'EHLO' : 'HELO')." befen\r\n");
	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) {
		exit("HELO/EHLO - ".$lastmessage);
	}

	while(1) {
		if(substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) {
			break;
		}
		$lastmessage = fgets($fp, 512);
	}

	if($mail['auth']) {
		fputs($fp, "AUTH LOGIN\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 334) {
			exit($lastmessage);
		}

		fputs($fp, base64_encode($mail['username'])."\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 334) {
			exit("AUTH LOGIN - ".$lastmessage);
		}

		fputs($fp, base64_encode($mail['password'])."\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 235) {
			exit("AUTH LOGIN - ".$lastmessage);
		}

		$email_from = $mail['mailfrom'];
	}

	fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != 250) {
		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250) {
			exit("MAIL FROM - ".$lastmessage);
		}
	}

	foreach(explode(',', $mail_to) as $touser) {
		$touser = trim($touser);
		if($touser) {
			fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 250) {
				fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
				$lastmessage = fgets($fp, 512);
				exit("RCPT TO - ".$lastmessage);
			}
		}
	}

	fputs($fp, "DATA\r\n");
	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != 354) {
		exit("DATA - ".$lastmessage);
	}

	fputs($fp, $headers);
	fputs($fp, "To: ".$mail_to."\r\n");
	fputs($fp, "Subject: $mail_subject\r\n");
	fputs($fp, "\r\n\r\n");
	fputs($fp, "$mail_message\r\n.\r\n");
	$lastmessage = fgets($fp, 512);
	if(substr($lastmessage, 0, 3) != 250) {
		exit("END - ".$lastmessage);
	}

	fputs($fp, "QUIT\r\n");

}

/*
 * 找出数组中重复最多的元素
 * /*输出结果为：
Array
 (
     [3] => 5
     [4] => 3
     [1] => 3
     [9] => 2
     [45] => 2
 )
 */
function mostRepeatedValues($array,$length=0){
    if(emptyempty($array) or !is_array($array)){
        return false;
    }
    //1. 计算数组的重复值
    $array = array_count_values($array);
    //2. 根据重复值 倒排序
    arsort($array);
    if($length>0){
        //3. 返回前 $length 重复值
        $array = array_slice($array, 0, $length, true);
    }
    return $array;
}

/**
 * 导入excel时候的时间处理函数
 * @param $date
 * @param bool $time
 * @return array|int|string
 */
function excelTime($date, $time = false) {
    if(function_exists('GregorianToJD')){
        if (is_numeric( $date )) {
            $jd = gregoriantojd( 1, 1, 1970 );
            $gregorian = jdtogregorian( $jd + intval ( $date ) - 25569 );
            $date = explode( '/', $gregorian );
            $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
                ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
                . ($time ? " 00:00:00" : '');
            return $date_str;
        }
    }else{
        $date=$date>25568?$date+1:25569;
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $ofs=(70 * 365 + 17+2) * 86400;
        $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');
    }
    return $date;
}

/**
 * 异步发送请求
 * @param $url
 * @param array $post_data 内必须要有session_id 用于保持会话
 * @return mixed
 */
function asyncPost($url,$post_data){
    //$post_data = array ("username" => "bob","key" => "12345");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, FALSE); // remove body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);//1s等待
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $head;
    //echo "head:".$head;
}

/**
 * @param string $receive_userid 接收的会员ID，用“，”隔开
 * @param string $receive_sysuserid 接收的管理员ID，用“，”隔开
 * @param string $title 邮件标题
 * @param string $content 邮件内容
 * @param string $userid 发送人用户ID （跟发送人管理员ID 只能二选一）
 * @param string $sysuerid 发送人管理员ID  （跟发送人用户ID 只能二选一）
 * @param string $session_id 用于保持会话，否则会退出显示登录,$this->data["sess"] = $this->parent_getsession()获取
 * @return mixed 返回HTTP代码
 */
function send_zhan_mail($receive_userid,$receive_sysuserid,$title,$content,$userid,$sysuerid,$session_id){
    $url = site_url("zcq_mail/add");
    $data = array(
        "receive_userid"=>$receive_userid,
        "receive_sysuserid"=>$receive_sysuserid,
        "title"=>$title,
        "content"=>$content,
        "session_id"=>$session_id,
        "ishelp"=>"yes"

    );

    //修正bug
    if (isset($userid)) {
        $data['userid'] = $userid;
    }
    if (isset($sysuerid)) {
        $data['sysuserid'] = $sysuerid;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, FALSE); // remove body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return  $httpCode;
}

?>