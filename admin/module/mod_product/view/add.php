<?php
if(isset($_POST['id']))
{
       
        if(isset($_FILES['image']['name'][0]) and !empty($_FILES['image']['name'][0]))
        {
            $uploadObj = new ITFUpload();
            
            $extention = 'jpg|jpeg|png|gif';
            $res = $uploadObj->validate($_FILES["image"], $extention);
            if($res === false){
                flash("Files must be jpg|png|gif","2");
                redirectUrl("itfmain.php?itfpage=".$currentpagenames."&actions=add");
            }
        }
        
	if(!empty($_POST['id']))
	{
		$obj->admin_update($_POST);
		flash("Product is succesfully updated");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
	else
	{
		$obj->admin_add($_POST);
		flash("Product is succesfully added");
		redirectUrl("itfmain.php?itfpage=".$currentpagenames);
	}
}
$ids=isset($_GET['id'])?$_GET['id']:'';
$ItfInfoData = $obj->CheckProduct($ids);
$categoryobj = new Category();
$categories = $categoryobj->showCategoriesList(0);
include(BASEPATHS."/fckeditor/fckeditor.php")
?>
<script type="text/javascript">

$(document).ready(function() {

    var Validator = jQuery('#itffrminput').validate({
        rules: {           
                name: "required",
                category_id:"required",
                code: "required",
                logn_desc: "required"<?php if($ids==""){ ?>,
                main_image: "required"
                <?php } ?>
        },
        messages: {


        }
    });
});
</script>
<div class="full_w">
	<!-- Page Heading -->
    <div class="h_title"><?php echo ($ids=="")?"Add New ":"Edit "; echo $pagetitle;?></div>
    <!-- Page Heading -->
					
<form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
<input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id'])?$ItfInfoData['id']:'' ?>" />

    <div class="element">
        <label>Category<span class="red">(required)</span></label>
        <select name="category_id" class="err">
            <option value="">-- select category --</option>
            <?php foreach($categories as $key=>$cat) {?>
                <option value="<?php echo $key ?>" <?php if($key == $ItfInfoData["category_id"]){ echo"selected";} ?>><?php echo $cat; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="element">
        <label>Product Code<span class="red">(required)</span></label>
        <input class="text"  name="code" type="text"  id="name" size="35" value="<?php echo isset($ItfInfoData['code'])?$ItfInfoData['code']:'' ?>" />
    </div>

    <div class="element">
        <label>Product Name<span class="red">(required)</span></label>
        <input class="text"  name="name" type="text"  id="name" size="35" value="<?php echo isset($ItfInfoData['name'])?$ItfInfoData['name']:'' ?>" />
    </div>
    
    <div class="element">
        <label>Product Image<span class="red">(required)</span> </label>
        <input class="text" name="main_image" type="file"  id="main_image" size="35" />
        <?php if($ItfInfoData['main_image']){ ?>    
        <div class="display"><img src="<?php echo PUBLICPATH."/products/".$ItfInfoData['main_image']; ?>" height="40" width="40"/></div>    
        <?php } ?>
    </div>
   

    <div class="element">
        <label>Product Gallery Images<span class="blue">(one or more than one)</span> </label>
        <input class="text" name="image[]" type="file"  id="image" size="35" multiple />
    </div>

    <div class="element">
        <label>Description</label>
        <textarea class="textarea" name="logn_desc"><?php echo isset($ItfInfoData['logn_desc'])?$ItfInfoData['logn_desc']:'' ?></textarea>

    </div>

    <div class="element">
        <label>Specification</label>
        <textarea class="textarea" name="specification"><?php echo isset($ItfInfoData['specification'])?$ItfInfoData['specification']:'' ?></textarea>

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