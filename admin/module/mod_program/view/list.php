<?php 
if(isset($_POST['itf_datasid'],$_POST['itfactions']))
{
	$userids=isset($_POST['itf_datasid'])?$_POST['itf_datasid']:"0";
	$acts=$_POST['itfactions'];
	$ids=implode(',',$_POST['itf_datasid']);
	if($acts=='delete')
	{
		$objmodule->adminDelete($ids);
		flash("Program is successfully Deleted");
	}

	redirectUrl("itfmain.php?itfpage=".$currentpagenames);
}
$perpage = 10;
$urlpath=CreateLinkAdmin(array($currentpagenames))."&";
$InfoData1 = $objmodule->showAll();
$pagingobj=new ITFPagination($urlpath,$perpage);
$InfoData=$pagingobj->setPaginateData($InfoData1);

?>
<div class="topcontroller"> 
<a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'add')); ?>" class="itfadd-button"><span>Add new <?php echo $pagetitle; ?></span></a> 
<a onclick="return itfsubmitfrm('delete','itffrmlists');" class="itfdel-button"><span>Delete</span></a>
</div>
				<!-- Box -->
				<div class="box">
					<!-- Box Head -->
					<div class="box-head">
						<h2 class="left"><?php echo $pagetitle; ?></h2>
				</div>
				
				
				<div class="table">
						<form id="itffrmlists" name="itffrmlists" method="post" action="">
<input type="hidden" name="itfactions" id="itfactions" value="" />
<input type="hidden" name="itf_status" id="itf_status" value="" />
  <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
    <tr>
      <th width="5%" align="center" ><input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
      <th width="37%" align="left"  >Title </th>
   	  <th width="40%" align="center"  >Department</th>
   	  <th width="9%" align="center"  >Status</th>
      <th width="9%"  align="center"  >Action</th>
   </tr>
	<?php
	//echo "<pre>"; print_r($InfoData); die;
	foreach($InfoData as $k=>$itfdata)
	{
	?>
    <tr class="<?php echo ($k%2==0)?"rowsfirst":"rowssec";?>" >
      <td align="left"><input name="itf_datasid[]" type="checkbox" value="<?php echo $itfdata['id']; ?>" class="itflistdatas" /></td>
	   <td  align="left" ><?php echo $itfdata['title']; ?></td>
   
       <td align="left"><?php echo $itfdata['department']; ?></td>
       <td align="center"><a href="#itf" class="activations" rel="<?php echo $itfdata['id']; ?>" rev="frmfaculty"><img src="imgs/<?php echo $itfdata['status']; ?>.png" /></a></td>
   <td align="center">
   <a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'edit','id'=>$itfdata['id'])); ?>" title="Edit Category " alt="Edit Category"><img src="imgs/itf_edit.png" border="0" /></a>   </td>
    </tr>
	<?php
	}
	?>
</table>
 </form>
		<!-- Pagging -->
		<div class="pagging">
		<div class="right">
		<?php echo $pagingobj->Pages(); ?>
		</div>
		</div>
		<!-- End Pagging -->
	</div>
</div>