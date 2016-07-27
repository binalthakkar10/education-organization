<?php
if (isset($_POST['id'])) {
    if (!empty($_POST['id'])) {
        $obj->admin_update($_POST);
        flash("Content is succesfully updated");
        //redirectUrl("itfmain.php?itfpage=".$currentpagenames);
    } else {
        $obj->admin_add($_POST);
        flash("Content is succesfully added");
        //redirectUrl("itfmain.php?itfpage=".$currentpagenames);
    }
    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$ItfInfoData = $obj->CheckNewsletter($ids);
include(BASEPATHS . "/fckeditor/fckeditor.php")
?>
<script type="text/javascript">

    $(document).ready(function() {

        var Validator = jQuery('#itffrminput').validate({
            rules: {
                title: "required",
		subject: "required",
                message: "required"

            },
            messages: {
                title: "required",
                message: "required"
            }
        });
    });
</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids == "") ? "Add New " : "Edit ";
echo $pagetitle; ?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id']) ? $ItfInfoData['id'] : '' ?>" />
<?php if ($ids == '') {
    ?>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        <?php } else { ?>
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
<?php } ?>

        <div class="element">
            <label>Newsletter Title<span class="red">(required)</span></label>
            <input class="text"  name="title" type="text"  id="title" size="35" value="<?php echo isset($ItfInfoData['title']) ? $ItfInfoData['title'] : '' ?>" />
        </div>
 <div class="element">
            <label>Newsletter Subject<span class="red">(required)</span></label>
            <input class="text"  name="subject" type="text"  id="subject" size="35" value="<?php echo isset($ItfInfoData['subject']) ? $ItfInfoData['subject'] : '' ?>" />
        </div>

        <div class="element">
            <label>Message</label>
<?php
$oFCKeditor = new FCKeditor('message');
//$oFCKeditor->BasePath = ITFPATH . 'fckeditor/';
$oFCKeditor->BasePath = '/fckeditor/';
$oFCKeditor->Value = isset($ItfInfoData['message']) ? $ItfInfoData['message'] : '';
$oFCKeditor->Height = "400";
$oFCKeditor->Width = "600";
$oFCKeditor->ToolbarSet = 'ITFCustom';
$oFCKeditor->Create();
?>
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
