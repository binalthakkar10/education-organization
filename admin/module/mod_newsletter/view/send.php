<?php
if (isset($_POST['id'])) {
    if (!empty($_POST)) {
        $obj->admin_send_newsletter($_POST);
        flash("Newsletter is succesfully sent");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$templates = $obj->ShowActiveNewsletter();
$members = $obj->ShowAllSubscribersActive($_REQUEST['source']);
$sourceName=$obj->ShowAllSubscribersActiveSource();

//$objstudent = new Student();
//$studentData = $objstudent->ShowAllActiveStudent();
//$objTournament = new Tournament();
//$TournamentStudentData = $objTournament->ShowAllActiveTournamentStudent();
//echo"<pre>"; print_r($templates); die;

?>
<script type="text/javascript">

    $(document).ready(function() {

        var Validator = jQuery('#itffrminput').validate({
            rules: {
                newsletter: "required",
                members: "required"

            },
            messages: {
                newsletter: "required",
                members: "required"
            }
        });
    });
</script>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
        echo ($ids == "") ? "Add New " : "Edit ";
        echo $pagetitle;
        ?></div>
    <!-- Page Heading -->
  <form id="itffrmsearch" name="itffrmsearch" method="post" action="">

        <input type="hidden" name="itfpage" value="<?php echo $currentpagenames; ?>" />
        <div class="element">
            <label>Select Source For Search</label>
            <select name="source" id="source">
                <option value="">---All Source--- </option>
                
                <?php
                foreach ($sourceName as $item) {
                    $idtest = '';
                    $source = $_REQUEST['source'];
                    ?>
                    <option value="<?php echo $item['source']; ?>"   <?php if ($source == $item['source']) { ?>selected="selected"<?php } ?>><?php echo $item['source']; ?> </option>
                <?php } ?>
            </select>     
        </div>  

        
        <input name="searchuser" type="submit" id="searchuser"  value="search" />
    </form> 
    <form action="" method="post" name="itffrminput" id="itffrminput">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id']) ? $ItfInfoData['id'] : '' ?>" />


        <div class="element">
            <label>Newsletter<span class="red">(required)</span></label>
            <?php echo Html::ComboBox("newsletter", Html::CovertSingleArray($templates, "id", "title"), "", array(), "Select Template"); ?>
        </div>

        <!--
            <div class="element">
                <label>Subscribe Members<span class="red">(required)</span></label>
        <?php //echo Html::ComboBox("members",Html::CovertSingleArray($members,"id","email"),"",array("multiple"=>"multiple","class"=>"multiple"),"");    ?>
        
            </div>-->

        <div class="element">
            <select name="select2[]" id="select2" size="10" multiple="multiple" tabindex="1">

                <option value="">---Please Select Email Id--- </option>

                <?php
                foreach ($members as $emailid) {
                    ?>
                    <option value="<?php echo $emailid['email']; ?>"><?php echo $emailid['first_name'] . '&nbsp;' . $emailid['last_name'] . ' , ' . $emailid['email'] . " ( $emailid[source] )"; ?> </option>
                <?php } ?>



            </select>
        </div>



        <!-- Form Buttons -->
        <div class="entry">
            <button type="submit">Send</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
