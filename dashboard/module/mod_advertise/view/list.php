<?php 
if(isset($_POST['itf_datasid'],$_POST['itfactions']))
{
	$userids=isset($_POST['itf_datasid'])?$_POST['itf_datasid']:"0";
	$acts=$_POST['itfactions'];
	$ids=implode(',',$_POST['itf_datasid']);
	if($acts=='delete')
	{
		$categoryobj->Advertise_deleteAdmin($ids);
		flash("Advertise is successfully Deleted");
	}
	redirectUrl("itfmain.php?itfpage=".$currentpagenames);
}
$perpage = 10;

$urlpath=CreateLinkAdmin(array($currentpagenames))."&";
$InfoData1 = $categoryobj->showAllAdvertise();
$pagingobj=new ITFPagination($urlpath,$perpage);
$InfoData=$pagingobj->setPaginateData($InfoData1);
//echo "<pre>"; print_r($InfoData); die;
?>
<div class="full_w">
  <div class="h_title"><?php echo $pagetitle;?></div>
 <div class="entry top_buttons">
        <a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'add')); ?>" class="button add"><span>Add New <?php echo $pagetitle; ?></span></a>
     
        <a onclick="return itfsubmitfrm('delete','itffrmlists');" class="button cancel"><span>Delete</span></a>
    
    </div>			<!-- Box -->
				
				<div class="table">
						<form id="itffrmlists" name="itffrmlists" method="post" action="">
<input type="hidden" name="itfactions" id="itfactions" value="" />
<input type="hidden" name="itf_status" id="itf_status" value="" />
  <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
    <tr>
      	<th width="3%" align="center" ><input name="selectalls" id="selectalls" type="checkbox" value="0" /></th>
      	<th width="37%" align="left"  >Advertise Name </th>
  		
  		<th width="17%" align="center"  >Image</th>
  		<th width="10%" align="center"  >Status</th>
      	<th width="9%"  align="center"  >Action</th>
	  </tr>
	<?php
	foreach($InfoData as $k=>$itfdata)
	{
	?>
    <tr class="<?php echo ($k%2==0)?"rowsfirst":"rowssec";?>" >
     <td align="left"><input name="itf_datasid[]" type="checkbox" value="<?php echo $itfdata['id']; ?>" class="itflistdatas" /></td>
	   <td  align="left" ><?php echo $itfdata['name']; ?></td>   
      
       <td align="left" class="imageview"><img src="<?php echo PUBLICPATH."banner/".$itfdata['imagename']; ?>" width="25" height="25"  /></td>
       <td align="center"><a href="#itf" class="activations" rel="<?php echo $itfdata['id']; ?>" rev="frmadvertise"><img src="imgs/<?php echo $itfdata['status']; ?>.png" /></a></td>
      <td align="center"><a href="<?php echo CreateLinkAdmin(array($currentpagenames,'actions'=>'edit','id'=>$itfdata['id'])); ?>" title="Edit Category " alt="Edit Category"><img src="imgs/itf_edit.png" border="0" /></a> </td>
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
    