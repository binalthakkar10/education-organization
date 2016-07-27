<?php 

if(count($_POST))
{ 
$objmodule->attendanceAdd($_POST);
flash("Attendance added sucessfully");  
}

$group = $objmodule->Getgroup(); 
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
                <option>Present</option>
                <option>Absent</option>
              </select>
            <td width="17%"><select name="period1[<?php echo $student['id']; ?>]" id="select1">
              <option>Present</option>
              <option>Absent</option>
            </select></td>
            <td width="17%"><select name="period2[<?php echo $student['id']; ?>]" id="select2">
              <option>Present</option>
              <option>Absent</option>
            </select></td>
            <td width="17%"><select name="period3[<?php echo $student['id']; ?>]" id="select3">
              <option>Present</option>
              <option>Absent</option>
            </select></td>
            <td width="17%"><select name="period4[<?php echo $student['id']; ?>]" id="select4">
              <option>Present</option>
              <option>Absent</option>
            </select></td>
            <td width="17%"><select name="period5[<?php echo $student['id']; ?>]" id="select5">
              <option>Present</option>
              <option>Absent</option>
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
 





