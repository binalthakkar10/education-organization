<?php
    if(!empty($_SESSION['FRONTUSER'])){
        if($_SESSION['FRONTUSER']['usertype'] == 2){
            redirectUrl(CreateLink(array("dashboard")));
        }
        if($_SESSION['FRONTUSER']['usertype'] == 3){
            redirectUrl(CreateLink(array("dashboard")));
        }
    }

    if(isset($_POST["email"]))
    {
        if(!empty($_POST["email"])){
            $logininfo = $obj->userLogin($_POST["email"],$_POST["password"]);
      if($logininfo==2){
          flashMsg("Your membership plan is expired. Please contact admin.");
      }
      else
      {
            if($logininfo or !empty($_SESSION['FRONTUSER'])){
                if($_SESSION['FRONTUSER']['usertype'] == 2){
                    redirectUrl(CreateLink(array("dashboard")));
                }
                if($_SESSION['FRONTUSER']['usertype'] == 3){
                    redirectUrl(CreateLink(array("dashboard")));
                }
            } else {
                flashMsg("Username and Password do not match or you do not have an account yet.","2");
                redirectUrl(CreateLink(array("signin")));
            }
        }
        
        }
        else {
            flashMsg("Empty Username / Password not allowed.","2");
            redirectUrl(CreateLink(array("signin")));
        }
    }
if(isset($_GET['msg']) and $_GET['msg']=="na" ){
    echo "<div class='msgbox n_error'><p>If you are not yet a customer please Register.</p></div>";
    
}

?>


<div class="main_mat">
<p><a href="<?php echo ITFPATH; ?>">Home</a> / <a href="#">Sign In</a></p>
</div>
<div class="sgn">
    
     <h3>Customer/Supplier Login</h3>
    <form id="sgn" name="signin" method="post" action="">
        <div class="adres">
            <label>Username<span class="required">*</span></label>
            <input type="text" name="email">
            <div class="clear"></div>
        </div>
        <div class="adres">
            <label>Password<span class="required">*</span></label>
            <input type="password" name="password">
            <div class="clear"></div>
        </div>
        <div class="forget">
            <a href="<?php echo CreateLink(array('signin','itemid'=>'forgot')); ?>">Forgot Password?</a>
        </div>
        <div class="adres">
            <input type="submit" value="login">
            <div class="clear"></div>
        </div>
    </form>
</div>