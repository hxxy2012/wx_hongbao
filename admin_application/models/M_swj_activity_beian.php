<?php

class M_swj_activity_beian extends M_common {

    private $table = "swj_activity_beian";
    private $tablename = "活动备案";

    function M_swj_project_shenbao_zonghe_fuwu() {
        parent::__construct();
    }

    function count($where) {
        $model = $this->M_common->query_one("select count(*) as dd from " . $this->table . " where $where");
		return $model["dd"];
    }

    function getlist($where,$orderby="create_time desc") {
        $sql = "select * from " . $this->table . " where $where order by ".$orderby;
        return $this->M_common->querylist($sql);
    }

    function GetModel($id) {
        $sql = "select * from " . $this->table . " where id='$id'";
        return $this->M_common->query_one($sql);
    }
	function GetModelFromProID($proid){
		  $sql = "select * from " . $this->table . " where project_shenbao_id='$proid'";	
	    return $this->M_common->query_one($sql);			
	}

    function add($model) {
        $arr = $this->M_common->insert_one($this->table, $model);
        write_action_log(
                $arr['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), ($arr['affect_num'] >= 1 ? 1 : 0), "添加" . $this->tablename . "：" . login_name() . "|管理员ID=" . admin_id() . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
        return $arr['insert_id'];
    }

    function update($model) {
        $arr = $this->M_common->update_data2($this->table, $model, array("id" => $model["id"]));
        write_action_log(
                $arr['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), ($arr['affect_num'] >= 1 ? 1 : 0), "更新" . $this->tablename . "：" . login_name() . "|管理员ID=" . admin_id() . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
    }

    function del($id) {
        $list = $this->getlist("id='" . $id . "'");
        foreach ($list as $v) {
            $model = $this->GetModel($v["id"]);
            $model["isdel"] = "1";
            $arr = $this->update($model);            
            write_action_log(
                    $arr['sql'], $this->uri->uri_string(), login_name(), get_client_ip(), ($arr['affect_num'] >= 1 ? 1 : 0), "删除" . $this->tablename . "：" . login_name() . "|管理员ID=" . ($arr['affect_num'] >= 1 ? "成功" : "失败"));
        }
    }
	



}

?>