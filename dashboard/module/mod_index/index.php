<?php $pagetitle="Home"; 
	$object = new User();	
	$userdata = $object->GetDashboardUser(); 
	
	?>
<table width="100%">
<tr>



<td align="center">Welcome to <?php echo $userdata[0]['org_name'];?> organization</td>



</tr>
</table>

<?php
	
	
	

?>