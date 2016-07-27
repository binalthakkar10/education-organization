<?php
class Users {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

 	function UserDetail($userId) {

        $sql = "select u.* from itf_users u where id='" . $userId . "'";

        return $this->dbcon->Query($sql);

    }
    
   function ShowAllUsers($past = true, $txtsearch) {
   	if ($txtsearch['state'] != '') {
   		$wheres[] = "contact_state ='" . $this->dbcon->EscapeString($txtsearch['state']) . "'";
   	}
   	if (count($wheres) > 0) {
   		 $where = ' where  (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ') group by org_id order by org_id DESC';
   	}else{
   		$where =' group by id order by id DESC';
   	}
    	$sql = "Select itf_users.id,itf_users.user_type,itf_users.first_name,itf_users.last_name,itf_users.user_type,itf_users.status,itf_users.username,itf_users.email, itf_org.org_name,itf_org.contact_state FROM `itf_users` JOIN itf_org ON itf_users.`org_id` = itf_org.org_id ".$where;
    	return $this->dbcon->FetchAllResults($sql);
    }
    function getActiveState() {
    	$sql = "select distinct(contact_state) from itf_org  where status='1' and contact_state !=''";
    
    	return $this->dbcon->FetchAllResults($sql);
    	
    
    }
    function updateStatus($status,$id){
    	$condition = array('org_id' => $id);
    	if($status == '1'){
    		$statusFinal = '0';
    	}else{
    		$statusFinal = '1';
    		}
    	$status = array('Status'=>$statusFinal);
    	unset($id);
    	
    	$this->dbcon->Modify('itf_org', $status, $condition);
    	
    }
    function ShowAllOrg(){
    	$sql="SELECT distinct(`org_name`),`org_id` FROM `itf_org` WHERE `Status`='1'";
    	return $this->dbcon->FetchAllResults($sql);
    }
    function addUser($datas) {
    	unset($datas['id']);
    	$password = $datas['password'];
    	$datas['password'] = md5($datas['password']);
    	$this->dbcon->Insert('itf_users', $datas);
    }
	function updateUser($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        if (empty($datas['password'])) {
            unset($datas['password']);
        } else {
            $datas['password'] = md5($datas['password']);
        }
        $this->dbcon->Modify('itf_users', $datas, $condition);
    }

    function CheckMembership($UsId) {
    	$sql = "select * from itf_users  where id='" . $UsId . "'";
    	return $this->dbcon->Query($sql);
    }
    function PublishMember($ids) {
    	$infos = $this->CheckMembership($ids);
    	if ($infos['status'] == '1')
    		$datas = array('status' => '0');
    		else
    			$datas = array('status' => '1');
    	
    			$condition = array('id' => $ids);
    			$this->dbcon->Modify('itf_users', $datas, $condition);
    	
    			return ($infos['status'] == '1') ? "0" : "1";
    }
}

?>
