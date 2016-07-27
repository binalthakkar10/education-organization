<?php
if (isset($_POST['id'])) {
    if (!empty($_POST['id'])) {
        $cmsobj->admin_update($_POST);
        flash("Page is succesfully updated");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    } else {
        $cmsobj->admin_add($_POST);
        flash("Page is succesfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$ItfInfoData = $cmsobj->CheckPageCms($ids);
include(BASEPATHS . "/fckeditor/fckeditor.php")
?>
<script type="text/javascript">

    $(document).ready(function() {
        var Validator = jQuery('#itffrminput').validate({
            rules: {
                name: "required",
                pagetitle: "required"
 },
            messages: {
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

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id']) ? $ItfInfoData['id'] : '' ?>" />
        <?php if ($ids == '') {
            ?>
        <input type="hidden" name="created_date" id="created_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        <?php } else { ?>
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
<?php } ?>

        <div class="element">
            <label>Page Title<span class="red">(required)</span></label>
            <input class="text" name="pagetitle" type="text"  id="pagetitle" size="35" value="<?php echo isset($ItfInfoData['pagetitle']) ? $ItfInfoData['pagetitle'] : '' ?>" />
        </div>

        <div class="element">
            <label>Alias Name<span class="red">(required)</span></label>
            <input class="text"  name="name" type="text"  id="name" size="35" value="<?php echo isset($ItfInfoData['name']) ? $ItfInfoData['name'] : '' ?>" />
        </div>

        <div class="element">
            <label>Keyword</label>
            <input class="text" name="pagekeywords" type="text"  id="pagekeywords" size="35" value="<?php echo isset($ItfInfoData['pagekeywords']) ? $ItfInfoData['pagekeywords'] : '' ?>" />
        </div>

        <div class="element">
            <label>Meta tag</label>
            <textarea class="textarea" name="pagemetatag" type="text"  id="pagemetatag"><?php echo isset($ItfInfoData['pagemetatag']) ? $ItfInfoData['pagemetatag'] : '' ?></textarea>
        </div>

        <div class="element">
            <label>Description</label>
            <?php
                $oFCKeditor = new FCKeditor('logn_desc');
                $oFCKeditor->BasePath = '/fckeditor/';
                $oFCKeditor->Value = isset($ItfInfoData['logn_desc']) ? $ItfInfoData['logn_desc'] : '';
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