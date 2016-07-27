<?php
class SummerCampClass{	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
        
       	function admin_addSummerCampClass($datas)
	{
//            echo "<pre>";
//		 print_r($datas);
//                 $days1="";
//                 foreach($_POST['days_of_week'] as $days){
//                 $days1.=$days.",";
//                 }
//                                
//                $datas['day_of_week']=substr($days1,0, (strlen($days1)-1));
                               
                unset($datas['id']);
                $startDate=date('Y-m-d',strtotime($datas['start_date']));
                $datas['start_date']= $startDate;
                $endDate=date('Y-m-d',strtotime($datas['end_date']));
                $datas['end_date']=$endDate;
                $inslmntDate=date('Y-m-d',strtotime($datas['installment_start_date'])); 
                $datas['installment_start_date']=$inslmntDate;
		$this->dbcon->Insert('itf_summercamp_class',$datas);
	}

	function admin_updateSummerCampClass($datas)
	{
            
            
                $condition = array('id'=>$datas['id']);
                unset($datas['id']);
                // echo '<pre>';print_r($datas);
                 $startDate=date('Y-m-d',strtotime($datas['start_date']));
                 $endDate=date('Y-m-d',strtotime($datas['end_date'])); 
                 $inslmntDate=date('Y-m-d',strtotime($datas['installment_start_date'])); 
                 $datas['start_date']= $startDate;
                 $datas['end_date']=$endDate;
                 $datas['installment_start_date']=$inslmntDate;
		 $this->dbcon->Modify('itf_summercamp_class',$datas,$condition);             
                 
	}
        
        function showAllTeacher($Id)
	{
		 $sql="delete from itf_class where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
          
	
	function SummerCampClass_deleteAdmin($Id)
	{
	        $sql="delete from itf_summercamp_class where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAllClasslist1($parentid="0")
	{
		$sql="select * from itf_class  where status='1' ";
		return $this->dbcon->FetchAllResults($sql);
		
	}

        function showAllSummerCampList($parentid='0'){
            $sql="select * from itf_summercamp_class where status='1' ";
		return $this->dbcon->FetchAllResults($sql);
                     
        }
         
//        function showAlllinkCat(){
//          echo $sql= "SELECT link_type FROM `itf_link_type` where status='1'";
//           return $this->dbcon->FetchAllResults($sql);
//        }
//        
//        
        function showAllCampCode()
	{
		$sql="SELECT camp_code FROM `itf_summercamp` where status='1'"; 
                return $this->dbcon->FetchAllResults($sql);
	}
//	function showAllLocation($parentid="0")
//	{
//		$sql="SELECT loc_code FROM `itf_state` where status='1'"; 
//                return $this->dbcon->FetchAllResults($sql);
//	}
//	
//               
//              function showAllClasslist()
//	{
//		$sql="select * from itf_class  where status='1' ";
//		return $this->dbcon->FetchAllResults($sql);
//	}
//	
//	function ShowAllCourseSearch($txtsearch)
//	{
//		$sql="select * from itf_course where  name like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
//		return $this->dbcon->FetchAllResults($sql);
//	}
//	
//        function showAllClass($parentid="0")
//            {
//		$sql="select S.*,(select count(*) from itf_class where parentid=S.id) as totalcity from itf_class S where S.parentid='".$parentid."' ";
//		return $this->dbcon->FetchAllResults($sql);
//	}
//        
	function CheckSummerCampClass($UsId)
	{
		$sql="select U.* from itf_summercamp_class U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}
//	//Function for change status	
//	function PublishBlock($ids)
//	{               
//		$infos=$this->CheckState($ids);
//		if($infos['status']=='1')
//			$datas=array('status'=>'0');
//		else
//			$datas=array('status'=>'1');
//		
//		$condition = array('id'=>$ids);
//		$this->dbcon->Modify('itf_course',$datas,$condition);
//		
//		return ($infos['status']=='1')?"0":"1";
//	}
//
//	//front end============================================================
//	function getAllStateFront($parentid="0")
//	{
//		$sql="select *  from itf_course where status='1' and parentid='".$parentid."' order by name ";
//		return $this->dbcon->FetchAllResults($sql);
//	}
//		
//	function getAllStateCity()
//	{
//		$allstate=$this->showAllState(0);
//		foreach($allstate as &$citystate)
//			$citystate["CITY"]=$this->showAllState($citystate["id"]);
//		return $allstate;
//	}		
}
//$obj = new SummerCampClass();
//echo $obj->test();
?>