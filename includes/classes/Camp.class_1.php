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
		$this->dbcon->Insert('itf_summercamp',$datas);
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
		$this->dbcon->Modify('itf_summercamp',$datas,$condition);
                
                 
	}

	
	function Camp_deleteAdmin($Id)
	{
		echo $sql="delete from itf_summercamp where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAllCamp($parentid="0")
	{
		$sql="select S.*,(select count(*) from itf_summercamp where parentid=S.id) as totalcity from itf_summercamp S where S.parentid='".$parentid."' ";
		return $this->dbcon->FetchAllResults($sql);
	}
	
function showAllCampList()
	{
		$sql="select * from itf_summercamp ";
		return $this->dbcon->FetchAllResults($sql);
	}

	function ShowAllCampSearch($txtsearch)
	{
		$sql="select * from itf_summercamp where  name like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function CheckCamp($UsId)
	{
		$sql="select U.* from itf_summercamp U where U.id='".$UsId."'";
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
		$this->dbcon->Modify('itf_summercamp',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	function getAllCampFront($parentid="0")
	{
		$sql="select *  from itf_summercamp where status='1' and parentid='".$parentid."' order by name ";
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