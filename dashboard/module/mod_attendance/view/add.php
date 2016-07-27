<?php
if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {

        $obj->admin_updateCourse($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $obj->admin_addCourse($_POST);
        flash($pagetitle . " is successfully added");
    }
    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->CheckCourse($ids);

//$priceCat=$categoryobj->showAllPriceCategory();
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                course_for: "required",
                course_code: "required",
                course: "required",
            },
            messages: {
                course_for: "Please enter <?php echo $pagetitle; ?> course type",
                course_code: "Please enter <?php echo $pagetitle; ?> course code",
                course: "Please enter <?php echo $pagetitle; ?> course code",
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
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
        <div class="element">
            <label>Course Code<span class="red">(required)</span></label>
            <input class="field size1"  name="course_code" type="text"  id="course_code" size="35" value="<?php echo isset($InfoData['course_code']) ? $InfoData['course_code'] : '' ?>" />
        </div>

        <div class="element">
            <label>Course<span class="red">(required)</span></label>
            <input class="field size1"  name="course" type="text"  id="course" size="35" value="<?php echo isset($InfoData['course']) ? $InfoData['course'] : '' ?>" />
        </div>

        <div class="element">
            <label> Course Description</label>
            <input class="field size1"  name="course_description" type="text"  id="course_description" size="35" value="<?php echo isset($InfoData['course_description']) ? $InfoData['course_description'] : '' ?>" />
        </div>

        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
