<?php
class Lessonroll
{
        public $name = "itf_attendance";
	public $dbcon;

	function __construct()
	{
			global $itfmysql;
			$this->dbcon=$itfmysql;
	}

	function userLogin($uname,$pass)
	{
		$sql="select * from ".$this->name." where email='".$this->dbcon->EscapeString($uname)."' and password='".md5($this->dbcon->EscapeString($pass))."' and status ='1' ";
		if($DD=$this->dbcon->Query($sql)){
		$_SESSION['FRONTUSER'] = $DD;
			return $DD;
			}
		else
		return '0';
	}

	function adminAdd($datas)
        {
            
            $datas["password"]=md5($datas["password2"]);
                      
          	unset($datas['id']);
		$this->dbcon->Insert($this->name,$datas);
	}

	function ShowAll()
	{
		$sql="select *  from ".$this->name."
		   order by attid DESC";
		return $this->dbcon->FetchAllResults($sql);

	}


	function adminUpdate($datas)
	{
		$condition = array('id'=>$datas['id']);
		unset($datas['id']);
		$this->dbcon->Modify($this->name,$datas,$condition);
	}

	function adminDelete($Id)
	{
		$sql="delete from ".$this->name." where id in(".$Id.")";	
		$this->dbcon->Query($sql);
		return $this->dbcon->querycounter;
	}

	
	
	function CheckUser($UsId)
	{
		$sql="select U.* from itf_users U where U.id='".$UsId."'";
	 	return $this->dbcon->Query($sql);
	}

	  function CheckStudent($UsId)
	{
		$sql="select U.* from itf_child U where U.class_id='".$UsId."'";
		return $this->dbcon->Query($sql);
	}

	//  function Checkattendance($ids)
	//{
	//	$sql="select * from ".$this->name."  where class_id='".$ids."'";
	// 	return $this->dbcon->Query($sql);
	//}


	  function PublishAttendanceStatus($ids)
	{	
		$infos=$this->Checkattendance($ids);
		if($infos['attand_status']=='1')
			$datas=array('attand_status'=>'0');
		else
			$datas=array('attand_status'=>'1');
		
		$condition = array('id'=>$ids);
		$this->dbcon->Modify($this->name,$datas,$condition);
		
		return ($infos['attand_status']=='1')?"0":"1";

	}
        
	function AttendanceUpdate($datas)
        {
            $condition = array('id'=>$datas['id']);
            unset($datas['id']);
            $this->dbcon->Modify($this->name,$datas,$condition);
	}
     
	     
	function ShowAllattendance()
	{
		$sql="select *  from ".$this->name." order by id DESC";
		return $this->dbcon->FetchAllResults($sql);
	}

	
	function ShowAttendancesbyStudent($ids)
        {
                $sql="select A.*,C.name from ".$this->name." A  inner join itf_child C on A.student_id=C.id WHERE student_id='".$ids."'";
                return $this->dbcon->FetchAllResults($sql);
	}

	 function ShowAllAttendanceSearchbyClass($txtsearch,$ids)
	{       
            $data= $this->Checkattendance($ids);
            $sql="select A.*,C.name from ".$this->name." A inner join itf_child C on C.id=A.student_id where  C.name like ( '%".$this->dbcon->EscapeString($txtsearch)."%') and A.class_id='".$ids."'";
            return $this->dbcon->FetchAllResults($sql);
	}

        function ShowAllAttendancebyClass($ids)
	{
            $id= $_SESSION['LoginInfo']['USERID'];
            $data= $this->CheckUser($id);
            $sql="select C.name,A.attand_date,A.attand_status from itf_child C left join ".$this->name." A on A.student_id=C.id where C.class_id='".$ids."'";
            return $this->dbcon->FetchAllResults($sql);
	}

	function Searchattendance($txtsearch)
	{
		$sql="select * from ".$this->name." where  name like ( '%".$this->dbcon->EscapeString($txtsearch)."%')";
		return $this->dbcon->FetchAllResults($sql);
	}

	
        
        
      function Getgroup()
	{
		 $sql="select distinct group_id, title from itf_student_group 
                      INNER JOIN itf_group ON itf_student_group.group_id = itf_group.id where 1=1";
                 
                 
                // if($gid!='' && $gid >0)
               //  {
               //   $sql .=" and itf_student_group.group_id=".$gid;                    
               //  }
                
		return $this->dbcon->FetchAllResults($sql);
	}
        
                
        function Getstudent($gid)
	{		
                $sql="select DISTINCT student_id AS id, name from itf_student_group 
                      INNER JOIN itf_child ON itf_student_group.student_id = itf_child.id where 1=1";
                
                if($gid!='' && $gid >0)
                {
                $sql .=" and itf_student_group.group_id=".$gid;                    
                }
                    
                $child =  $this->dbcon->FetchAllResults($sql);               
                return $child;
                
	}
        
