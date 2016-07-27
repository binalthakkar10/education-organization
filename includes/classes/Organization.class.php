<?php
class Organization {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }


    function addOrganizationData($datas) {
		$datas['contract_date']= date("Y-m-d", strtotime($datas['contract_date']));
        unset($datas['org_id']);
        $this->dbcon->Insert('itf_org', $datas);

    }



    function updateOrganizationData($datas) {
        $condition = array('org_id' => $datas['org_id']);

        unset($datas['org_id']);

        $this->dbcon->Modify('itf_org', $datas, $condition);

    }
    

 	function OrganizationDetail($orgId) {

        $sql = "select O.* from itf_org O where org_id='" . $orgId . "'";

        return $this->dbcon->Query($sql);

    }
    
   function ShowAllOrganization($past = true, $txtsearch) {
   	if ($txtsearch['state'] != '') {
   		$wheres[] = "contact_state ='" . $this->dbcon->EscapeString($txtsearch['state']) . "'";
   	}
   	if (count($wheres) > 0) {
   		 $where = ' where  (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ') group by org_id order by org_id DESC';
   	}else{
   		$where ='group by org_id order by org_id DESC';
   	}
   	
    	$sql = "SELECT * FROM `itf_org` ".$where;
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


}

?>
