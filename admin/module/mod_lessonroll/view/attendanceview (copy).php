<?php 

if(count($_POST))
{ 
$objmodule->attendanceAdd($_POST);
flash("Attendance added sucessfully");  
}

$group = $objmodule->Getgroup(); 
?>


 <div class="full_content">  <form id="form1" name="form1" method="post" action="">
         
         <a href="#">Absent</a>&nbsp;&nbsp;||&nbsp;&nbsp;<a href="#">Present</a><br>
         
     <a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'display'));?>">Show All</a> <br>
   <?php          
       for($i=0;$i<count($group);$i++)
       {  ?>            
           <a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'display','gid'=>$group[$i]['group_id']));?>"><?php echo $group[$i]['title']; ?></a> <br>
          <?php 
       }     
    ?>
  
   
    
      <div class="contact_box">
    	<div class="view_box">  
          
    	<div class="commnet_box_right"> 
        
        
         <?php @$gid = @$_REQUEST['gid']; 
        $student = $objmodule->Getstudent($gid);
        
        foreach ($student as $student){//print_r($student);
        ?>
        
        <?php echo ucfirst($student['name']); ?><br>
        
        <?php }?> 
        
        
        
        
        
        
        
        </div>
      	<div class="commnet_box_left"> <input type="submit" name="Send" value="Send Notification"></div>
        </div>  
    <div>
      
        </div>
    
   </form>
    </div><!-- end of right content-->
 





