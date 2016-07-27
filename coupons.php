<?php 
include('itfconfig.php');
$_SESSION['amit'] = 'Y';
if(isset($_GET['func']) && $_GET['func'] == 'couponsValue') {
	if($_POST['type'] == '1'){
		$cType = 'C';	
	}else {
		$cType = 'S';		
	}
	
	if($_POST['type'] == 't'){
			$cType = 'T';
	}
	 $sql = "select * from itf_coupon where code = '".$_POST['valueC']."' and date(start_date) <='" . date('Y-m-d') . "' and  date(end_date) >='" . date('Y-m-d') . "' and type = '".$cType."' and status = '1'";
 
$CValue = mysql_query($sql);
$CValues = mysql_fetch_array($CValue);

if(!empty($CValues)){
		if($CValues['scope'] == 'local'){
			if($cType == 'C'){
				$valueC = mysql_query("select * from itf_coupon_class where class_id = '".$_POST['classID']."' and coupon_id = '".$CValues['id']."'");
				$valueCC = mysql_fetch_array($valueC);
				if(!empty($valueCC)){
					echo $CValues['discount'];	
				} else {
					echo "Invalid";
				}
			}elseif($cType == 'S'){		
				$valueC = mysql_query("select * from itf_coupon_summercamp where summercamp_id = '".$_POST['classID']."' and coupon_id = '".$CValues['id']."'");
				$valueCC = mysql_fetch_array($valueC);
				if(!empty($valueCC)){
					echo $CValues['discount'];	
				} else {
					echo "Invalid";
				}
			}elseif($cType == 'T'){		
				$valueC = mysql_query("select * from itf_coupon_tournament where tournament_id = '".$_POST['classID']."' and coupon_id = '".$CValues['id']."'");
				$valueCC = mysql_fetch_array($valueC);
				if(!empty($valueCC)){
					echo $CValues['discount'];	
				} else {
					echo "Invalid";
				}
			}else{
				echo "Invalid";
			}
		} else {
		echo $CValues['discount'];		
		}
} else {
	echo "Invalid";	
}

}
?>
