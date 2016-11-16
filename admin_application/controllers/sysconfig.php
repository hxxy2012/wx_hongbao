<?php

/*
 * 系统基本信息配置
 * author 王建 
 */
if (!defined('BASEPATH')) {
    exit('Access Denied');
}

class Sysconfig extends MY_Controller {

    private $group_ = array();
    private $type = array();
    private $dbinfo = array();
    private $sysconfig_cache_path = '';

    function Sysconfig() {
        parent::__construct();
        $this->load->model('M_common');
        $this->group_ = config_item("web_group");
        $this->type = config_item("web_type");
        $this->sysconfig_cache_path = config_item("sysconfig_cache");
    }

    function index() {
        $action_array = array(
            'config', 'add_config', 'get_data'
        );
        $action = $this->input->get_post("action");
        $action = (isset($action) && in_array($action, $action_array)) ? $action : 'config';
        if ($action == 'config') {
            $gid = 0;
            $gid = intval($this->input->get_post("gid"));
            if ($gid == 0) {
                if ($this->group_) {
                    $index = 0;
                    foreach ($this->group_ as $k => $v) {
                        if ($index >= 1) {
                            break;
                        }
                        $gid = $k;
                        $index++;
                    }
                }
            }
            $sql_gid = "SELECT * FROM {$this->table_}common_sysconfig where groupid = '{$gid}'";
            $list_data = array();
            $list_data = $this->M_common->querylist($sql_gid);
            if ($list_data) {
                foreach ($list_data as $k1 => $v1) {

                    $text = '';
                    if (in_array($v1['type'], array('number', 'string'))) {
                        $text = "<input type='text' name='{$v1['varname']}' value='{$v1['value']}'>";
                    } elseif ($v1['type'] == 'boolean') {
                        if ($v1['value'] == 'Y') {
                            $text = "是<input type='radio' name='{$v1['varname']}' value='Y' checked='checked'>否<input type='radio' name='{$v1['varname']}' value='N'>";
                        } else {
                            $text = "是<input type='radio' name='{$v1['varname']}' value='Y'>否<input type='radio' name='{$v1['varname']}' value='N' checked='checked'>";
                        }
                    } elseif ($v1['type'] == 'textarea') {
                        $text = "<textarea name='{$v1['varname']}' style='width:360px'>{$v1['value']}</textarea>";
                    }
                    $list_data[$k1]['text'] = $text;
                }
            }

            $data = array(
                'group' => $this->group_,
                'list' => $list_data,
                'gid' => $gid
            );
            $this->make();
            
            $this->load->view(__TEMPLET_FOLDER__ . "/views_sysconfig", $data);
        } elseif ($action == 'get_data') {
            $gid = intval($this->input->get_post("id"));
            //添加系统变量
            $add_data = array(
                'gid' => $gid,
                'group' => $this->group_,
                'type' => $this->type
            );
            $this->load->view(__TEMPLET_FOLDER__ . "/views_sysconfig_add", $add_data);
        }
    }

