<?php

Html::AddJavaScript("register/assests/jquery.validate.password.js","component");
Html::AddStylesheet("register/assests/jquery.validate.password.css","component");
$obj = new User();
if(isset($_POST['submit']) && !empty($_POST['email'])){
// code for check server side validation
    if(empty($_SESSION['6_letters_code'] ) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
    {
        flashMsg("The Validation code does not match!","2");
    }else{
        //$id=$obj->getLastId();
        //echo "<pre>";print_r($id);die;
          //$_SESSION['maxid']=$_POST['max(id)'];
          $_SESSION['memberid']=$_POST['memberid'];
          $payment=$obj->getCheckPayMem($_SESSION['memberid']);
        
          $value=$payment['amount'];
           if($value==0)
           {
                 $obj->freeRegister($_POST);
                  flashMsg("You are successfully register for free trail membership");
                 redirectUrl(CreateLink(array("signin")));
           }
          else {
     
     $res = $obj->customerRegisterTemp($_POST);
          $_SESSION['temId']=$res;
             }
          
//          echo "fakhare";echo $res;die;
        //$res = $obj->customerRegister($_POST);
        if($res){
           
            redirectUrl(CreateLink(array("payment")));

        } elseif($res && $_POST['payment_type'] == "account"){
            flashMsg("Thanks for registering with Plucka, you will shortly receive an email your account activated.","3");
            redirectUrl(CreateLink(array("register","itemid"=>"customer")));

        } else {
            flashMsg("Something went wrong.","2");
            redirectUrl(CreateLink(array("register","itemid"=>"customer")));

        }
    }
}


?>
<script type="text/javascript">

$(document).ready(function() {

    $.validator.addMethod("noSpace", function(value, element) {

        var resinfo = parseInt(value.indexOf(" "));

        if(resinfo == 0 && value !="") { return false; } else return true;

    }, "Space not allowed in the starting of string !");

    $('#custom').validate({
        rules: {
            name:{required:true, maxlength:'100', noSpace: true},
            last_name:{required:true, maxlength:'100', noSpace: true},
            email_phone:{required:true, number:true},
            email: {required:true,},
            username:{
				remote: {
							url: "<?php echo SITEURL; ?>itf_ajax/index.php",
							type: "post",
							data: {
								itfusername: function() {
									return $( "#userid" ).val();
								}
							}
						}
			},    
            password:{required:true, minlength:8, maxlength:20, noSpace: true},
            verify_password:{required:true, minlength:8, maxlength:20, equalTo:"#password"},
            //payment_type:"required",
            terms_condition:"required",
            '6_letters_code':"required"

        },
        messages: {
            name:{required:"You must fill in all of the fields !"},
            last_name: {required:"You must fill in all of the fields !" },
            email_phone: {required:"You must fill in all of the fields !"},
            //payment_type: "You must fill in all of the fields !",
            terms_condition: "You must fill in all of the fields !",
            email: {required: "You must fill in all of the fields !"},
			username: {remote: "You must fill in all of the fields !"},
            password:{ required: "You must fill in all of the fields !"},
            verify_password:{ required: "You must fill in all of the fields !", equalTo:"Password not match !"},
            '6_letters_code':"You must fill in all of the fields !"

        }
    });


});
</script>
<div class="main_mat">
<p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href="#">Customer Registration</a></p>
</div>
<div class="about_us"><h3>Customer Registration</h3></div>
<div class="custom">
        <form id="custom" name="registration" method="post" action="">
               <input type="hidden" name="memberid" value="<?php echo $_GET['id']; ?>">
        <div class="reg">
            <label>First Name<span class="required">*</span></label>
        <input type="text" name="name" value="<?php echo isset($_POST['name'])?$_POST['name']:""; ?>">
        <div class="clear"></div>
        </div>
        <div class="reg">
        <label>Last Name<span class="required">*</span></label>
        <input type="text" name="last_name" value="<?php echo isset($_POST['last_name'])?$_POST['last_name']:""; ?>">
        <div class="clear"></div>
        </div>
        <div class="reg">
        <label>Email-Id<span class="required">*</span></label>
        <input type="text" name="email" value="<?php echo isset($_POST['email'])?$_POST['email']:""; ?>">
        <div class="clear"></div>
        </div>
            <div class="reg">
        <label>Username<span class="required">*</span></label>
        <input type="text" name="username" id="userid" value="<?php echo isset($_POST['username'])?$_POST['username']:""; ?>">
        <div class="clear"></div>
        </div>
        <div class="reg">
        <label>Business Phone</label>
        <input type="text" name="business_phone" value="<?php echo isset($_POST['business_phone'])?$_POST['business_phone']:""; ?>">
        <div class="clear"></div>
        </div>
        <div class="reg">
        <label>Mobile / Landline No.<span class="required">*</span></label>
        <input type="text" name="email_phone" value="<?php echo isset($_POST['email_phone'])?$_POST['email_phone']:""; ?>">
        <div class="clear"></div>
        </div>
        <div class="reg" >
        <label>Company Name</label>
        <input type="text" name="company_name" value="<?php echo isset($_POST['company_name'])?$_POST['company_name']:""; ?>">
        <div class="clear"></div>
        </div>
        <div class="reg">
        <label>Company ABN</label>
        <input type="text" name="company_abn" value="<?php echo isset($_POST['company_abn'])?$_POST['company_abn']:""; ?>" >
        <div class="clear"></div>
        </div>
         <div class="reg">
        <label>Company Address</label>
        <input type="text" name="company_address" value="<?php echo isset($_POST['company_address'])?$_POST['company_address']:""; ?>">
        <div class="clear"></div>
        </div>
         <div class="reg">
        <label>Company Suburb</label>
        <input type="text" name="company_subrub" value="<?php echo isset($_POST['company_subrub'])?$_POST['company_subrub']:""; ?>">
        <div class="clear"></div>
        </div>
         <div class="reg">
        <label>Company state</label>
        <input type="text" name="company_state" value="<?php echo isset($_POST['company_state'])?$_POST['company_state']:""; ?>">
        <div class="clear"></div>
        </div>
<!--
        <div class="reg payment">
            <label>Payment Type<span class="required">*</span></label>
            <input type="radio" name="payment_type" value="credit_card">By Credit Card
            <input type="radio" name="payment_type" value="account">By Account
            <div class="clear"></div>
        </div>-->

         <div class="reg">
        <label>Password<span class="required">*</span></label>
        <input type="password" class="password" name="password" id="password">
        <div class="password-meter">
		<div class="password-meter-message">Too Short&nbsp;</div>
		<div class="password-meter-bg">
			<div class="password-meter-bar"></div>
		</div>
	</div>
        <div class="clear"></div>
        </div>
         <div class="reg">
        <label>Verify Password<span class="required">*</span></label>
        <input type="password" name="verify_password" id="verify_password">
        <div class="clear"></div>
        </div>


            <div class="clear"></div>
            <div class="reg">
                <label>Validation code</label>
                <div class="cpatcha">
                    <img src="<?php echo SITEURL;?>/itf_ajax/captcha_code_file.php?rand=<?php echo rand();?>" id='captchaimg'>
                    <br>
                    Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh
                </div>
            </div>
            <div class="clear"></div>
            <div class="reg">
                <label for='message'>Enter the above code here<span class="required">*</span></label>
                <div class="inputbox"><input id="6_letters_code" name="6_letters_code" type="text"></div>
            </div>
            <div class="clear"></div>
            <div class="reg terms">
                <input name="terms_condition" type="checkbox" value="" class="cond"> Accept Terms & Conditions
            </div>
            <div class="reg">
        <label>&nbsp;</label>
        <input type="submit" name="submit" value="register">
        </div>
        </form>
</div>

<script type='text/javascript'>
    function refreshCaptcha()
    {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
    }
</script>
