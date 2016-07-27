<?php
#error_reporting(E_ALL);
#ini_set('display_errors', 'On');
$obj = new Class1;
if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
        // echo '<pre>';print_r($_POST);
        $obj->uploadRegForm($_POST);
        flash(ucfirst($pagetitle) . " is successfully updated");
        //  redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    } else {
        $obj->uploadRegForm($_POST);
        flash(ucfirst($pagetitle) . " is successfully added");
        // redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
//$InfoData = $objSlidingImage->imageDetail($ids);
$InfoData = $obj->getFormName(2);
$location = $obj->showAllLocation();
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                form_name: "required"
<?php if ($ids == '') { ?>
                    ,
                    bannerimage: {
                        required: true,
                                accept: "pdf|doc"
                    },
<?php } ?>
            },
            messages: {
                name: "Please enter  form name",
                        bannerimage: "Please select <?php echo $pagetitle; ?> name"
            }
        });
    });
</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
       // echo ($ids == "") ? "Add New " : "Edit ";
        echo 'Upload Registration Form';
        ;
        ?></div>
    <!-- Page Heading -->
    <form name="itffrminput" id="itffrminput" method="post" action="" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="form_type" type="hidden" id="id" value="2" />

        <div class="element">
            <label> Form Name <span class="red">*</span></label>
            <input class="field size1"  name="form_name" type="text"  id="form_name" size="35" value="<?php echo isset($InfoData['form_name']) ? $InfoData['form_name'] : '' ?>" />
        </div>
        <div class="element">
            <label> Upload Form <span class="red">*</span></label>
            <div id="FileUpload">
                <input type="file" size="24" id="bannerimage" name="bannerimage" class="BrowserHidden"  />
                <div id="BrowserVisible">
                    <?php if ($InfoData['name']) { ?>
                        <div class="display"> <a href="<?php echo SITEURL . "/itf_public/registration_form/" . $InfoData['name'] ?>" target="blank" ><?php echo $InfoData['name']?></a> </div>
                    <?php } ?>
                </div>
            </div>
            <div class="entry">

                <button type="submit">Submit</button>
                <button type="button" onclick="history.back()">Back</button>
            </div>


    </form>		
</div>