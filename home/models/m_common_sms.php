<?php
if (! defined('BASEPATH')) {
    exit('Access Denied');
}
//发送短信模型
class m_common_sms extends M_common{

	private $table;
	function Swj_sms(){
		parent::__construct();
		$this->table = 'swj_sms_log';
	}

	/*
	 *发送短信成功,插入到记录表中
	 *参　　数：$content:短信内容，$type:短信类型，$suid:发送用户id, $stype：发送用户类型
	 			,$rInfo:接收用户信息，二维数组，键为type的类型，id的为id,phone电话 ;$fjumb:失败跳转控制器方法
	 *返 回 值：返回检测结果，ture or false 
	 */
	function send_message($content, $type, $suid, $stype, $rInfo, $fjumb) {
		//如果接受短信的类型为后台管理员,查找管理员表
		if ($this->chkSmsOpen()) {//判断是否开启短信端口
			// file_put_contents("f:/aaa.txt", var_export($rInfo,true));
			foreach ($rInfo as $key => $value) {
				$ruid = $value['id'];//id
				$rtype = $value['type'];//类型
				$phone = $value['phone'];//电话
				if (!$phone) {//不存在电话,返回false
					// return false;
					continue;
				}
				$createtime = date('Y-m-d H:i:s', time());
				//插入到发短信记录表中
				$data = array(
					'content' => $content,
					'type' => $type,
					'suid' => $suid,
					'stype' => $stype,
					'ruid' => $ruid,
					'rtype' => $rtype,
					'phone' => $phone,
					'createtime' => $createtime);
				if ($this->do_sms($content, $phone, $data)) {//发送短信成功
					
	    			// file_put_contents("f:/aaa.txt", '343434');
				} else {
					if($fjumb!=""){
						showmessage("发送短信失败","$fjumb",3,0);exit;
					}								
				}
			}
		}
		// file_put_contents("f:/aaa.txt", $key);
		
	}
	/*
	 *进行发送短信
	 *参　　数：$content:短信内容，$phone:发送到的手机号码,$data插入到短信表中
	 *返 回 值：返回检测结果，ture or false 
	 */
	function do_sms($content, $tel, $data) {
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
		$flag = true;//返回发送成功或者失败
		try{
			$obj=$soap->$method($params);
			$result = $obj->RequestIdentifier;
			$flag = true;
		}catch(Exception $e) {
			$result="Exception: " . $e->getMessage();
			$flag = false;
		}
		// print_r($result);
		$data['result'] = $result?$result:'';
		$this->insert_one('swj_sms_log', $data);
		return $flag;
	}
	/*
	 *获取接收短信的后台管理员id,
	 *参　　数：
	 *返 回 值：返回检测结果，以逗号分隔后台管理员id
	 */
	function getAdminInfo() {
		// $str = '';
		$arr = array();
		$sql = "select * from `57sy_common_system_user` where `super_admin`=1";
		$result = $this->querylist($sql);
		$i = 0;//标记是否为第一次
		foreach ($result as $key => $value) {
			/*if ($i == 0&&$value->tel) {//如果为第一次进入且有电话
				$i = 1;
				$str = $value->tel;
			} else if ($value->tel) {
				$str .= ','.$value->tel;
			}*/
			$arr[$i]['phone'] = $value['tel'];
			$arr[$i]['type'] = 0;
			$arr[$i]['id'] = $value['id'];
			$i++;
		}
		// file_put_contents("f:/aaa.txt", var_export($arr, true));
		return $arr;
	}

	//发送给全体管理员$type:类型哪个模块，$userid:发送者普通用户id，$content:短信内容,
	//$fjumb:失败跳转控制器方法 如： "Index/index"
	function sendSmsToSystem($type, $userid, $content, $fjumb){
    	$rInfo = $this->getAdminInfo();
		// file_put_contents("f:/aaa.txt", var_export($rInfo,true));

    	$this->send_message($content, $type, $userid, 1, $rInfo, $fjumb);
	}
    //发给普通用户
	//$userid:接收者普通用户id,
	//$adminid:发送者管理员id,
	//$content:短信内容
    //$fjumb:失败跳转的控制器方法  如： "Index/index"
	function sendSmsToUser($type, $userid, $adminid, $content, $fjumb){
		$rInfo = array();
		$uInfo = $this->query_one("select * from `57sy_common_user` where uid='$userid'");
		$rInfo[0]['id'] = $userid;
		$rInfo[0]['type'] = 1;
		$rInfo[0]['phone'] = $uInfo['tel'];
    	$this->send_message($content, $type, $adminid, 0, $rInfo, $fjumb);
	}	

	//判断系统短信是否开启,开启返回true，否则false
	function chkSmsOpen() {

		$sql = "select value from `57sy_common_sysconfig` where `varname`='cfg_sms_open'";
		$result = $this->query_one($sql);
		if (count($result)<=0) {//没找到
			return false;
		} elseif ($result['value'] == 'Y') {//开启
			return true;
		} else {//没开启
			return false;
		}
	}

}