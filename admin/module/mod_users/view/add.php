<?php
//echo "state add";

if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
        $obj->updateUser($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $obj->addUser($_POST);
        flash($pagetitle . " is successfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }

    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}

$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->UserDetail($ids);
$orgName = $obj->ShowAllOrg();
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
            org_id:{required:true},
            user_type:{required:true},
            username:{required:true, noSpace: true},
                    last_name:{required:true, noSpace: true},
                    email:{required:true, email:true,
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
                        },
<?php } ?>
            },
            messages: {
            first_name: " Please Enter a Valid First Name",
                    last_name: " Please Enter a Valid Last Name",
                    email:" Please Enter a Valid Email",
                    org_id:"Please select the organization Name",
                    user_type:"Please select the user type",
                    username:{
                    	required:"Please enter the usename",
                    	noSpace :"There should be no space in user name",
                        },
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
    });
</script>
<style>
    .ui-autocomplete {
        background-color: white;
        width: 300px;
        border: 1px solid #cfcfcf;
        list-style-type: none;
        padding-left: 0px;
    }

    .add_field,.remove_field{
        background-color: #d3d3d3;
        width: 20px;
        height: 20px;
        display: inline-block;
        text-align: center;
        color: #0033ff;
        font-size: 19px;
        cursor: pointer;
    }

    .input_holder input{
        display:block;
    }

    .add_field1,.remove_field1{
        background-color: #d3d3d3;
        width: 20px;
        height: 20px;
        display: inline-block;
        text-align: center;
        color: #3E3E3E;
        font-size: 19px;
        cursor: pointer;
    }

    .input_holder1 input{
        display:block;
    }
</style>

<div class="full_w">
    <!-- Page Heading -->
    <div class="h_title"><?php
        echo ($ids == "") ? "Add New " : "Edit ";
        echo $pagetitle;
        ?></div>
    <!-- Page Heading -->

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input name="id" type="hidden" id="id" value="<?php echo!empty($InfoData['id']) ? $InfoData['id'] : ''; ?>" />
        <input name="parentid" type="hidden" id="parentid" value="<?php echo $parentids; ?>" />
        <div class="element">
            <label>Organization Name<span class="red">(required)</span></label>

        <select name="org_id" id="org_id" >
                <option value="">---Select Organization Name--- </option>
                <?php foreach ($orgName as $item) { ?>
                    <option value="<?php echo $item['org_id']; ?>"   <?php if ($InfoData['org_id'] == $item['org_id']) { ?>selected="selected"<?php } ?>><?php echo $item['org_name']; ?> </option>
                <?php } ?>
            </select>
             </div>
      
        <div class="element">
            <label> First Name <span class="red">(required)</span></label>
            <input name="first_name" id="first_name"  size="35" type="text" value="<?php echo isset($InfoData['first_name']) ? $InfoData['first_name'] : '' ?>"/>
            <div class="clear"></div>
        </div>
        <div class="element">
            <label>Last Name<span class="red">(required)</span></label>
            <input class="text" name="last_name" type="text"  id="last_name" size="35" value="<?php echo isset($InfoData['last_name']) ? $InfoData['last_name'] : '' ?>" maxlength="100"/>
        </div>
       <div class="element">
            <label>Email<span class="red">(required)</span></label>
<?php if (empty($ids)) { ?>
                <input class="text" name="email" type="text"  id="email" size="35" value="<?php echo isset($InfoData['email']) ? $InfoData['email'] : '' ?>" maxlength="100"/>
            <?php } else { ?>
                <input class="text" name="email2" type="text" readonly="readonly" size="35" value="<?php echo isset($InfoData['email']) ? $InfoData['email'] : '' ?>" />
            <?php } ?>
        </div>
        		<div class="element">
            <label>User Name<span class="red">(required)</span></label>
            <input class="text" name="username" type="text"  id="username" size="35" value="<?php echo isset($InfoData['username']) ? $InfoData['username'] : '' ?>" maxlength="100"/>
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
           <label>User Type<span class="red">(required)</span></label>
           <select name="user_type" id="user_type" >
           <option value="">---User Type--- </option>
               <option value="1" <?php if ($InfoData['user_type'] == '1') { echo"selected";}?>>Admin User </option>
               <option value="2" <?php if ($InfoData['user_type'] == '2') { echo"selected";}?>>Organization User </option>
              
               </select>     
       </div> 
            <div class="entry">
            <button type="submit">Submit</button>
            <button type="button" onclick="history.back()">Back</button>
        </div>
        <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>
