<?php
if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
        $debateObj->admin_updateDebate($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $debateObj->admin_addDebate($_POST);
        flash($pagetitle . " is successfully added");
    }
    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $debateObj->CheckDebate($ids);
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                last_name: "required",
                first_name: "required",
                debt_score: "required",
                attend_date: "required",
            },
        });
    });
</script>

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
        echo ($ids == "") ? "Add New " : "Edit ";
        echo $pagetitle;
        ?></div>
    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <div class="element">
            <label>First Name<span class="red">(required)</span></label>
            <input class="field size1"  name="first_name" type="text"  id="first_name" size="35" value="<?php echo isset($InfoData['first_name']) ? $InfoData['first_name'] : '' ?>" />
        </div>
        <div class="element">
            <label>Last Name<span class="red">(required)</span></label>
            <input class="field size1"  name="last_name" type="text"  id="last_name" size="35" value="<?php echo isset($InfoData['last_name']) ? $InfoData['last_name'] : '' ?>" />
        </div>    


        <div class="element">
            <label> Debt Score <span class="red">(required)</span></label>
            <input class="field size1"  name="debt_score" type="text"  id="debt_score" size="35" value="<?php echo isset($InfoData['debt_score']) ? $InfoData['debt_score'] : '' ?>" />
        </div>
        <div class="element">
            <label>Year of birth</label>
            <input class="field size1 tcal" autocomplete="off" name="dob" type="text"  id="attend_date" size="35" value="<?php echo isset($InfoData['dob']) ? date('m/d/Y', strtotime($InfoData['dob'])) : '' ?>" />
        </div>
        <div class="element">
            <label>Last Tournament Attended<span class="red">(required)</span></label>
            <input class="field size1 tcal" autocomplete="off" name="attend_date" type="text"  id="attend_date" size="35" value="<?php echo isset($InfoData['attend_date']) ? date('m/d/Y', strtotime($InfoData['attend_date'])) : '' ?>" />
        </div>

        <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>

