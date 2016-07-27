<?php
#error_reporting(E_ALL);
#ini_set('display_errors', 'On');
if (isset($_POST['email'])) {
           
    $urlname = CreateLinkAdmin(array($currentpagenames, 'actions' => 'subscriber'));
    if (!empty($_POST['id'])) {
        $obj->admin_update($_POST);
        flash("Subscriber is succesfully updated");
        redirectUrl($urlname);
        //redirectUrl("itfmain.php?itfpage=".$currentpagenames);
    } else {
 
        if ($obj->checkSubscriber($_POST['email'])) {
            $obj->addSubscriber($_POST['email'],'subscriber_admin');
            flash("Subscriber is succesfully added");
            redirectUrl($urlname);
        }
        flash("Email is already exist in our db.Please add other email id");

        //redirectUrl("itfmain.php?itfpage=".$currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$ItfInfoData = $obj->getSubscriberInfo($ids);
?>
<script type="text/javascript">

    $(document).ready(function() {

        var Validator = jQuery('#itffrminput').validate({
            rules: {
                email: "required",
                status: "required"
            }
        });
    });
</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
    $pagetitle='Subscriber';
echo ($ids == "") ? "Add New " : "Edit ";
echo $pagetitle;
?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id']) ? $ItfInfoData['id'] : '' ?>" />
        <input type="hidden" name="source" id="source" value="Subscriber_Admin" />
        <div class="element">
            <label>Subscriber Email<span class="red">(required)</span></label>
            <input class="text"  name="email" type="text"  id="email" size="35" value="<?php echo isset($ItfInfoData['email']) ? $ItfInfoData['email'] : '' ?>" />
        </div>

        <div class="element">
            <label>Status<span class="red">(required)</span></label>
            <input type="radio" name="status" value="1"  <?php
        if ($ItfInfoData['id'] == '' || $ItfInfoData['status'] == '1') {
            echo 'checked="checked"';
        }
?>>Active
            <input type="radio" name="status" value="0" <?php echo $ItfInfoData['status'] == '0' ? 'checked="checked"' : '' ?>>Inactive
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
