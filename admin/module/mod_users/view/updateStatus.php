<?php 

if(isset($_POST['status']) && isset($_POST['status_id'])){
	
	$status = $_POST['status'];
	$id = $_POST['status_id'];
$sql = mysql_query("UPDATE `itf_org` SET `Status`='".$status."' WHERE `org_id`=".$id);
    	if($sql){
    		echo 'hello';exit;
    		$sql1 = "SELECT Status FROM `itf_org`where org_id = ".$id;
    		$finalStatus = $this->dbcon->FetchAllResults($sql);
    		echo $finalStatus;exit;
    	}
}


?>