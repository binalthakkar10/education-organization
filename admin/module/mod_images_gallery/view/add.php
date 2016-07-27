<?php

echo "image gallery";
if(isset($_POST['id']))
{
	$userids=$_POST['id'];
	if(!empty($_POST['id']))
	{
		$objbanner->admin_updateGallery($_POST);
		flash("Banner is successfully Updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
		$objbanner->admin_addGallery($_POST);
		flash("Banner is successfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
}

echo $ids=isset($_GET['id'])?$_GET['id']:'';
$InfoData = $objbanner->CheckBanner($ids);
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#itffrminput").validate({
	rules: {
			name: "required",
                                                    bannerimage:{accept:"jpg|png|gif|jpeg"},
		},
		messages: {
			name: "Please enter Banner name"
			}
	});
});
</script>
<div class="full_w">
	<!-- Page Heading -->
      <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
      <!-- Page Heading -->
      <form name="itffrminput" id="itffrminput" method="post" action="" enctype="multipart/form-data">
      <input name="id" type="hidden" id="id" value="<?php echo !empty($InfoData['id'])?$InfoData['id']:''; ?>" />
<!--        <div class="element">
           <label>Images Name <span>*</span></label>
           <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name'])?$InfoData['name']:'' ?>" />
       </div>-->

<div class="element">
           <label>Image Name <span>*</span></label>
           <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name'])?$InfoData['name']:'' ?>" />
       </div>     


<div class="element">
           <label>Image Gallery <span>*</span></label>
           <div id="FileUpload">
                <input type="file" size="24" id="bannerimage" name="bannerimage" class="BrowserHidden" onchange="getElementById('tmp_bannerimage').value = getElementById('bannerimage').value;" />
                <div id="BrowserVisible"><input type="text" id="tmp_bannerimage" class="FileField" /></div>
                  <?php if($InfoData['imagename']){ ?>
                         <div class="display"><img src="<?php echo PUBLICPATH."/banner/".$InfoData['imagename']; ?>" height="40" width="40"/></div>
                <?php } ?>
           </div>
       </div>

<div class="element">
           <label> Images Description <span>*</span></label>
           <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name'])?$InfoData['name']:'' ?>" />
       </div>-->
      
      <div class="entry">
         
        <button type="submit">Submit</button>
        <button type="button" onclick="history.back()">Back</button>
    </div>
      

</form>		
</div>