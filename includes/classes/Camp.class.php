<?php
class Camp
{	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
		
	function admin_addCamp($datas)
	{
		unset($datas['id']);
                 $startDate=date('Y-m-d',strtotime($datas['start_date']));
                $datas['start_date']= $startDate;
                $endDate=date('Y-m-d',strtotime($datas['end_date']));
                $datas['end_date']=$endDate;
		$this->dbcon->Insert('itf_course',$datas);
	}

	function admin_updateCamp($datas)
	{
		
                $condition = array('id'=>$datas['id']);
		unset($datas['id']);
                // echo '<pre>';print_r($datas);
                 $startDate=date('Y-m-d',strtotime($datas['start_date']));
                 $endDate=date('Y-m-d',strtotime($datas['end_date'])); 
                 $datas['start_date']= $startDate;
                 $datas['end_date']=$endDate;
		$this->dbcon->Modify('itf_course',$datas,$condition);
                
                 
	}

	
	function Camp_deleteAdmin($Id)
	{
		echo $sql="delete from itf_course where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAllCamp()
	{
		$sql="select * from itf_course where  type = '2'";
		return $this->dbcon->FetchAllResults($sql);
	}
	
function showAllCampList()
	{
		$sql="select * from itf_course where status = '1' AND type = '2'";
		return $this->dbcon->FetchAllResults($sql);
	}

	function ShowAllCampSearch($txtsearch)
	{
		$sql="select * from itf_course where  name like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function CheckCamp($UsId)
	{
		$sql="select U.* from itf_course U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}
			
	
	//Function for change status	
	function PublishBlock($ids)
	{	

		$infos=$this->CheckCamp($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		
		$condition = array('id'=>$ids);
		$this->dbcon->Modify('itf_course',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	function getAllCampFront($parentid="0")
	{
		$sql="select *  from itf_course where status='1' and parentid='".$parentid."' order by name ";
		return $this->dbcon->FetchAllResults($sql);
	}
		
	function getAllCampCity()
	{
		$allstate=$this->showAllState(0);
		foreach($allstate as &$citystate)
		$citystate["CITY"]=$this->showAllState($citystate["id"]);
		return $allstate;
	}		
}
?>