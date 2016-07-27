<?php
class Department 
{
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	

	function adminAdd($datas)
	{
		unset($datas['id']);
		$this->dbcon->Insert('itf_department',$datas);

	}

	function adminUpdate($datas)
	{
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_department',$datas,$condition);
	}

	function adminDelete($Id)
	{
		$sql="delete from itf_department where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAll()
	{
		$sql="select D.id, D.title,D.status,F.title as faculty from itf_department D inner join itf_faculty F on D.faculty_id=F.id order by D.id desc";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function getActiveData()
	{
		$sql="select * from itf_department where status='1' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}

	function searchAll($txtsearch)
	{
		$sql="select * from itf_department where  title like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function checkData($UsId)
	{
		$sql="select U.* from itf_department U where U.id='".$UsId."'";
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
		$this->dbcon->Modify('itf_department',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	
         function getActiveDepartment($facultyid)
	{
		$sql="select id,title from itf_department where  status='1' and faculty_id='".$facultyid."' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}
        
}
?>