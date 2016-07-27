<?php
$cmsPageCms = new PageCms();
$pageDetails = $cmsPageCms->GetPageDetails('44');
if (isset($_POST["submit"])) {
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"])) {
        $objstudent = new Student();
        $_POST['reg_status'] = 3;
        $_POST['source'] = 'Web';
        $userId = $objstudent->adminAdd($_POST);
        $paymentData = array();
        $paymentData['student_id'] = $userId;
        $paymentData['source'] = 2;
        $paymentData['reference_id'] = $_POST["class_id"];
        if ($_POST["payment_option"] == 1) {
            $paymentData['amount'] = $_POST["single_pay_amt"];
            $paymentData['is_installment'] = 1;
            $paymentData['payment_type'] = 1;
        } else {
            $paymentData['amount'] = $_POST["installment_booking_amt"];
            $paymentData['is_installment'] = 1;
            $paymentData['installment_amount'] = $_POST["installment_amt"];
            $paymentData['payment_type'] = 2;
        }
        $paymentObj = new Payment();
        $paymentObj->addOrder($paymentData);
        flashMsg("Successfully Registered");
    } else {
        flashMsg("Please Fill up Manadatory Fields.", "2");
        redirectUrl(CreateLink(array("summer_camps")));
    }
}
if (isset($_GET['msg']) and $_GET['msg'] == "na") {
    echo "<div class='msgbox n_error'><p>If you are not yet a customer please Register.</p></div>";
}
$gradeObj = new Grade();
$gradesList = $gradeObj->showAllActiveGrade();
$validJs = TemplateUrl() . "js2/jquery.validate.js";
$jqueryJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<script src="<?php echo $jqueryJs ?>"></script>
<script src="<?php echo $validJs ?>"></script>
<script>
    $(document).ready(function() {

        $('#frm').validate({// initialize the plugin
            errorElement: "span",
            rules: {
                first_name: {
                    required: true,
                    minlength:1,
                    maxlength: 50
                },
                last_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                }
            },
            messages: {
                first_name: {
                    required: "Please Enter a First Name",
                    minlength: "Please Enter a Valid First Name",
                    maxlength: "Please Enter a Valid First Name"
                },
                last_name: {
                    required: "Please Enter a Last Name",
                    minlength: "Please Enter a Valid Last Name",
                    maxlength: "Please Enter a Valid Last Name"
                },
            },
            submitHandler: function(form) { // for demo
                var xmlhttp;
                var first_name = document.getElementById("first_name").value;
                var last_name = document.getElementById("last_name").value;
                var str;
                str = first_name + '_____' + last_name;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                    {
                        document.getElementById("list_debt_students").innerHTML = xmlhttp.responseText;
                    }
                }

                xmlhttp.open("GET", "itf_ajax/debt_ranking_results.php?name=" + str, true);
                xmlhttp.send();
                return false; // for demo
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
<?php echo $pageDetails['logn_desc'] ?>
</div>
