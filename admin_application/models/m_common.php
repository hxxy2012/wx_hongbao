<?php
/*
 *common model 文件
 *@author 王建 
 */
class M_common extends CI_Model {
	public $db ;
	public $type ; 
	function M_common($params = array() ){
		$type = '' ;
		$type =( isset($params['type']) && $params['type'] )? $params['type'] : 'real_data' ;
		parent::__construct();
		
		$this->db = $this->load->database($type,true);		
	}
	
	//插入一条数据
	function insert_one($table,$data){
		$this->db->insert($table,$data) ;
		return array(
			'affect_num'=>$this->db->affected_rows() ,
			'insert_id'=>$this->db->insert_id(),
			'sql'=>$this->db->last_query()
		);
	}
	//查询1条数据，返回结果
	function query_one($sql){		
		return $this->db->query($sql)->row_array();
	}
	//返回单 表
	function getmodel($table,$id){
		return $this->db->query("select * from $table where id=".$id)->row_array();
	}
	//查询list data
	function querylist($sql){

        $result =array();
		$query = $this->db->query($sql);
		if($query){
			foreach($query->result_array() as $row){
	    		$result[] = $row ;
	    	}		
		}

    	return $result ;
	}
	//查询返回的结果
	function query_count($sql){
		$query = $this->db->query($sql);
		$num_array = $query->result_array();
		$num = 0 ;
		if(isset($num_array[0]) && !empty($num_array[0])){
			foreach ($num_array[0] as $k=>$v){
				$num = $v ;
				break ;
			}
		}	
		return $num ;
		
	}
	//删除数据
	function del_data($sql){
	
		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
		
	}
	function del_array($table,$where_arr){
		//array("id"=>1);
		$this->db->delete($table, $where_arr);
	}
	//修改数据
	function update_data($sql){
	
		$query = $this->db->query($sql);
		return $this->db->affected_rows(); //返回影响的行数
	}
	
	//修改数据
	function update_data2($table,$data,$where){
	
		//$query = $this->db->update_string($table,$data,$where);
		$err = 0;
		$this->db->update($table, $data,$where) or $err=1 ; 		
		return array(
			'affect_num'=>($this->db->affected_rows()>=0 && $err==0)? 1:0 ,
			'insert_id'=>$this->db->insert_id(),
			'sql'=>$this->db->last_query()
		);		
		//return $this->db->affected_rows(); //返回影响的行数
	}	
	
	function create_table($table,$fields){
		$this->load->dbutil();
		$this->load->dbforge();
		$this->dbforge->add_field($fields);		
		//可选的第二个参数如果被设置为TRUE，那么，表的定义中就会添加 "IF NOT EXISTS" 子句
		$this->dbforge->create_table($table, TRUE);
	}
	

	function create_cols($table,$fields){
		/*
		$fields = array(
				'blog_id' => array(
						'type' => 'INT',
						'constraint' => 5,
						'unsigned' => TRUE,
						'auto_increment' => TRUE
				),
				'blog_title' => array(
						'type' => 'VARCHAR',
						'constraint' => '100',
				),
				'blog_author' => array(
						'type' =>'VARCHAR',
						'constraint' => '100',
						'default' => 'King of Town',
				),
				'blog_description' => array(
						'type' => 'TEXT',
						'null' => TRUE,
				),
		);
		*/
		$this->load->dbutil();
		$this->load->dbforge();
		$this->dbforge->add_column($table,$fields);
	}
	
	function get_fields($table){
		$fields = $this->db->list_fields($table);
		return $fields;
	}
	
	
	function get_fields_all($table){
		$result = $this->db->query("SELECT 
							COLUMN_NAME,
							COLUMN_COMMENT							
						  FROM 
						  information_schema.COLUMNS WHERE TABLE_NAME='".$table."'");
						  
		return $result->result_array();
	}
	
	
	

}