<?php
//echo "grade add";

if (isset($_POST['id'])) {
    $userids = $_POST['id'];
    if (!empty($_POST['id'])) {
        $obj->UpdateMenu($_POST);
        flash($pagetitle . " is successfully Updated");
    } else {
        $obj->admin_addGrade($_POST);
        flash($pagetitle . " is successfully added");
    }

    $urlname = CreateLinkAdmin(array($currentpagenames, "parentid" => $parentids));
    redirectUrl($urlname);
}

$ids = isset($_GET['id']) ? $_GET['id'] : '';
$InfoData = $obj->CheckMenu($ids);
//echo '<pre>';print_r($InfoData);
//echo $InfoData["link"];
$pages = $obj->ShowAllPages();
$parentMenu = $obj->ShowParenMenuName($InfoData['parent_id']);?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#itffrminput").validate({
            rules: {
                name: "required"


            },
            messages: {
                name: "Please enter menu name"

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
            <label>Name<span class="red">(required)</span></label>
            <input class="field size1"  name="name" type="text"  id="name" size="35" value="<?php echo isset($InfoData['name']) ? $InfoData['name'] : '' ?>" />
        </div>

        <?php if($InfoData["parent_id"]!=0 && $InfoData["parent_id"]!='')
        { ?>
    
        <div class="element">
            <label>Parent Menu<span class="red">(required)</span></label>
            <select name="link" id="link" class="err">
               
                <?php foreach ($parentMenu as $name) { ?>
                    <option value="<?php echo $name['id'] ?>" <?php
                    if ($name['id'] == $InfoData["parent_id"]) {
                        echo"selected";
                    }
                    ?>><?php echo ucfirst($name['name']); ?></option>      
                        <?php } ?>
            </select>            
        </div>
        <?php } ?>

        <div class="element">
            <label>Menu Link<span class="red">(required)</span></label>
            <select name="link" id="link" class="err">
                <option value="">-- Select Page--</option>
                <?php foreach ($pages as $loc) { ?>
                    <option value="<?php echo $loc['id'] ?>" <?php
                    if ($loc['id'] == $InfoData["link"]) {
                        echo"selected";
                    }
                    ?>><?php echo ucfirst($loc['name']); ?></option>      
                        <?php } ?>
            </select>            
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