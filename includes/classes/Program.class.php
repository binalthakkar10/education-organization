<?php
class Program 
{
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	

	function adminAdd($datas)
	{
		unset($datas['id']);
		$this->dbcon->Insert('itf_program',$datas);

	}

	function adminUpdate($datas)
	{
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_program',$datas,$condition);
	}

	function adminDelete($Id)
	{
		$sql="delete from itf_program where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAll()
	{
		$sql="select P.id, P.title,P.status,D.title as department from itf_program P inner join itf_department D on P.department_id=D.id order by P.id desc";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function getActiveData()
	{
		$sql="select * from itf_program where status='1' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}


	function searchAll($txtsearch)
	{
		$sql="select * from itf_program where  title like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function checkData($UsId)
	{
		$sql="select U.* from itf_program U where U.id='".$UsId."'";
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
		$this->dbcon->Modify('itf_program',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	
         function getActiveProgram($deptid)
	{
		$sql="select id,title from itf_program where  status='1' and department_id='".$deptid."' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}
}
?>