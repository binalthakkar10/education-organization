<?php
class Partners {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }


    function addPartnerData($datas) {
    	$datas['last_updated_date']= date("Y-m-d", strtotime($datas['last_updated_date']));
    	$datas['org_id']= $_SESSION['LoginInfo']['org_id'];
        $this->dbcon->Insert('itf_partner', $datas);

    }



    function updatePartnerData($datas) {
        $condition = array('id' => $datas['id']);

        unset($datas['id']);

        $this->dbcon->Modify('itf_partner', $datas, $condition);

    }
    

 	function PartnersDetail($id) {

        $sql = "select * from itf_partner  where id='" . $id . "'";

        return $this->dbcon->Query($sql);

    }
    
   function ShowAllPartnerData($past = true, $txtsearch) {
   	if ($txtsearch['city'] != '') {
   		$wheres[] = "city ='" . $this->dbcon->EscapeString($txtsearch['city']) . "'";
   	}
   	if (count($wheres) > 0) {
   		 $where = ' where  (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ') group by itf_partner.id order by itf_partner.id DESC';
   	}else{
   		$where ='group by itf_partner.id order by itf_partner.id DESC';
   	}
   	
    	$sql = "SELECT itf_partner.*,itf_org.org_id,itf_org.org_name FROM `itf_partner`JOIN itf_org ON itf_partner.org_id = itf_org.org_id ".$where;
    	return $this->dbcon->FetchAllResults($sql);
    }
    function getActiveCity() {
    	$sql = "select distinct(city) from itf_partner  where  city !=''";
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
    function PublishBlock($ids) {
    	$infos = $this->CheckUser($ids);
    	if ($infos['status'] == '1')
    		$datas = array('status' => '0');
    		else
    			$datas = array('status' => '1');
    
    			$condition = array('id' => $ids);
    			$this->dbcon->Modify('itf_partner', $datas, $condition);
    			return ($infos['status'] == '1') ? "0" : "1";
    }
    function CheckUser($UsId) {
    	$sql = "select * from itf_partner where id='" . $UsId . "'";
    	return $this->dbcon->Query($sql);
    }


}

?>
