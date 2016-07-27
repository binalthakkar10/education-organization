<?php 
 $pagename = $currentpagenames;
//echo "list";die;
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css"/>
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<script>
$(function() {
$("#day").datepicker({
           dateFormat:"yy-m-d"
        });
       
});
</script>
<div class="box">
<div class="box-head">
    <h2 class="left"><?php echo "Attendance Display"; ?></h2>
    <div class="clear"></div>
    <div class="">						
        <form id="frm1" name="frm1" method="post" action="<?php echo "itfmain.php?itfpage=".$currentpagenames."&actions=display"; ?>">
      
       <?php $objmodule->displayAttendanceView($pagename); ?>
        </form>	
    </div>
</div>
	<!-- Table -->

</div>