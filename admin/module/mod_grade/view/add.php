<?php
if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
        $obj->admin_updateGrade($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $obj->admin_addGrade($_POST);
        flash($pagetitle . " is successfully added");
    }

    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->CheckGrade($ids);
?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                grade: "required",
                grade_desc:"required"

            }
        });
    });
</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
        echo ($ids == "") ? "Add " : "Edit ";
        echo $pagetitle;
        ?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />

        <div class="element">

            <label>Grade<span class="red">(required)</span></label>
            <select name="grade" id="grade" >
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    if ($InfoData['grade'] == $i) {
                        echo '<option value="' . $i . '" selected>' . $i . ' </option>';
                    } else {

                        echo '<option value="' . $i . '" >' . $i . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="element">

            <label>Grade Description<span class="red">(required)</span></label>
            <input class="field size1"  name="grade_desc" type="text"  id="grade_desc" size="35" value="<?php echo isset($InfoData['grade_desc']) ? $InfoData['grade_desc'] : '' ?>" />

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
