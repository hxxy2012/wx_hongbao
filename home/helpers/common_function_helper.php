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

    //跳转    $template模板以哪个模板进行跳转（2016年3月26日10:31:56）
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
        $data = array();
        $encode_string = '';
        $encode_string = ($string != "" ) ? $string : (isset($_COOKIE['admin_auth']) ? $_COOKIE['admin_auth'] : '');

        //$encode_string = isset($_COOKIE['admin_auth'])?$_COOKIE['admin_auth']:'' ;
        if (empty($encode_string)) {
            return $data;
        }
        $encode_string = auth_code($encode_string, "DECODE", config_item("s_key"));
        $data = unserialize($encode_string);
        //file_put_contents("e:aa.txt","bbbb=".print_r($data,true));
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

function cn_substr_utf8($str, $length, $start=0)
{
	if(strlen($str) < $start+1)
	{
		return '';
	}
	preg_match_all("/./su", $str, $ar);
	$str = '';
	$tstr = '';

	for($i=0; isset($ar[0][$i]); $i++)
	{
		if(strlen($tstr) < $start)
		{
			$tstr .= $ar[0][$i];
		}
		else
		{
			if(strlen($str) < $length + strlen($ar[0][$i]) )
			{
				$str .= $ar[0][$i];
			}
			else
			{
				break;
			}
		}
	}
	return $str;
}

