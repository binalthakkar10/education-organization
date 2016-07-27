<?php
if (isset($_POST['id'])) {
    if (!empty($_POST['id'])) {
        $objTeacher->updateTeacherDetails($_POST);
        flash("Teacher is succesfully updated");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    } else {
        $objTeacher->addTeacherDetails($_POST);
        flash("Teacher is succesfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$ItfInfoData = $objTeacher->getTeacherInfo($ids);
?>

<script type="text/javascript">
    $(document).ready(function() {

    $.validator.addMethod("noSpace", function(value, element) {

    var resinfo = parseInt(value.indexOf(" "));
            if (resinfo == 0 && value != "") { return false; } else return true;
    }, "Space are not allowed!");
            var Validator = jQuery('#itffrminput').validate({
    errorElement: "span",
            rules: {
            first_name:{required:true, noSpace: true},
                    last_name:{required:true, noSpace: true},
                    email:{required:true, email:true,
                            remote: {
                            url: "<?php echo SITEURL; ?>/admin/itf_ajax/index.php",
                                    type: "post",
                                    data: {
                                    email: function()
                                    {
                                    return $("#email").val();
                                    }
                                    }
                            }


                    },
<?php if (empty($ids)) { ?>
                'password': {
                required: true,
                        minlength: 6,
                        maxlength: 20
                },
                        'password2': {

                        required: true,
                                equalTo: '#password'
                        }
<?php } ?>
            },
            messages: {
            first_name: " Please Enter a Valid First Name",
                    last_name: " Please Enter a Valid Last Name",
                    email:" Please Enter a Valid Email",
                    password: {
                    required: " Please Enter a Password",
                            minlength: "Please Enter Minimum 6 Character Password.",
                            maxlength: "Please Enter Maximium 20 Character Password."
                    },
                    password2: {
                    required: "Please Enter a Confirm Password",
                    equalTo: "Passwords do Not Match"
                    }
            }
    });
    });</script>

<style>
    .element span{
        margin-left: 12px;
        border: 0px;
    }
    
    
#main form .error {
  
}
</style>
<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
echo ($ids == "") ? "Add New " : "Edit ";
echo $pagetitle;
?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" >
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['user_id']) ? $ItfInfoData['user_id'] : '' ?>" />
        <input type="hidden" name="usertype" value="3" />

        <div class="element">
            <label>First Name<span class="red">(required)</span></label>
            <input class="text" name="first_name" type="text"  id="first_name" size="35" value="<?php echo isset($ItfInfoData['first_name']) ? $ItfInfoData['first_name'] : '' ?>" maxlength="100"/>
        </div>

        <div class="element">
            <label>Last Name<span class="red">(required)</span></label>
            <input class="text" name="last_name" type="text"  id="last_name" size="35" value="<?php echo isset($ItfInfoData['last_name']) ? $ItfInfoData['last_name'] : '' ?>" maxlength="100"/>
        </div>

        <div class="element">
            <label>Email<span class="red">(required)</span></label>
<?php if (empty($ids)) { ?>
                <input class="text" name="email" type="text"  id="email" size="35" value="<?php echo isset($ItfInfoData['email']) ? $ItfInfoData['email'] : '' ?>" maxlength="100"/>
            <?php } else { ?>
                <input class="text" name="email2" type="text" readonly="readonly" size="35" value="<?php echo isset($ItfInfoData['email']) ? $ItfInfoData['email'] : '' ?>" />
            <?php } ?>
        </div>

        <div class="element">
            <label>Address</label>
            <textarea name="address" class="textarea"><?php echo isset($ItfInfoData['address']) ? $ItfInfoData['address'] : '' ?></textarea>
        </div>

        <div class="element">
            <label>Phone</label>
            <input class="text" name="phone" type="text"  id="phone" size="35" value="<?php echo isset($ItfInfoData['phone']) ? $ItfInfoData['phone'] : '' ?>" maxlength="12"/>
        </div>

        <div class="element">
            <label>Password<?php if (empty($ids)) { ?><span class="red">(required)</span> <?php } ?></label>
            <input class="text" name="password" type="password"  id="password" size="35" value="" maxlength="50"/>
        </div>

        <div class="element">
            <label>Verify Password <?php if (empty($ids)) { ?><span class="red">(required)</span> <?php } ?></label>
            <input class="text" name="password2" type="password"  id="password2" size="35" value="" maxlength="50"/>
        </div>
        
        <div class="element">
            <label>Files Url</label>
            <input class="text" name="url" type="text"  id="phone" size="35" value="<?php echo isset($ItfInfoData['url']) ? $ItfInfoData['url'] : '' ?>" maxlength="250"/>
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