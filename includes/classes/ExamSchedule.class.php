<?php
class ExamSchedule 
{
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
	

	function adminAdd($datas)
	{
		unset($datas['id']);
		$this->dbcon->Insert('itf_exam_schedule',$datas);

	}

	function adminUpdate($datas)
	{
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify('itf_exam_schedule',$datas,$condition);
	}

	function adminDelete($Id)
	{
		$sql="delete from itf_exam_schedule where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}
	
	function showAll()
	{
		$sql="select * from itf_exam_schedule";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function getActiveData()
	{
		$sql="select * from itf_exam_schedule where status='1' order by title asc";
		return $this->dbcon->FetchAllResults($sql);
	}

	function searchAll($txtsearch)
	{
		$sql="select * from itf_exam_schedule where  title like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}
	
	function checkData($UsId)
	{
		$sql="select U.* from itf_exam_schedule U where U.id='".$UsId."'";
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
		$this->dbcon->Modify('itf_exam_schedule',$datas,$condition);
		
		return ($infos['status']=='1')?"0":"1";

	}

	//front end============================================================
	
        function getActiveExamSchedule($studentid=0,$year=0)
	{
            $sql="select ES.id,ES.exam_date,ES.exam_time,ES.venue,S.subject_code,S.name as subject_name 
                from itf_exam_schedule ES 
                inner join itf_subject S on ES.subject_id=S.id 
                inner join itf_student_enrollement SE on SE.course_info_id=S.course_info_id
                where  ES.status='1' and SE.stdudent_id='".$studentid."' and SE.session_year ='".$year."' 
                order by ES.exam_date asc";
            
            return $this->dbcon->FetchAllResults($sql);
	}
}
?>