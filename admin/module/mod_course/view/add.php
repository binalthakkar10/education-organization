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
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                code: "required",
                name: "required",
            },
            messages: {
                code: "Please enter <?php echo $pagetitle; ?> course code",
                name: "Please enter <?php echo $pagetitle; ?> course code",
            }
        });
    });
</script>

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php echo ($ids == "") ? "Add New " : "Edit ";
echo $pagetitle; ?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
        <input name="type" type="hidden" id="type" value="1" />


      
        <div class="element">
            <label>Course Code<span class="red">(required)</span></label>
            <input class="field size1"  name="code" type="text"  id="code" size="35" value="<?php echo isset($InfoData['code']) ? $InfoData['code'] : '' ?>" />
        </div>

        <div class="element">
            <label>Course Name<span class="red">(required)</span></label>
            <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name']) ? $InfoData['name'] : '' ?>" />
        </div>

        <div class="element">
            <label> Course Description</label>
            <textarea name="description" id="description" cols="100" rows="10"><?php echo isset($InfoData['description']) ? $InfoData['description'] : '' ?></textarea>
           
        </div>

        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
