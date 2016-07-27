<?php 
if(isset($_REQUEST['group_id'])){$gid =@$_REQUEST['group_id'];}
else{$gid=0;}
if(isset($_REQUEST['class_id'])){$cid =@$_REQUEST['class_id'];
if(@$_REQUEST['group_id']==''){$gid=0;}
}
else{$cid=0;}
?>
<link href="css/jquery-ui-1.8rc3.custom.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery-ui-1.8rc3.custom.min.js"></script>
<script>
function attendance(sid,tid,pid)
{   
   $.post("itf_ajax/index.php?attendance=2", {sid: ""+sid+"",tid: ""+tid+"",pid: ""+pid+""}, function(data)
   {
		if(data.length >0)
		{	
			$("#event_edit_container").empty();
		  var c = data;
			$("#event_edit_container").html(c);
		}
	});

var $dialogContent = $("#event_edit_container");
$dialogContent.dialog({
            modal: true,
            title: "Attendance",
			width: 400,
			close: function() {
            $dialogContent.dialog("destroy");
            $dialogContent.hide();
			 }
		}).show();
}

function addbyvalue()
{
var absent=$("#absent").val();
var reason=$("#reason").val();
var studentid=$("#student_id").val();
var teacherid=$("#teacher_id").val();
var period=$("#period").val();

$.post("itf_ajax/index.php?viewattendance=2", {absent: ""+absent+"",reason: ""+reason+"",student_id: ""+studentid+"",teacher_id: ""+teacherid+"",period: ""+period+""}, function(data)
	{   
		if(data.length >0)
		{	
			
		  var c = data;//alert(c);
		 location.href='itfmain.php?itfpage=lessonroll&actions=morning_roll&group_id=<?php echo $_REQUEST['group_id']; ?>';
		}
	});
}


</script>
<div id="event_edit_container"><input type="hidden" /></div>
 <div class="full_content">  
     <form id="form1" name="form1" method="post" action="<?php echo "itfmain.php?itfpage=".$currentpagenames."&actions=morning_roll"; ?>">
                
     <select name='class_id' id='class_id' style='width:100px;' onchange='document.form1.submit();' >
	<option value=''>--Select Class--</option>
        <?php echo $objmodule->funGetClass($cid); ?>
     </select>  
           
      <select name='group_id' id='group_id' style='width:150px;' onchange='document.form1.submit();' >
	<option value=''>--Select Group--</option>
        <?php echo $objmodule->funGetGroup2($gid,$cid); ?>
     </select>  
         
  
    <table width="100%" id="rounded-corner" summary="2007 Major IT Companies' Profit">
      
    <thead>
    	<tr>
            <th width="17%" class="rounded-company" scope="col">Student Name</th>
            <th width="17%" class="rounded" scope="col">Morning roll call</th>
            <th width="17%" class="rounded" scope="col">Period 1 (<?php echo $objmodule->funGetSubject('P1', date("Y-m-d")); ?>)</th>
            <th width="17%" class="rounded" scope="col">Period 2 (<?php echo $objmodule->funGetSubject('P2', date("Y-m-d")); ?>)</th>
            <th width="17%" class="rounded" scope="col">Period 3 (<?php echo $objmodule->funGetSubject('P3', date("Y-m-d")); ?>)</th>
            <th width="17%" class="rounded" scope="col">Period 4 (<?php echo $objmodule->funGetSubject('P4', date("Y-m-d")); ?>)</th>
             <th width="17%" class="rounded" scope="col">Period 5 (<?php echo $objmodule->funGetSubject('P5', date("Y-m-d")); ?>)</th>
           <!-- <th width="17%" class="rounded" scope="col">&nbsp;</th>-->
            </tr>
    </thead>
        
    <tbody>
        <?php  
        $student = $objmodule->Getstudent1($gid,$cid);
        if(isset($_REQUEST['group_id'])){
        foreach ($student as $student){//print_r($student);
        ?>
        
    	<tr>
            <td width="17%"><div class="table_student_name"><?php echo ucfirst($student['name']); ?>
                <input name="student_id[<?php echo $student['id']; ?>]" type="hidden"  id="student_id1" size="35" value="<?php echo $student['id']; ?>" />
            </div></td>
            <td width="17%"><a href="#" onclick="attendance('<?php echo $student['id']; ?>','<?php echo $_SESSION['LoginInfo']['USERID']; ?>','P0')"><?php echo $objmodule->FindStudentAttendance($student['id'],'P0'); ?></a>  </td>
            <td width="17%"> <a href="#" onclick="attendance('<?php echo $student['id']; ?>','<?php echo $_SESSION['LoginInfo']['USERID']; ?>','p1')"><?php echo $objmodule->FindStudentAttendance($student['id'],'P1'); ?></a> </td>
            <td width="17%"> <a href="#" onclick="attendance('<?php echo $student['id']; ?>','<?php echo $_SESSION['LoginInfo']['USERID']; ?>','P2')"><?php echo $objmodule->FindStudentAttendance($student['id'],'P2'); ?></a> </td> 
            <td width="17%"> <a href="#" onclick="attendance('<?php echo $student['id']; ?>','<?php echo $_SESSION['LoginInfo']['USERID']; ?>','P3')"><?php echo $objmodule->FindStudentAttendance($student['id'],'P3'); ?></a> </td>
            <td width="17%"> <a href="#" onclick="attendance('<?php echo $student['id']; ?>','<?php echo $_SESSION['LoginInfo']['USERID']; ?>','P4')"><?php echo $objmodule->FindStudentAttendance($student['id'],'P4'); ?></a> </td>
            <td width="17%"> <a href="#" onclick="attendance('<?php echo $student['id']; ?>','<?php echo $_SESSION['LoginInfo']['USERID']; ?>','P5')"><?php echo $objmodule->FindStudentAttendance($student['id'],'P5'); ?></a> </td>          
        </tr>
            
        <?php }}
        else
        {  echo "<tr><td colspan='7'>Please select the Group First</td></tr>";
            
        }
        
        
        
        ?> 
            
    	</tbody>         

</table>
	<div><!--<a href="#" class="save">Submit to Admin</a>-->
      
        </div>
    
       </form>
    </div><!-- end of right content-->
 

