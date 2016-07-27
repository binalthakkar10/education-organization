<?php
class TimeTable 
{
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	

	function adminAdd($datas)
	{
		unset($datas['id']);
		$this->dbcon->Insert('itf_timetable',$datas);

	}

	function adminUpdate($datas)
	{
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_timetable',$datas,$condition);
	}

	function adminDelete($Id)
	{
		$sql="delete from itf_timetable where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAll()
	{
		$sql="select * from itf_timetable";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function getActiveData()
	{
		$sql="select * from itf_timetable where status='1' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}

	function searchAll($txtsearch)
	{
		$sql="select * from itf_timetable where  title like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function checkData($UsId)
	{
		$sql="select U.* from itf_timetable U where U.id='".$UsId."'";
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
		$this->dbcon->Modify('itf_timetable',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	
        function getActiveLevel($monthyear)
	{
            $sql="select T.id,T.classname,T.class_date,T.start_time,T.duration,S.name,S.subject_code from itf_timetable T left join itf_subject S on T.subject_id=S.id  where  T.status='1'  order by T.class_date asc";
            return $this->dbcon->FetchAllResults($sql);
	}
}
?>