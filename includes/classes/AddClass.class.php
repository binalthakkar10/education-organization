<?php
class AddClass
{
	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
		
	function admin_addClass($datas)
	{
		unset($datas['id']);
                 $startDate=date('Y-m-d',strtotime($datas['start_date']));
                $datas['start_date']= $startDate;
                $endDate=date('Y-m-d',strtotime($datas['end_date']));
                $datas['end_date']=$endDate;
		$this->dbcon->Insert('itf_addclass',$datas);
	}
	function admin_updateAddClass($datas)
	{
		 $condition = array('id'=>$datas['id']);
		 unset($datas['id']);
                // echo '<pre>';print_r($datas);
//                 $startDate=date('Y-m-d',strtotime($datas['start_date']));
//                 $endDate=date('Y-m-d',strtotime($datas['end_date'])); 
//                 $datas['start_date']= $startDate;
//                 $datas['end_date']=$endDate;
		 $this->dbcon->Modify('itf_addclass',$datas,$condition);             
                 
	}
	
       function showAllAddClass(){
          $sql="select S.*,(select count(*) from itf_addclass where parentid=S.id) as totalcity from itf_addclass S where S.parentid='".$parentid."' ";
		return $this->dbcon->FetchAllResults($sql);            
        }
        
        function class_deleteAdmin($Id)
	{
		$sql="delete from itf_addclass where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAllCourse($parentid="0")
	{
		$sql="select S.*,(select count(*) from itf_course where parentid=S.id) as totalcity from itf_course S where S.parentid='".$parentid."' ";
		return $this->dbcon->FetchAllResults($sql);
	}
	        
              function showAllCourselist()
	{
		$sql="select id, course from itf_course  where status='1' ";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function ShowAllCourseSearch($txtsearch)
	{
		$sql="select * from itf_course where  name like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function CheckClass2($UsId)
	{
		$sql="select U.* from itf_addclass U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}
	//Function for change status	
	function PublishBlock($ids)
	{	
                
		$infos=$this->CheckState($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_course',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	function getAllStateFront($parentid="0")
	{
		$sql="select *  from itf_course where status='1' and parentid='".$parentid."' order by name ";
		return $this->dbcon->FetchAllResults($sql);
	}
		
	function getAllStateCity()
	{
		$allstate=$this->showAllState(0);
		foreach($allstate as &$citystate)
			$citystate["CITY"]=$this->showAllState($citystate["id"]);
		return $allstate;
	}		
}
?>