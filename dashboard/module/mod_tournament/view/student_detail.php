<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$objSt=new Student;
$ItfInfoData = $objSt->getTournamentStudentDetails($ids);
//echo '<pre>';print_r($ItfInfoData);
$tounamentObj = new Tournament;


?>

   
<div class="full_w">
    <!-- Page Heading -->

    <!-- Page Heading -->
 <div class="h_title"><?php
        //echo ($ids == "") ? "Add New " : "Edit ";
        echo 'Student Detail';
        ?></div>
    <form action="" method="post" name="itffrminput" id="itffrminput" >
    

        <div class="element">
           <label> First Name -> &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['first_name']?></label>
            
        </div>

        <div class="element">
             <label>Last Name -> &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['last_name']?></label>
        </div>
       
        <div class="element">
            <label>Email ->    &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['email']?></label>
        </div>
 <div class="element">
            <label>Phone ->     &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['phone']?></label>
        </div>
<div class="element">
            <label>Date of Birth -> &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['dob']?></label>
        </div>
<div class="element">
            <label>Did you attend tournaments hosted by Bay Area Debate Club before -> &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['attend_tournament']=='1'?'Yes':'No';?></label>
        </div>

      

        <div class="element">
            <label>Tournament Topics Choices -> &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['topics_choices'];?></label>
           
        </div>

       <div class="element">
            <label>Payment Status -> &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['reg_status']=='1'?'Success':'Failure';?></label>
           
        </div>
  
<div class="element">
            <label> Status -> &nbsp;&nbsp;&nbsp;    <?php echo $ItfInfoData['status']=='1'?'Active':'Inactive';?></label>
           
        </div>
    </form>
    <!-- End Form -->
</div>