        function Getstudent1($gid,$cid)
	{		//echo $gid;die;
                $sql="select DISTINCT student_id AS id, name from itf_student_group 
                      INNER JOIN itf_child ON itf_student_group.student_id = itf_child.id
                      where itf_child.class_id='".$cid."' and itf_student_group.group_id=".$gid;
                
                
                    
                $child =  $this->dbcon->FetchAllResults($sql);               
                return $child;
                
	}
        
        function checkAttendance($sid, $adate)
	{
		$sql="select id from itf_attandance  where student_id='".$sid."' and STR_TO_DATE(attand_date, '%Y-%m-%d') ='".$adate."'";
	 	$result = mysql_query($sql);
                return mysql_num_rows($result);
	}
        
         function findAttendance($sid, $adate)
	{
		$sql="select id from itf_attandance  where student_id='".$sid."' and STR_TO_DATE(attand_date, '%Y-%m-%d') ='".$adate."'";
	 	$result = mysql_query($sql);                
                return mysql_fetch_array($result);
               
	}
        
        function getTodayAttendance()
        {
                $sql="select * from itf_attandance  where 1=1 and STR_TO_DATE(attand_date, '%Y-%m-%d') ='".date("Y-m-d",time())."'";
	 	return $this->dbcon->FetchAllResults($sql);
        }
        
        
        function funGetSubject($period,$date)
        {
             $sql="select S.* from itf_timetable U INNER JOIN itf_subgroup S on U.subject = S.id where U.period='".$period."' and U.create_date='".$date."'";
	   
               $row =  $this->dbcon->Query($sql);
               return $row['title'];
        }
        
        function attendanceAdd($datas)
        {              
            foreach($datas['student_id'] as $sid){  $flag=0;$attandance = array();  $condition  = array();        
                $attandance['student_id'] = $sid;
                $attandance['attand_date'] = date("Y-m-d H:i:s",time());   
                $attandance['teacher_id'] = $_SESSION['LoginInfo']['USERID'];            
                                                
                if($datas['roll_call'][$sid]== 1){
                $attandance['roll_call'] = 1;
                }
                else
                { $attandance['roll_call'] = 0;$flag=1;}
                
                if($datas['period1'][$sid]== 1){
                $attandance['period1'] = 1;
                }
                else{ $attandance['period1'] = 0;$flag=1;}
                
                if($datas['period2'][$sid]== 1){
                $attandance['period2'] = 1;
                }
                else{ $attandance['period2'] = 0;$flag=1;}
                 
                if($datas['period3'][$sid]== 1){
                $attandance['period3'] = 1;
                }
                else{ $attandance['period3'] = 0;$flag=1;}
                
                if($datas['period4'][$sid]== 1){
                $attandance['period4'] = 1;
                }
                else{ $attandance['period4'] = 0;$flag=1;}
                
                if($datas['period5'][$sid]== 1){
                $attandance['period5'] = 1;
                }
                else{ $attandance['period5'] = 0;$flag=1;}
                
                 if($flag==1){                               
                 if($this->checkAttendance($sid,date("Y-m-d",time()))>0)
                 { 
                    $data= $this->findAttendance($sid,date("Y-m-d",time()));
                 $condition = array('id'=>$data['id']);  
                 
                 //echo $sid;echo "++";echo $this->findAttendance($sid,date("Y-m-d",time()))['id'];
                 $this->dbcon->Modify('itf_attandance',$attandance,$condition);
                 }
                 else
                 {                     
                  $this->dbcon->Insert('itf_attandance',$attandance);                 
                  } 
                
                 }                            
             } 
       }              
       
