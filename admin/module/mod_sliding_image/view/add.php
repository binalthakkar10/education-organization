<?php
if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
       // echo '<pre>';print_r($_POST);
        $objSlidingImage->updateSlidingImage($_POST);
        flash(ucfirst($pagetitle) . " is successfully updated");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    } else {
        $objSlidingImage->addSlidingImage($_POST);
        flash(ucfirst($pagetitle) . " is successfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $objSlidingImage->imageDetail($ids);
$obj = new Class1;
$location = $obj->showAllLocation();
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                position: "required",
                name: "required"
<?php if ($ids == '') { ?>
                    ,
                            bannerimage: {
                                required: true,
                                        accept: "jpg|png|gif|jpeg"
                            },
<?php } ?>
            },
            messages: {
                name: "Please enter  name",
                        bannerimage: "Please select <?php echo $pagetitle; ?> name"
            }
        });
    });
</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids == "") ? "Add New " : "Edit ";
echo $pagetitle;
?></div>
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
            <label><?php echo $pagetitle; ?> Title <span class="red">*</span></label>
            <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name']) ? $InfoData['name'] : '' ?>" />
        </div>
        <div class="element">
            <label><?php echo $pagetitle; ?> Image <span class="red">*</span></label>
            <div id="FileUpload">
                <input type="file" size="24" id="bannerimage" name="bannerimage" class="BrowserHidden"  />
                <div id="BrowserVisible">
                    <?php if ($InfoData['image_name']) { ?>
                        <div class="display"><img src="<?php echo PUBLICPATH . "/banner/" . $InfoData['image_name']; ?>" height="40" width="40"/></div>
<?php } ?>
                </div>
            </div>

            <div class="element">
                <label><?php echo $pagetitle; ?> Sequence <span class="red">*</span></label>
                <select name="position" id="position">
                    <option value=""  >Select Position</option>
                    <?php
                    for ($i = 1; $i < 6; $i++) {
                        if ($InfoData['position'] == $i) {
                            echo "<option value=".$i." selected>".$i."</option>";
                        } else {
                            echo "<option value=".$i.">".$i."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="entry">

                <button type="submit">Submit</button>
                <button type="button" onclick="history.back()">Back</button>
            </div>


    </form>		
</div>