    //function add
    function add() {
        $gid = verify_id($this->input->get_post("gid"));
        $varname = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("varname"))))); //varname
        $value = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("value"))))); //value
        $info = dowith_sql(daddslashes(html_escape(strip_tags($this->input->get_post("info"))))); //info
        $type = (daddslashes(html_escape(strip_tags($this->input->get_post("type"))))); //value
        if (!array_key_exists($type, $this->type)) {
            $type = 'string';
        }
        $data = array(
            'varname' => $varname,
            'value' => $value,
            'info' => $info,
            'type' => $type,
            'groupid' => $gid
        );
        if (empty($varname)) {
            exit('varname is empty');
        }
        if (utf8_str($varname) != 1) {
            showmessage("变量名称必须是英文", "sysconfig/index", 3, 0, "?action=get_data&gid=true");
            exit();
        }
        $sql_one = "SELECT * FROM {$this->table_}common_sysconfig WHERE varname = '{$varname}' limit 1 ";

        if ($this->M_common->query_one($sql_one)) {
            showmessage("对不起你要添加的数据已经存在", "sysconfig/index", 3, 0, "?action=get_data&gid=true");
            exit();
        }
        $array = $this->M_common->insert_one("{$this->table_}common_sysconfig", $data);
        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "添加系统变量{$varname}成功");
            $this->make();
            showmessage("添加成功", "sysconfig/index", 3, 1, "?gid={$gid}");
            exit();
        } else {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "添加系统变量{$varname}失败");
            showmessage("服务器繁忙请稍候", "sysconfig/index", 3, 0);
            exit();
        }
    }

    function edit() {
        $gid = verify_id($this->input->get_post("gid"));
        if ($_POST) {
            foreach ($_POST as $last_key => $last_val) {
                $last_val = daddslashes(html_escape(strip_tags($last_val)));
                $last_key = daddslashes(html_escape(strip_tags($last_key)));
                $sql_ = "UPDATE `{$this->table_}common_sysconfig` SET `value` = '{$last_val}' WHERE `varname` = '{$last_key}'";
                $this->M_common->update_data($sql_);
            }
            $this->make();
            write_action_log("sysconfig_update", $this->uri->uri_string(), login_name(), get_client_ip(), 1, "修改系统变量成功");
            showmessage("修改成功", "sysconfig/index", 3, 1, "?gid={$gid}");
        } else {
            write_action_log("sysconfig_update", $this->uri->uri_string(), login_name(), get_client_ip(), 0, "修改系统变量失败");
            showmessage("请传递正确的参数", "sysconfig/index", 3, 0);
        }
    }

    //生成
    private function make() {
        $sql_gid = "SELECT * FROM {$this->table_}common_sysconfig";
        $list_data = array();
        $list_data = $this->M_common->querylist($sql_gid);
        if (!is_really_writable(dirname($this->sysconfig_cache_path))) {
            exit("目录" . dirname($this->sysconfig_cache_path) . "不可写,或者不存在");
        }

        if (!file_exists($this->sysconfig_cache_path)) {
            mkdir($this->sysconfig_cache_path);
        }
        $configfile = $this->sysconfig_cache_path . "/sysconfig.inc.php";
        $fp = fopen($configfile, 'w');
        flock($fp, 3);
        fwrite($fp, "<" . "?php\r\n");
        fwrite($fp, "/*网站基本信息配置*/\r\n");
        fwrite($fp, "/*author wangjian*/\r\n");
        fwrite($fp, "/*time 2014_03_03*/\r\n");
        if ($list_data) {
            foreach ($list_data as $j_key => $j_val) {
                $value = daddslashes($j_val['value']);
                if ($j_val['type'] == 'number') {
                    $value = intval($j_val['value']);
                }

                fwrite($fp, "\${$j_val['varname']} ='{$value}';\r\n");
            }
        }
    }

    /**
     * 增加文件来压缩包
     * @param type $path
     * @param type $zip
     */
    function addFileToZip($path, $zip) {
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    addFileToZip($path . "/" . $filename, $zip);
                } else { //将文件加入zip对象
                    $zip->addFile($path . "/" . $filename);
                }
            }
        }
        @closedir($path);
    }

    /**
     * 删除文件夹
     * @param type $dir
     * @return boolean
     */
    function deldir($dir) {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 导入数据库
     */
    public function importDatabase() {
        //获取数据库配置信息
        $dbinfo = $this->databaseinfo();
        include "admin_application/libraries/DBManage.php"; //数据库类
        $db = new DBManage($dbinfo['localhost'], $dbinfo['username'], $dbinfo['password'], $dbinfo['database'], $dbinfo['char_set']);

        $id = verify_id($this->input->get_post("id", true));
        $sql = "select * from rwfp_database_bak where id=$id";
        $row = $this->M_common->query_one($sql);
        $arr = explode('---', $row['title']);
        $title = $arr[0]; //20140928
//        $filename = 'backup/' .$title.'.zip'; //'backup/20140928.zip'
        $filename = 'backup/'.date('Ym', strtotime($row['createtime'])) . '/' . $title . '.zip'; //'backup/20140928.zip'
        
//        echo $filename;exit;
        //解压缩
        $zip = new ZipArchive; //新建一个ZipArchive的对象
//        if ($zip->open('backup/' .$row['title']) === TRUE) {//打开压缩包
        if ($zip->open('backup/' . date('Ym', time()) . '/' . $row['title']) === TRUE) {//打开压缩包
            $zip->extractTo('./'); //假设解压缩到在当前路径下images文件夹内
            $zip->close(); //关闭处理的zip文件
        }

        //导入
        $dirnow = getcwd(); //当前目录
        $dirnow = $dirnow . '/' . $filename;
        $dirnow = substr($dirnow, 0, strpos($dirnow, '.zip')) . '/';
//        echo $dirnow;exit;         
        //打开目录

        $dirnowfile = scandir($dirnow, 1); //目录中文件
//        backup/201409/20140929/
//        print_r($dirnowfile);
//        exit;
        foreach ($dirnowfile as $dirfile) {
            if ($dirfile != '.' && $dirfile != '..') {
                $db->restore($dirnow . $dirfile);
            }
        }
        if ($this->deldir($dirnow)) {//删除原来的文件夹
            write_action_log('无', $this->uri->uri_string(), login_name(), get_client_ip(), 1, "导入数据库备份成功");
            showmessage("导入数据库备份成功", "sysconfig/databaselist", 2, 1); //1:正确
            exit();
        } else {
            write_action_log('无', $this->uri->uri_string(), login_name(), get_client_ip(), 0, "导入数据库备份失败");
            showmessage("导入数据库备份失败", "sysconfig/databaselist", 2, 0);
            exit();
        }
    }

    /**
     * 备份的数据库列表
     */
    public function databaselist() {
        $action = $this->input->get_post("action");
        $action_array = array("show", "ajax_data");
        $action = !in_array($action, $action_array) ? 'show' : $action;
        if ($action == 'show') {
            $this->load->view(__TEMPLET_FOLDER__ . "/views_database");
        } elseif ($action == 'ajax_data') {
            $this->ajax_data();
        }
    }

    //ajax 获取数据
    private function ajax_data() {
        $this->load->library("common_page");
        $page = $this->input->get_post("page");
        if ($page <= 0) {
            $page = 1;
        }
        $per_page = 10; //每一页显示的数量
        $limit = ($page - 1) * $per_page;
        $limit.=",{$per_page}";
//        $where = ' where isdel=0 and pid=0 ';
        $where = ' where 1';
        $sea_title = daddslashes(html_escape(strip_tags(trim($this->input->get_post("search_title", true)))));

        if (!empty($sea_title)) {
            $where.=" AND title like '%$sea_title%'";
        }
        $sql_count = "SELECT COUNT(*) AS tt FROM rwfp_database_bak {$where}";
        $total = $this->M_common->query_count($sql_count);
        $page_string = $this->common_page->page_string($total, $per_page, $page);
        $sql_role = "SELECT * FROM rwfp_database_bak {$where} order by id desc limit  {$limit}";
        $list = $this->M_common->querylist($sql_role);
        foreach($list as $k=>$v){        	
        	$list[$k]["filepath"] =  '../../backup/' . date('Ym', strtotime($v['createtime'])) . '/' . $v['title'];        	
        }
        echo result_to_towf_new($list, 1, '成功', $page_string);
    }

    /**
     * 删除备份的数据库
     */
    public function delDatabase() {
        $id = verify_id($this->input->get_post("id", true));
        $sql = "select * from rwfp_database_bak where id=$id";
        $row = $this->M_common->query_one($sql);
//        $filename = 'backup/' . $row['title'];
        $filename = 'backup/' . date('Ym', strtotime($row['createtime'])) . '/' . $row['title'];
        $array = $this->M_common->del_data("delete from rwfp_database_bak where id=" . $id);
        if ($array >= 1) {
            @unlink($filename); //删除原来的文件夹
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "删除数据库备份成功");
            showmessage("删除数据库备份成功", "sysconfig/databaselist", 2, 1); //1:正确
            exit();
        } else {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "删除数据库备份失败");
            showmessage("删除数据库备份失败", "sysconfig/databaselist", 2, 0);
            exit();
        }
    }

    /**
     * 备份数据库
     */
    public function bakDatabase() {
        //获取数据库配置信息
        $dbinfo = $this->databaseinfo();
        include "admin_application/libraries/DBManage.php"; //数据库类
        $db = new DBManage($dbinfo['localhost'], $dbinfo['username'], $dbinfo['password'], $dbinfo['database'], $dbinfo['char_set']);

        //导出
        $list_tables = $db->list_tables($dbinfo['database']);
		//按表名备份
		/*
        foreach ($list_tables as $table) {
            if ($table == 'rwfp_database_bak') {
                continue;
            }
            $db->backup($table, '', '9000'); //9000 = 9M 
        }
        */
        //全库备份9M一个分卷
        $db->backup('', '', '9000'); //9000 = 9M

//        $datetime = date('Ymd---His', time()); // 以时间来命名
//        $filename = 'backup/' . date('Ymd', time()); //备份好的目录
//        

        $datetime = date('Ymd---His', time()); // 以时间来命名
        $filename = 'backup/' . date('Ym', time()) . '/' . date('Ymd', time()) . '/'; //备份好的目录
        //压缩
        $zip = new ZipArchive();
//        if ($zip->open('backup/'.$datetime.'.zip', ZipArchive::OVERWRITE) === TRUE) {
        if ($zip->open('backup/' . date('Ym', time()) . '/' . $datetime . '.zip', ZipArchive::OVERWRITE) === TRUE) {//打开zip
            $this->addFileToZip($filename, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); //关闭处理的zip文件
            $this->deldir($filename); //删除原来的文件夹
            //保存到数据库
            $data = array(
                'title' => $datetime . '.zip',
                'createtime' => date('Y-m-d H:i:s', time()),
            );
            $array = $this->M_common->insert_one("rwfp_database_bak", $data);
        }
        if ($array['affect_num'] >= 1) {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 1, "数据库备份成功");
            showmessage("数据库备份成功", "sysconfig/databaselist", 3, 1); //1:正确
            exit();
        } else {
            write_action_log($array['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), 0, "数据库备份失败");
            showmessage("数据库备份失败", "sysconfig/databaselist", 3, 0);
            exit();
        }
    }

    /**
     * 获取数据库信息
     * @return type
     */
    private function databaseinfo() {
        include "admin_application/config/database.php";
        $this->dbinfo = array(
            'localhost' => $db['real_data']['hostname'],
            'username' => $db['real_data']['username'],
            'password' => $db['real_data']['password'],
            'database' => $db['real_data']['database'],
            'char_set' => $db['real_data']['char_set'],
        );
        return $this->dbinfo;
    }

}