       function displayAttendanceView($pagename)
       {
          $selgroup = $day = $selperiod = $class_id = $absent = ""; 
          // SELECT * FROM `itf_attandance_single` INNER JOIN `itf_student_group` ON `itf_attandance_single`.student_id = `itf_student_group`.student_id
        
        if(isset($_REQUEST['day'])){$day = $_REQUEST['day']; }
        if(isset($_REQUEST['absent'])){$absent = $_REQUEST['absent']; }
        if(isset($_REQUEST['class_id'])){$class_id = $_REQUEST['class_id']; }       
        if($selgroup != "")
	{
	$sql .= " and `itf_student_group`.group_id = '".$selgroup."'  ";
	}
        if($day != "")
	{
	$sql .= " and STR_TO_DATE(attand_date, '%Y-%m-%d') = '".$day."'  ";
	}
        if($selperiod != "")
	{
	$sql .= " and `itf_attandance_single`.period = '".$selperiod."'  ";
	}
        if($absent != "")
	{
	$sql .= " and `itf_attandance_single`.absent_type = '".$absent."'  ";
	}
        if($class_id != "")
	{
	$sql .= " and `itf_child`.class_id = '".$class_id."'  ";
	} 
       
        echo "<table width='100%' border='0'>";
	echo "<tr><td align='left' >";
	echo "<table width='100%' border='0' cellpadding ='1' cellspacing ='1' align='left'  >";
	echo "<tr>";
        //echo "<td width='15%' valign='top'></td>";
        echo "<td width='15%' class='input-text' valign='top'>";
	echo "Select Class:<select  name='class_id' id='class_id' style='width:130px;' onchange='document.frm1.submit();' >
	<option value=''>-Class-</option>";
            $this->funGetClassByschool(@$class_id);       
	echo "</select>";
	echo "</td>";
        echo "<td width='15%' class='input-text' valign='top'>";
	echo "Select Group:<select  name='selgroup' id='selgroup' style='width:130px;'  >
	<option value=''>--Select Group--</option>";
            $this->funGetGroup2(@$selgroup,@$class_id);       
	echo "</select>";
	echo "</td>";
        echo "<td width='15%' class='input-text' valign='top'>";
	echo "Select Date:<input id='day' name='day' type='text'  value='".@$day."' />";
	echo "</td>";
        echo "<td width='15%' class='input-text' valign='top'>";
	echo "Select Absent Type:<select name='absent' id='absent' style='width:130px;'  >";
	echo "<option value=''>Absent Type</option>";
        echo "<option value='explained'";if(@$absent =='explained'){echo "selected='selected'";}
        echo ">Explained absent</option>";  
        echo "<option value='un_explained'";if(@$absent == 'un_explained'){echo "selected='selected'";}
        echo ">Unexplaned absent</option>";
	echo "</select>";
	echo "</td>";		
	
	
        echo "<td width='5%' valign='bottom'><input name='searchuser' type='submit' id='searchuser' class='button' value='Submit' /></td>";
	echo "</tr>";
	
	
	echo "<tr><td align='right' class='text' colspan='4' ></td></tr>";
	echo "</table>";
	echo "</td></tr>";
		
	echo "<tr><td align = 'left' valign='top' >";
	echo "<table width='100%' id='itffrmlists' align='left' cellpadding='0' cellspacing='0' border='1' bordercolor='#FFFFFF' style='border-collapse:collapse;margin: 1em auto;' ><thead><tr >";
	echo "<th class='table-header' valign='top' ><strong>Absent Student Name</strong></th>";	
	//echo "<td class='table-header' valign='top' >Subject</td></tr></thead>";
	echo "<tbody >";          
        
        if(isset($_REQUEST['day'])){$day = $_REQUEST['day']; }
        if(isset($_REQUEST['selperiod'])){$selperiod = $_REQUEST['selperiod']; }
        if(isset($_REQUEST['absent'])){$absent = $_REQUEST['absent']; }
        if(isset($_REQUEST['selgroup'])){$selgroup = $_REQUEST['selgroup']; }  
        
        
        
        $InfoData1= $this->dbcon->FetchAllResults($sql);
        $urlpath=CreateLinkAdmin(array($pagename,'actions'=>"display",'day'=>$day,'selperiod'=>$selperiod,'absent'=>$absent,'selgroup'=>$selgroup))."&";
        $pagingobj=new ITFPagination($urlpath);
        $InfoData=$pagingobj->setPaginateData($InfoData1);
        if($selgroup == "")
        {
        echo "<tr class='lines'>";	
		echo "<td class='marked' align='center'  >".strtoupper('Please Select The Group First')."</td>";
		echo "</tr>";
        }
        elseif(count($InfoData) == 0)
	{
		echo "<tr class='lines'>";	
		echo "<td class='marked' align='center'  >".strtoupper('sorry currently there no student in the list')."</td>";
		echo "</tr>";
	}
	else
	{
           foreach ($InfoData as $rows) { //echo "<pre>"; print_r($rows);echo "</pre>";
	        echo "<tr class='lines'>";	
                echo "<td  align='left' valign='top' >".$this->funGetStudentName($rows['student_id'])."</td>";
		//echo "<td  align='left' valign='top' >".$this->funGetStudentId($rows['student_id'])."</td>";
               // echo "<td  align='left' valign='top' >".$this->funGetExam($rows['test_name_id'])."</td>";
		//echo "<td  align='left' valign='top' ><a title='Student Exam Detail' href='itfmain.php?itfpage=".$pagename."&actions=studentmarkbook&sid=".$rows['student_id']."' >Exam Detail</a></td>";
                echo "</tr>";
           }  
           echo "<tr><td colspan='1'>".$pagingobj->Pages()."</td></tr>";
           
       }
        
        
       echo "</tbody></table></td></tr></table></td></tr></table>";  
           
       }
       
