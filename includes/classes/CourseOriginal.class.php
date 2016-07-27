<?php
class Course 
{
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	

	function adminAdd($datas)
	{
		unset($datas['id']);
		$this->dbcon->Insert('itf_course',$datas);

	}

	function adminUpdate($datas)
	{
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_course',$datas,$condition);
	}

	function adminDelete($Id)
	{
		$sql="delete from itf_course where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAll()
	{
		$sql="select D.id, D.title,D.status,F.title as faculty from itf_course D inner join itf_faculty F on D.faculty_id=F.id order by D.id desc";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function getActiveData()
	{
		$sql="select * from itf_course where status='1' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}

	function searchAll($txtsearch)
	{
		$sql="select * from itf_course where  title like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function checkData($UsId)
	{
		$sql="select U.* from itf_course U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}
			
	
	//Function for change status	
	function PublishBlock($ids)
	{	

		$infos=$this->checkData($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_course',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	
         function getActiveCourse($programid)
	{
		$sql="select id,title from itf_course where  status='1' and program_id='".$programid."' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}
        
}
?>