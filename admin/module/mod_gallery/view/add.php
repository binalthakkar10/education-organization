<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
if (isset($_POST['id'])) {
    // print_r($_POST);die;
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {

        $objbanner->admin_updateImageGallery($_POST);
        flash("Image is successfully updated");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);

    } else {
        $objbanner->admin_addGallery($_POST);
        flash("Image is successfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$addid = isset($_GET['addid']) ? $_GET['addid'] : '';
$InfoData = $objbanner->CheckImageGallery($ids);
$albumname = $objbanner->showAllAlbum();
//print_r($albumname);          
?>
<script type="text/javascript">
    $(document).ready(function() {
    $("#itffrminput").validate({
    rules: {
    name: "required",
<?php if ($ids == '') { ?>
        ,
                bannerimage: {
                required: true,
                        accept: "jpg|png|gif|jpeg"
                },
<?php } ?>

    album_id: "required",
            image_desc: "required"
    },
            messages: {
            name: "Please enter image name",
                    bannerimage: "Please select image",
                    album_id: "Please select gallery",
                    image_desc: "Please enter description"
            }
    });
    });</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids == "") ? "Upload Images " : "Edit "; ?></div>
    <!-- Page Heading -->
    <form name="itffrminput" id="itffrminput" method="post" action="" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
<?php if ($ids == '') {
    ?>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
<?php } else { ?>
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        <?php } ?>
        <div class="element">
            <label>Image Title <span class="red">*</span></label>
            <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name']) ? $InfoData['name'] : '' ?>" />
        </div>

        <div class="element">
            <label>Image Description <span class="red">*</span></label>
            <input class="field size1"  name="image_desc" type="text"  id="image_desc" size="35" value="<?php echo isset($InfoData['name']) ? $InfoData['name'] : '' ?>" />
        </div> 
        <div class="element">
            <label>Gallery Name <span class="red">*</span></label>
            <select name="album_id" class="paymentType"  id="album_id">
                <option value="">--Please select gallery--</option>
<?php foreach ($albumname as $album) {
    ?>
                    <option value="<?php echo $album['id'] ?>" <?php
                    if ($album['id'] == $InfoData["album_id"]) {
                        echo"selected";
                    }
                    ?>><?php echo $album['album_name']; ?></option>
                <?php } ?>
            </select>           
        </div>         

        <div class="element">
            <label>Upload Image <span class="red">*</span></label>
            <div id="FileUpload">
                <input type="file" size="24" id="bannerimage" name="bannerimage" class="BrowserHidden" >
<?php if ($InfoData['image_name']) { ?>
                    <div class="display"><img src="<?php echo PUBLICPATH . "image_gallery/" . $InfoData['album_id'] . "/" . $InfoData['image_name']; ?>" height="40" width="40"/></div>
                <?php } ?>
            </div>
        </div>
        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
    </form>		
</div>
