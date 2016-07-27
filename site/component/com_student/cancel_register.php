<?php
$objstudent = new Student();
$cancelList = $objstudent->ShowStudentCancelStatus();
$objClass=new Class1;
$location = $objClass->showAllLocation();
if (isset($_POST["submit"])) {
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"])) {
        $objstudent = new Student();
        $studentDetails = $objstudent->cancelRegistration($_POST);
//print_r($studentDetails); die;
        if ($studentDetails != '0') {
            flashMsg("We have received your cancellation request and it will be processed within 3 business days.");
        } else {
            //flashMsg("We have recieved your cancellation request and it will be processed within 3 business days.");
            flashMsg("Invalid Student Details Supplied.");
        }
    } else {
        flashMsg("Please Fill up Mandatory Fields.", "2");
        redirectUrl(CreateLink(array("summer_camps")));
    }
}
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<script src="<?php echo $validJs ?>"></script>
<script>
    function showDetails(val)
    {
        if (val == 4)
        {
            document.getElementById("provide_div").style.display = 'block';
        }
        else
        {
            document.getElementById("provide_div").style.display = 'none';
        }
    }
    $(document).ready(function() {

        $('#frm').validate({// initialize the plugin
            errorElement: "span",
            rules: {
                first_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                last_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                primary_contact: {
                    //required: true,
                    minlength: 10,
                    maxlength: 15
                },
                primary_email: {
                    required: true,
                    email: true
                },
                loc_id: {
                    required: true
                },
                cancel_reason_id: {
                    required: true
                }

            },
            messages: {
                first_name: {
                    required: "Please Enter a  First Name",
                    minlength: "Please Enter a  Valid First Name",
                    maxlength: "Please Enter a  Valid First Name."
                },
                last_name: {
                    required: "Please Enter a  Last Name",
                    minlength: "Please Enter a  Valid Last Name",
                    maxlength: "Please Enter a  Valid Last Name"
                },
                primary_contact: {
                    required: "Please Enter a Phone Number",
                    minlength: "Please Enter a  Valid Phone Number",
                    maxlength: "Please Enter a  Valid Phone Number"
                },
                primary_email: "Please Enter a Valid Email",
                cancel_reason_id: "Please Select Cancel Reason",
                loc_id: "Please Select Location"

            }
        });

        $('#button').click(function() {
            if ($('#myform').valid()) {
                alert('form is valid - not submitted');
            } else {
                alert('form is not valid');
            }
        });

    });
</script>

<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <!--<div id="page_title">
        <h1>Calendar</h1>
    </div>-->
        <div id="page_content">
            <div class="page_left">
                <div id="cancel_registration">
                    <div id="cancel_reg_title"><h1>Cancel Registration</h1></div>
                    <form action="" method="post" name="frm" id="frm">
                        <div id="form_label">
                            <label>Student First Name<span>*</span></label>
                            <input type="text" name="first_name"  id="first_name" value="" />
                        </div>
                        <div id="form_label">
                            <label>Student Last Name<span>*</span></label>
                            <input type="text" name="last_name"  id="last_name" value="" />
                        </div>
                        <div id="form_label">
                            <label>Phone</label>
                            <input type="text" name="primary_contact" value="" id="primary_contact"/>
                        </div>
                        <div id="form_label">
                            <label>Email<span>*</span></label>
                            <input type="text" name="primary_email" id="primary_email" value="" />
                        </div>
                        <div id="form_label">
                            <label>Location<span>*</span></label>


                           <select name="loc_id" id="loc_id" class="err">
                <option value="">-- Select Location--</option>
                <?php foreach ($location as $loc) { ?>
                    <option value="<?php echo ucfirst($loc['code'] . ' - ' . $loc['name']) ?>" <?php
                    echo '>' . ucfirst($loc['code'] . ' - ' . $loc['name'])
                    ?></option><?php } ?>
            </select>            
                        </div>
                        <div id="form_label">
                            <label>Reason of Cancellation<span>*</span></label>


                            <select name="cancel_reason_id" id="cancel_reason_id" onchange="showDetails(this.value);">
                                <option value="">---Select Cancellation Reason--- </option>
                                <?php
                                $cancelId = $ItfInfoData['cancel_reason_id'];
                                foreach ($cancelList as $item) {
                                    ?>
                                    <option value="<?php echo $item['id']; ?>" ><?php echo $item['reason']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="provide_div" style="display: none">
                            <div id="form_label" >
                                <label>Please Provide Details </label>
                                <textarea title="Please Provide Details" name="cancel_detail"  id="cancel_detail"rows="5" cols="40"></textarea>
                            </div>
                        </div>
                        <div id="submit_btn">
                            <input class="button_submit sh_btn" type="submit" name="submit" value="Cancel Registration" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="page_right">
                <img src="<?php echo TemplateUrl() ?>/images/cancel-Reg.png" alt="" />
            </div>
        </div>
    </div>
</div>
