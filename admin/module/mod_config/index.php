<?php
$siteobj=new Site();
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';
$pagetitle = "Site Configuration";
$ItfInfoData = $siteobj->CheckSite(1);
include(BASEPATHS."/fckeditor/fckeditor.php");
?>


<script type="text/javascript">

    $(document).ready(function() {

        var Validator = jQuery('#itffrminput').validate({
            rules: {

                sitename: "required",
                paging_size: {required:true, number:true},
          	 emailid: {
                    required: true,
                    email: true
                },

            },
            messages: {

                sitename: " Please enter site name.",
		paging_size: " Please enter Paging Size."

            }
        });
    });
</script>
<?php
if(isset($_POST['id']))
{
    if(!empty($_POST['id']))
    {
        $siteobj->admin_update($_POST);
        flash("Configuration is successfully updated");
        redirectUrl("itfmain.php?itfpage=".$currentpagenames);
    }
}
?>
<div class="full_w">
    <!-- Page Head -->
    <div class="h_title"><?php echo $pagetitle;?></div>

    <form action="" method="post" name="itffrminput" id="itffrminput">
        <input type="hidden" name="id" id="id" value="<?php echo isset($ItfInfoData['id'])?$ItfInfoData['id']:'' ?>" />

            <div class="element">
                <label>Site Title<span class="red">(required)</span></label>
                <input class="text" name="sitename" type="text"  id="sitename" value="<?php echo isset($ItfInfoData['sitename'])?$ItfInfoData['sitename']:'' ?>" />
            </div>
<div class="element">
                <label>Site Email<span class="red">(required)</span></label>
                <input class="text" name="emailid" type="text"  id="emailid" value="<?php echo isset($ItfInfoData['emailid'])?$ItfInfoData['emailid']:'' ?>" />
            </div>
            <div class="element">
                <label>Keyword</label>
                <input class="text" name="keyword" type="text"  id="keyword" value="<?php echo isset($ItfInfoData['keyword'])?$ItfInfoData['keyword']:'' ?>" />
            </div>

            <div class="element">
                <label>Meta Tag</label>
                <input class="text" name="meta_tag" type="text"  id="meta_tag" value="<?php echo isset($ItfInfoData['meta_tag'])?$ItfInfoData['meta_tag']:'' ?>" />
            </div>

          <div class="element">
                <label>Paging Size <span class="red">(required)</span></label>
                <input class="text" name="paging_size" type="text"  id="paging_size" value="<?php echo isset($ItfInfoData['paging_size'])?$ItfInfoData['paging_size']:'' ?>" />
            </div>

         

            <div class="element">
                <label>Currency Prefix</label>
                <input class="text" name="currency_prefix" type="text"  id="currency_prefix" value="<?php echo isset($ItfInfoData['currency_prefix'])?$ItfInfoData['currency_prefix']:'' ?>" />
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