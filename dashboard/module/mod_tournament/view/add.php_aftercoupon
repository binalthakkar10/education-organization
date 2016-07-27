<?php
if (isset($_POST['id'])) {
    if (!empty($_POST['id'])) {
        if ($_POST['type'] != 1) {
            $_POST['external_url'] = '';
        }
        $userobj->tournament_update($_POST);
        flash("Tournament is succesfully updated");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    } else {
        if ($_POST['type'] != 1) {
            $_POST['external_url'] = '';
        }
        $userobj->tournament_add($_POST);
        flash("Tournaments is succesfully added");
        redirectUrl("itfmain.php?itfpage=" . $currentpagenames);
    }
}
$ids = isset($_GET['id']) ? $_GET['id'] : '';
$ItfInfoData = $userobj->gettournamentInfo($ids);
$topicDetails = $userobj->getTopicInfo($ids);
//echo '<br/><pre>';print_r($topicDetails);
if ($ItfInfoData['tournament_date'] != '') {
    $tournament_date = date('m/d/Y', strtotime($ItfInfoData['tournament_date']));
} else {
    $tournament_date = date('m/d/Y');
}
$obj = new Class1();
$location = $obj->showAllLocation();
///
$getCoupon = new Coupon;
$couponList = $getCoupon->TournamentCoupon();
$InfoDataCoupon = $getCoupon->CouponTournamentData($ids);
include(BASEPATHS . "/fckeditor/fckeditor.php");
?>
<script type="text/javascript">
    $(document).ready(function() {

        $("#itffrminput").validate({
            rules: {
                title: "required",
                tournament_date: "required",
                location_code: "required",
                start_time: "required",
                end_time: "required",
                        loc_id: "required"<?php if ($ids == '') { ?>
                    , bannerimage: {
                        required: true,
                                accept: "jpg|png|gif|jpeg"
                    }
<?php } ?>
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

    <form action="" method="post" name="itffrminput" id="itffrminput" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id']) ? $ItfInfoData['id'] : '' ?>" />


        <div class="element">
            <label>Tournament Title<span class="red">(required)</span></label>
            <input class="text" name="title" type="text"  id="title" size="35" value="<?php echo isset($ItfInfoData['title']) ? $ItfInfoData['title'] : '' ?>" />
        </div>

        <div class="element">
            <label>Tournament Date<span class="red">(required)</span></label>
            <input class="text tcal"  name="tournament_date" type="text"  id="tournament_date" size="35" value="<?php echo $tournament_date ?>" readonly/>
        </div>
        <div class="element">
            <label>Start Time<span class="red">(required)</span></label>
            <input class="text"  name="start_time" type="text"  id="start_time" size="35" value="<?php echo isset($ItfInfoData['start_time']) ? $ItfInfoData['start_time'] : '' ?>" />
        </div>

        <div class="element">
            <label>End Time<span class="red">(required)</span></label>
            <input class="text"  name="end_time" type="text"  id="end_time" size="35" value="<?php echo isset($ItfInfoData['end_time']) ? $ItfInfoData['end_time'] : '' ?>" />
        </div>

        <div class="element">
            <label>Location Name<span class="red">(required)</span></label>
            <select name="loc_id" id="location_code" class="err">
                <option value="">-- Select Location Name--</option>
                <?php foreach ($location as $loc) { ?>
                    <option value="<?php echo $loc['id'] ?>" <?php
                    if ($loc['id'] == $ItfInfoData["loc_id"]) {
                        echo"selected";
                    }echo '>' . ucfirst($loc['code'] . ' - ' . $loc['name'])
                    ?></option><?php } ?>
            </select>            
        </div>
        <div class="element">
            <label><?php echo $pagetitle; ?> Image <span class="red">(required)</span></label>
            <div id="FileUpload">
                <input type="file" size="24" id="bannerimage" name="bannerimage" class="BrowserHidden"  />
                <div id="BrowserVisible">
                    <?php
                    if ($ItfInfoData['image'] != '') {
                        echo PUBLICPATH . "tournament_image/" . $ItfInfoData['image'];
                        ?>
                        <div class="display"><img src="<?php echo PUBLICPATH . "tournament_image/" . $ItfInfoData['image']; ?>" height="40" width="40"/></div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="element">
            <label>Tournament Description</label>
            <?php
            $oFCKeditor = new FCKeditor('sdescription');
            //$oFCKeditor->BasePath = ITFPATH . 'fckeditor/';
            $oFCKeditor->BasePath = '/fckeditor/';
            $oFCKeditor->Value = isset($ItfInfoData['sdescription']) ? $ItfInfoData['sdescription'] : '';
            $oFCKeditor->Height = "400";
            $oFCKeditor->Width = "600";
            $oFCKeditor->ToolbarSet = 'ITFCustom';
            $oFCKeditor->Create();
            ?>
        </div>

        <!--<div class="element">
            <label>Location Code<span class="red">(required)</span></label>
            <select name="location" id="location" class="err">
                <option value="">-- Select Location Code--</option>
        <?php foreach ($location as $loc) { ?>
                                                                                                                        <option value="<?php echo $loc['loc_code'] ?>" <?php
            if ($loc['code'] == $ItfInfoData["location"]) {
                echo"selected";
            }
            ?>><?php echo ucfirst($loc['code'] . ' - ' . $loc['name']); ?></option>      
        <?php } ?>
            </select>            
        </div>-->
        <div class="element">
            <label>Registration Type</label>
            <input type="radio" name="type" value="0" onclick="document.getElementById('external').style.display = 'none'" <?php echo $ItfInfoData[' external_url '] == '' ? 'checked="checked"' : '' ?>>Internal<br>
            <input type="radio" name="type" value="1" onclick="document.getElementById('external').style.display = 'block'" <?php echo $ItfInfoData['external_url'] != '' ? 'checked="checked"' : '' ?>>External
        </div>
        <?php if ($ItfInfoData['external_url'] == '' || $_REQUEST['actions'] == 'add') {
            ?>
            <div class="element" id="external" style="display:none;">

            <?php } else { ?>
                <div class="element" id="external" style="display:block;">
                <?php } ?>
                <label>External Url</label>

                <input class="text" name="external_url" type="text"  id="external_url" size="35" value="<?php echo isset($ItfInfoData['external_url']) ? $ItfInfoData['external_url'] : '' ?>" />
            </div>
            <div class="element">
                <label>Registration Fee</label>
                <input class="text"  name="fee" type="text"  id="fee" size="35" value="<?php echo isset($ItfInfoData['fee']) ? $ItfInfoData['fee'] : '' ?>" />
            </div>
            <div class="element">
            <label>Coupon <span class="red"></span></label>           
            <select name="coupon_applied"> 
                <option value="" >--Select Coupon--</option>
                <?php
                foreach ($couponList as $values) {
                    if ($InfoDataCoupon[0]['coupon_id'] == $values["id"]) {
                        echo '<option value="' . $values["id"] . '" selected="selected">' . $values["name"] .'('.$values["code"].') </option>';
                    } else {
                        echo '<option value="' . $values["id"] . '">'. $values["name"] .'('.$values["code"].')</option>';
                    }
                }
                ?>
            </select>

        </div>
            <!--<div class="element">
                <label>Tournament Topics Choices</label>
                <input class="text"  name="topics_choices" type="text"  id="topics_choices" size="35" value="<?php echo isset($ItfInfoData['topics_choices']) ? $ItfInfoData['topics_choices'] : '' ?>" />
            </div>-->
            <!-- <div class="element">
                 <label>Social Networking benefits society</label>
                 <select name="topic1" id="topic1" class="err"> 
                     <option value="">Please Select</option>
                     <option value="FOR" <?php
            if ('FOR' == $topicDetails["1"]["topic_value"]) {
                echo"selected";
            }
            ?>>FOR</option>
                     <option value="AGAINST" <?php
            if ('AGAINST' == $topicDetails["1"]["topic_value"]) {
                echo"selected";
            }
            ?>>AGAINST</option>
                 </select> 
             </div>
             <div class="element">
                 <label>Marijuana should be legalized</label>
                 <select name="topic2" id="topic2" class="err"> 
                     <option value="">Please Select</option>
                     <option value="FOR" <?php
            if ('FOR' == $topicDetails["2"]["topic_value"]) {
                echo"selected";
            }
            ?>>FOR</option>
                     <option value="AGAINST" <?php
            if ('AGAINST' == $topicDetails["2"]["topic_value"]) {
                echo"selected";
            }
            ?>>AGAINST</option>
                 </select> 
             </div>
 
             <div class="element">
                 <label>School lunches should be buffet style</label>
                 <select name="topic3" id="topic3" class="err"> 
                     <option value="">Please Select</option>
                     <option value="FOR" <?php
            if ('FOR' == $topicDetails["3"]["topic_value"]) {
                echo"selected";
            }
            ?>>FOR</option>
                     <option value="AGAINST" <?php
            if ('AGAINST' == $topicDetails["3"]["topic_value"]) {
                echo"selected";
            }
            ?>>AGAINST</option>
                 </select> 
             </div>
             <div class="element">
                 <label>US should prioritize energy independence</label>
                 <select name="topic4" id="topic4" class="err"> 
                     <option value="">Please Select</option>
                     <option value="FOR" <?php
            if ('FOR' == $topicDetails["4"]["topic_value"]) {
                echo"selected";
            }
            ?>>FOR</option>
                     <option value="AGAINST" <?php
            if ('AGAINST' == $topicDetails["4"]["topic_value"]) {
                echo"selected";
            }
            ?>>AGAINST</oprextended</label>
                 <select name="topic5" id="topic5" class="err"> 
                     <option value="">Please Select</option>
                     <option value="FOR" <?php
            if ('FOR' == $topicDetails["5"]["topic_value"]) {
                echo"selected";
            }
            ?>>FOR</option>
                     <option value="AGAINST" <?php
            if ('AGAINST' == $topicDetails["5"]["topic_value"]) {
                echo"selected";
            }
            ?>>AGAINST</option>
                 </select> 
             </div>
 
             <div class="element">
                 <label>Stay home Mom's kids perform better in school</label>
                 <select name="topic6" id="topic6" class="err"> 
                     <option value="">Please Select</option>
                     <option value="FOR" <?php
            if ('FOR' == $topicDetails["6"]["topic_value"]) {
                echo"selected";
            }
            ?>>FOR</option>
                     <option value="AGAINST" <?php
            if ('AGAINST' == $topicDetails["6"]["topic_value"]) {
                echo"selected";
            }
            ?>>AGAINST</option>
                 </select> 
             </div>
 
            <!--<div class="element">
                <label>School timing should be extended</label>
    
                <select name="topic7" id="topic7" class="err"> 
                    <option value="FOR" <?php
            if ('FOR' == $ItfInfoData["6"]["topic_value"]) {
                echo"selected";
            }
            ?>>FOR</option>
                    <option value="AGAINST" <?php
            if ('AGAINST' == $ItfInfoData["6"]["topic_value"]) {
                echo"selected";
            }
            ?>>AGAINST</option>
                </select> 
            </div>-->

            <!-- Form Buttons -->
            <div class="entry">
                <button type="submit">Submit</button>
                <button type="button" onclick="history.back()">Back</button>
            </div>
            <!-- End Form Buttons -->
    </form>
    <!-- End Form -->
</div>