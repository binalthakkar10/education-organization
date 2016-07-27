<script type="text/javascript">
$(document).ready(function() {
	$("#itffrminput").validate({
	rules: {
			catname: "required",
            image:{accept:"jpg|png|gif|jpeg"}
		},
		messages: {
			catname: "Please enter category name"
		}
	});
});
</script>
<?php
if(isset($_POST['id']))
{
	$userids=$_POST['id'];
	if(!empty($_POST['id']))
	{
		$categoryobj->admin_updateCategory($_POST);
		flash("Category is successfully Updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
		$categoryobj->admin_addCategory($_POST);
		flash("Category is successfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
}

$ids=isset($_GET['id'])?$_GET['id']:'';
$ItfInfoData = $categoryobj->CheckCategory($ids);
$categories = $categoryobj->showCategoriesList(0);

//echo"<pre>"; print_r($categories); die;
?>

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id'])?$ItfInfoData['id']:'' ?>" />

        <div class="element">
            <label>Parent<span class="blue">(optional)</span></label>
            <select name="parent" class="err">
                <option value="0">-- select category --</option>
                <?php foreach($categories as $key=>$cat) {?>
                    <option value="<?php echo $key ?>" <?php if($key == $ItfInfoData["parent"]){ echo"selected";} ?>><?php echo $cat; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="element">
            <label>Category Name<span class="red">(required)</span></label>
            <input class="text" name="catname" type="text"  id="catname" size="35" value="<?php echo isset($ItfInfoData['catname'])?$ItfInfoData['catname']:'' ?>" />
        </div>

        <div class="element">
            <label>Category Image</label>
            <input class="text" name="image" type="file"  id="image" size="35" />
            <?php if($ItfInfoData['image']){ ?>
                <div class="display"><img src="<?php echo PUBLICPATH."/categories/".$ItfInfoData['image']; ?>" height="40" width="40"/></div>
            <?php } ?>
        </div>

        <!-- Form Buttons -->
        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>