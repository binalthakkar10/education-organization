<?php 

if(count($_POST))
{ 
$objmodule->attendanceAdd($_POST);
flash("Attendance added sucessfully");  
}
$get_current= $objmodule->getTodayAttendance();

$group = $objmodule->Getgroup(); 
//echo "<pre>"; print_r($get_current); echo "</pre>";
?>


 <div class="full_content">  <form id="form1" name="form1" method="post" action="">
     <a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'insert'));?>">Show All</a> <br>
   <?php          
       for($i=0;$i<count($group);$i++)
       {  ?>            
           <a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'insert','gid'=>$group[$i]['group_id']));?>"><?php echo $group[$i]['title']; ?></a> <br>
          <?php 
       }     
    ?>
  
    <table width="100%" id="rounded-corner" summary="2007 Major IT Companies' Profit">
      
    <thead>
    	<tr>
            <th width="17%" class="rounded-company" scope="col">Student Name</th>
            <th width="17%" class="rounded" scope="col">Morning roll call</th>
            <th width="17%" class="rounded" scope="col">Period 1</th>
            <th width="17%" class="rounded" scope="col">Period 2</th>
            <th width="17%" class="rounded" scope="col">Period 3</th>
            <th width="17%" class="rounded" scope="col">Period 4</th>
             <th width="17%" class="rounded" scope="col">Period 5</th>
           <!-- <th width="17%" class="rounded" scope="col">&nbsp;</th>-->
            </tr>
    </thead>
        
    <tbody>
        <?php @$gid = @$_REQUEST['gid']; 
        $student = $objmodule->Getstudent($gid);
        
        foreach ($student as $student){//print_r($student);
        ?>
        
    	<tr>
            <td width="17%"><div class="table_student_name"><?php echo ucfirst($student['name']); ?>
                <input name="student_id[<?php echo $student['id']; ?>]" type="hidden"  id="student_id" size="35" value="<?php echo $student['id']; ?>" />
               
                </div></td>
            <td width="17%">
              <label for="select"></label>
              <select name="roll_call[<?php echo $student['id']; ?>]" id="select">
                <?php $flag=1; for($i=0 ;$i< count($get_current);$i++){ ?>  
                <?php if($get_current[$i]['student_id']== $student['id']){ $flag=0;?>                        
                  <option value ="1" <?php if($get_current[$i]['roll_call']== 1){echo 'selected="selected"';} ?> >Present</option>
                  <option value ="0" <?php if($get_current[$i]['roll_call']== 0){echo 'selected="selected"';} ?>>Absent</option>
                   <?php  }}
                   if($flag==1){echo "<option value ='1'>Present</option><option value ='0'>Absent</option>";}  ?>                
              </select>
            <td width="17%"><select name="period1[<?php echo $student['id']; ?>]" id="select1">
              <?php $flag=1; for($i=0 ;$i< count($get_current);$i++){ ?>  
                <?php if($get_current[$i]['student_id']== $student['id']){ $flag=0;?>                        
                  <option value ="1" <?php if($get_current[$i]['period1']== 1){echo 'selected="selected"';} ?> >Present</option>
                  <option value ="0" <?php if($get_current[$i]['period1']== 0){echo 'selected="selected"';} ?>>Absent</option>
                   <?php  }}
                   if($flag==1){echo "<option value ='1'>Present</option><option value ='0'>Absent</option>";}  ?>             
            </select></td>
            <td width="17%"><select name="period2[<?php echo $student['id']; ?>]" id="select2">
             <?php $flag=1; for($i=0 ;$i< count($get_current);$i++){ ?>  
                <?php if($get_current[$i]['student_id']== $student['id']){ $flag=0;?>                        
                  <option value ="1" <?php if($get_current[$i]['period2']== 1){echo 'selected="selected"';} ?> >Present</option>
                  <option value ="0" <?php if($get_current[$i]['period2']== 0){echo 'selected="selected"';} ?>>Absent</option>
                   <?php  }}
                   if($flag==1){echo "<option value ='1'>Present</option><option value ='0'>Absent</option>";}  ?>             
            </select></td>
            <td width="17%"><select name="period3[<?php echo $student['id']; ?>]" id="select3">
              <?php $flag=1; for($i=0 ;$i< count($get_current);$i++){ ?>  
                <?php if($get_current[$i]['student_id']== $student['id']){ $flag=0;?>                        
                  <option value ="1" <?php if($get_current[$i]['period3']== 1){echo 'selected="selected"';} ?> >Present</option>
                  <option value ="0" <?php if($get_current[$i]['period3']== 0){echo 'selected="selected"';} ?>>Absent</option>
                   <?php  }}
                   if($flag==1){echo "<option value ='1'>Present</option><option value ='0'>Absent</option>";}  ?>             
            </select></td>
            <td width="17%"><select name="period4[<?php echo $student['id']; ?>]" id="select4">
            <?php $flag=1; for($i=0 ;$i< count($get_current);$i++){ ?>  
                <?php if($get_current[$i]['student_id']== $student['id']){ $flag=0;?>                        
                  <option value ="1" <?php if($get_current[$i]['period4']== 1){echo 'selected="selected"';} ?> >Present</option>
                  <option value ="0" <?php if($get_current[$i]['period4']== 0){echo 'selected="selected"';} ?>>Absent</option>
                   <?php  }}
                   if($flag==1){echo "<option value ='1'>Present</option><option value ='0'>Absent</option>";}  ?>             
            </select></td>
            <td width="17%"><select name="period5[<?php echo $student['id']; ?>]" id="select5">
             <?php $flag=1; for($i=0 ;$i< count($get_current);$i++){ ?>  
                <?php if($get_current[$i]['student_id']== $student['id']){ $flag=0;?>                        
                  <option value ="1" <?php if($get_current[$i]['period5']== 1){echo 'selected="selected"';} ?> >Present</option>
                  <option value ="0" <?php if($get_current[$i]['period5']== 0){echo 'selected="selected"';} ?>>Absent</option>
                   <?php  }}
                   if($flag==1){echo "<option value ='1'>Present</option><option value ='0'>Absent</option>";}  ?>             
            </select></td>
           <!-- <td width="17%"><a href="#"><img src="images/go.png" width="26" height="27" /></a></td>-->
            </tr>
            
        <?php }?> 
            
    	</tbody>         

</table>
	<div><!--<a href="#" class="save">Submit to Admin</a>-->
       <input type="submit" class="button" value="submit" />
        </div>
    
       </form>
    </div><!-- end of right content-->
 





