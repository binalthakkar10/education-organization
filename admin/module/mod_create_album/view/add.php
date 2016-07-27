<?php
if (isset($_POST['id'])) {
    $userids = $_POST['id'];

    if (!empty($_POST['id'])) {
        $obj->admin_updateAlbum($_POST);
        flash($pagetitle . " is successfully added");
    } else {
        $data = $obj->admin_createAlbum($_POST);
        if (isset($data) and $data == '1') {
            flash($pagetitle . " is successfully added");
        } else {
            flash("<font color=red>" . $pagetitle . " is already exist</font>");
        }
    }
    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}
//echo "check album";
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->CheckAlbum($ids);
?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                //grade: "required"                                         
                album_name: "required",
                album_desc: "required"

            },
            messages: {
                album_name: "Please enter <?php echo $pagetitle; ?>",
                album_desc: "Please enter description"
            }
        });
    });
</script>

<style>
    .ui-autocomplete {
        background-color: white;
        width: 300px;
        border: 1px solid #cfcfcf;
        list-style-type: none;
        padding-left: 0px;
    }

    .add_field,.remove_field{
        background-color: #d3d3d3;
        width: 20px;
        height: 20px;
        display: inline-block;
        text-align: center;
        color: #0033ff;
        font-size: 19px;
        cursor: pointer;
    }

    .input_holder input{
        display:block;
    }

    .add_field1,.remove_field1{
        background-color: #d3d3d3;
        width: 20px;
        height: 20px;
        display: inline-block;
        text-align: center;
        color: #3E3E3E;
        font-size: 19px;
        cursor: pointer;
    }

    .input_holder1 input{
        display:block;
    }
</style>

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids == "") ? "Add New " : "Edit ";
echo $pagetitle;
?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
        <?php if ($ids == '') {
            ?>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
        <?php } else { ?>
            <input type="hidden" name="modified_date" id="modified_date" value="<?php echo date('Y-m-d H:i:s') ?>" />
<?php } ?>

        <div class="element">
            <label><?php echo $pagetitle; ?>  Name<span class="red">*</span></label>
            <input class="field size1"  name="album_name" type="text"  id="album_name" size="35" value="<?php echo isset($InfoData['album_name']) ? $InfoData['album_name'] : '' ?>" />
        </div>

        <div class="element">
            <label><?php echo $pagetitle; ?> Description <span class="red">*</span></label>
            <input class="field size1"  name="album_desc" type="text"  id="album_desc" size="35" value="<?php echo isset($InfoData['album_desc']) ? $InfoData['album_desc'] : '' ?>" />
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