if (!function_exists('cn_substr')) {

    function cn_substr($str, $slen, $startdd = 0) {
        global $cfg_soft_lang;
        $cfg_soft_lang = 'utf-8';
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

function sendmail_help($mail_to, $mail_subject, $mail_message) {


	$bfconfig = Array (
			'sitename' => '中山市电子商务信息管理系统',
	);

	/*$mail = Array (
			'state' => 1,
			'server' => 'smtp.126.com',
			'port' => 25,
			'auth' => 1,
			'username' => 'www_car_cc',
			'password' => '19820830',
			'charset' => 'utf-8',
			'mailfrom' => 'www_car_cc@126.com'
	);*/
    $mail = Array (
            'state' => 1,
            'server' => 'smtp.163.com',
            'port' => 25,
            'auth' => 1,
            'username' => 'zsboc_no_reply',
            'password' => 'swj18676000323',
            'charset' => 'utf-8',
            'mailfrom' => 'zsboc_no_reply@163.com'
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

function sendmail_help2($mail_to, $mail_subject, $mail_message) {


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
//zx99
function helper_pinyin($_String, $_Code='UTF8'){ //GBK页面可改为gb2312，其他随意填写为UTF8
	$_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
			"|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
			"cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
			"|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
			"|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
			"|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
			"|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
			"|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
			"|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
			"|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
			"|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
			"she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
			"tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
			"|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
			"|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
			"zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
	$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
			"|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
			"|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
			"|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
			"|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
			"|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
			"|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
			"|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
			"|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
			"|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
			"|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
			"|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
			"|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
			"|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
			"|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
			"|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
			"|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
			"|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
			"|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
			"|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
			"|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
			"|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
			"|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
			"|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
			"|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
			"|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
			"|-10270|-10262|-10260|-10256|-10254";
	$_TDataKey   = explode('|', $_DataKey);
	$_TDataValue = explode('|', $_DataValue);
	$_Data = array_combine($_TDataKey, $_TDataValue);
	arsort($_Data);
	reset($_Data);
	if($_Code!= 'gb2312') $_String = _U2_Utf8_Gb($_String);
	$_Res = '';
	for($i=0; $i<strlen($_String); $i++) {
		$_P = ord(substr($_String, $i, 1));
		if($_P>160) {
			$_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536;
		}
		$_Res .= _Pinyin($_P, $_Data);
	}
	return preg_replace("/[^a-z0-9]*/", '', $_Res);
}
function _Pinyin($_Num, $_Data){
	if($_Num>0 && $_Num<160 ){
		return chr($_Num);
	}elseif($_Num<-20319 || $_Num>-10247){
		return '';
	}else{
		foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
		return $k;
	}
}
function _U2_Utf8_Gb($_C){
	$_String = '';
	if($_C < 0x80){
		$_String .= $_C;
	}elseif($_C < 0x800) {
		$_String .= chr(0xC0 | $_C>>6);
		$_String .= chr(0x80 | $_C & 0x3F);
	}elseif($_C < 0x10000){
		$_String .= chr(0xE0 | $_C>>12);
		$_String .= chr(0x80 | $_C>>6 & 0x3F);
		$_String .= chr(0x80 | $_C & 0x3F);
	}elseif($_C < 0x200000) {
		$_String .= chr(0xF0 | $_C>>18);
		$_String .= chr(0x80 | $_C>>12 & 0x3F);
		$_String .= chr(0x80 | $_C>>6 & 0x3F);
		$_String .= chr(0x80 | $_C & 0x3F);
	}
	return iconv('UTF-8', 'GB2312', $_String);
}

function str_split_utf8($str,$charset='utf-8'){
    $strlen=mb_strlen($str);
    while($strlen){
        $array[]=mb_substr($str,0,1,$charset);
        $str=mb_substr($str,1,$strlen,$charset);
        $strlen=mb_strlen($str);
    }
    return $array;
}
//起止日期，月份
function helper_add_date($orgDate,$mth){
	$cd = strtotime($orgDate);
	$retDAY = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$mth,date('d',$cd),date('Y',$cd)));
	return $retDAY;
}

function help_gettext($html){
	$sublen = 9999;
	$string = strip_tags($html);
	$string = str_replace("　","",$string);
	$string = str_replace(" ","",$string);
	$string = str_replace("，","",$string);
	$string = str_replace(",","",$string);
	$string = str_replace("“","",$string);
	$string = str_replace("”","",$string);
	$string = str_replace("\t","",$string);
	$string = str_replace("&emsp;","",$string);
	$string = preg_replace ('/\n/is', '', $string);
	$string = preg_replace ('/|/is', '', $string); 
	$string = preg_replace ('/&nbsp;/is', '', $string);
	
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);
	if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";
	else $string = join('', array_slice($t_string[0], 0, $sublen));
	return $string;
}

function sendSMSFromWS($tel,$content){

	require_once("other/nusoap_lib/nusoap.php");
	@ini_set("soap.wsdl_cache_enabled", "1"); // disabling WSDL cache
	$wsdl = "http://61.142.73.74:10005/services/cmcc_mas_wbs?wsdl";
	$soap=new SoapClient($wsdl, array( 'trace'=>true,'cache_wsdl'=>WSDL_CACHE_NONE, 'soap_version'   => SOAP_1_1));
	$method="sendSms";//sendSmsRequest";
	//$method="getRecSmsByAppilicationID";
	$params = array (
		'ApplicationID' => 'P000000000000037',
		'DestinationAddresses' => "tel:$tel",
		'ExtendCode' => "8",
		'Message' => $content,
		'MessageFormat' => "GB2312",
		'SendMethod' => "Normal",
		'DeliveryResultRequest' => "true"	
	);
	try{
		$result=$soap->$method($params);
	}catch(Exception $e) {
		echo "Exception: " . $e->getMessage();
	}	
}

//获取客户端ip地址2016年8月12日17:08:45 
if (!function_exists("getIP")){
    function getIP(){
        global $ip;
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow";
        return $ip;
    }
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
    //$url =$_SERVER['SERVER_NAME']."/index.php/adminx/zcq_mail/add";//site_url("adminx/zcq_mail/add");
    $url = ($_SERVER['SERVER_PORT']=="443"?"https":"http")."://".$_SERVER['SERVER_NAME']."".($_SERVER['SERVER_PORT']=="443" || $_SERVER['SERVER_PORT']=="80"?"":(":".$_SERVER['SERVER_PORT']))."/index.php/adminx/zcq_mail/add";
    // echo $url;
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

    $ch = curl_init($url);
    /*
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_NOBODY, FALSE); // remove body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return  $httpCode."<br>{$head}";
}

/**
 * 返回所有管理员ID
 */
function get_all_admin_id(){
    $id = "";
    $ci = &get_instance(); //初始化 为了用方法
    $d = $ci->load->database();
    $sql="select id from 57sy_common_system_user where status='1'";
    //$list = $d->query($sql);
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result)){
        if($id ==""){
            $id=$row["id"];
        }
        else{
            $id.=",".$row["id"];
        }
    }
    return $id;
}

function subString($str, $start, $length) {
    $i = 0;
    //完整排除之前的UTF8字符
    while($i < $start) {
        $ord = ord($str{$i});
        if($ord < 192) {
            $i++;
        } elseif($ord <224) {
            $i += 2;
        } else {
            $i += 3;
        }
    }
    //开始截取
    $result = '';
    while($i < $start + $length && $i < strlen($str)) {
        $ord = ord($str{$i});
        if($ord < 192) {
            $result .= $str{$i};
            $i++;
        } elseif($ord <224) {
            $result .= $str{$i}.$str{$i+1};
            $i += 2;
        } else {
            $result .= $str{$i}.$str{$i+1}.$str{$i+2};
            $i += 3;
        }
    }
    if($i < strlen($str)) {
        $result .= '...';
    }
    return $result;
}
?>