     function funGetStudentName($sid)
        {   $sql = "select name from itf_child where id =".$sid;
            $result = mysql_query($sql);
            $row = mysql_fetch_assoc($result);
            return $row['name'];            
        }
       
       
         function funGetGroup($gid)
        {
        $query = "SELECT * from itf_group where 1=1 ";
	$result = mysql_query($query) or die(mysql_error());	
	while($rows = mysql_fetch_array($result))
	{
	$value = $rows['title'];//."-".$rows['COMPANY_NAME'];
	$key = $rows['id'];
	echo "<option value='".$key."' ";
	if($key == @$gid){ echo " selected";}
	echo ">".$value."</option>";
	}	  
        
        }
       
        function funGetGroup2($gid,$cid)
        {
        if($cid!=''){
        $query = "SELECT * from itf_group where class_id =".$cid;
	$result = mysql_query($query) or die(mysql_error());	
	while($rows = mysql_fetch_array($result))
	{
	$value = $rows['title'];//."-".$rows['COMPANY_NAME'];
	$key = $rows['id'];
	echo "<option value='".$key."' ";
	if($key == @$gid){ echo " selected";}
	echo ">".$value."</option>";
        }	}  
        
        }
       
       function funGetClass($cid)
        {
        $query = "SELECT * from itf_class where 1=1 ";
	$result = mysql_query($query) or die(mysql_error());	
	while($rows = mysql_fetch_array($result))
	{
	$value = $rows['title'];//."-".$rows['COMPANY_NAME'];
	$key = $rows['id'];
	echo "<option value='".$key."' ";
	if($key == @$cid){ echo " selected";}
	echo ">".$value."</option>";
	}	  
        
        }
       
       function funGetClassByschool($cid)
        {
           $data=$this->CheckUser($_SESSION['LoginInfo']['USERID']);
          
           
           
           //echo"<pre>";print_r($data);die;
        $query = "SELECT * from itf_class";
	$result = mysql_query($query) or die(mysql_error());	
	while($rows = mysql_fetch_array($result))
	{
	$value = $rows['title'];//."-".$rows['COMPANY_NAME'];
	$key = $rows['id'];
	echo "<option value='".$key."' ";
	if($key == @$cid){ echo " selected";}
	echo ">".$value."</option>";
	}	  
        
        }
       
       
       function FindStudentAttendance($sid,$pid,$attand_date)
     {
     $sql="select * from itf_attandance_single WHERE student_id='".$sid."' and period ='".$pid."' and STR_TO_DATE(attand_date, '%Y-%m-%d') ='".$attand_date."' and status=1";
	
        $result = mysql_query($sql);
        if(mysql_num_rows($result))
        {
            return "Absent";
        }
        else
        {   return "Present";
        }
        
        
        }
        
         function FindStudentAttendanceReason($sid,$pid,$attand_date)
     {
     $sql="select * from itf_attandance_single WHERE student_id='".$sid."' and period ='".$pid."' and STR_TO_DATE(attand_date, '%Y-%m-%d') ='".$attand_date."' and status=1";
	
        $result = $this->dbcon->Query($sql);
        
            return $result;
        }
        

	//Function for change status	

	function PublishBlock($ids)
	{	
		$infos=$this->Checkattendance($ids);
		if($infos['status']=='1')
			$datas=array('status'=>'0');
		else
			$datas=array('status'=>'1');
		$condition = array('id'=>$ids);
		$this->dbcon->Modify($this->name,$datas,$condition);
		return ($infos['status']=='1')?"0":"1";
	}
         
        
        function funGetGroup1()
        {
        $query = "SELECT * from itf_group where 1=1 ";
	$result = mysql_query($query) or die(mysql_error());
        $gid = @$_REQUEST['group_id'];
	while($rows = mysql_fetch_array($result))
	{
	$value = $rows['title'];//."-".$rows['COMPANY_NAME'];
	$key = $rows['id'];
	echo "<option value='".$key."' ";
	if($key == $gid){ echo " selected";}
	echo ">".$value."</option>";
	}	  
        
        }

        function checkattend($UsId,$date)
	{
		$sql="select * from itf_attandance_single WHERE teacher_id='".$_REQUEST['teacher_id']."' and student_id='".$UsId."' and period='".$_REQUEST['period']."' and STR_TO_DATE(attand_date, '%Y-%m-%d') ='".$date."'"; 
	 	return $this->dbcon->Query($sql);
	}
        
}
?>