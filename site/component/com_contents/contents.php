<?php
$objcontents = new PageCms();
if (isset($_POST["emailid"], $_POST["name"])) {
    $objcontents->contactUs($_POST);
    $gotolink = CreateLink(array("contents", "itemid" => "thanks"));
    redirectUrl($gotolink);
}
$contentdata = $objcontents->GetArticales($data["itemid"]);
$itfMeta = array("title" => $contentdata["pagetitle"], "description" => $contentdata["pagemetatag"], "keyword" => $contentdata["pagekeywords"])
?>
<?php if (!empty($contentdata)) { ?>

    <!--<div class="full_width_page main_mat">
        <p><a href="<?php echo SITEURL; ?>">Home</a> / <a href=""><?php echo $contentdata["pagetitle"]; ?></a></p>
    </div>-->
    <div class="content_main">

        <h1><?php echo $contentdata["pagetitle"]; ?></h1> </div>
    <?php echo $contentdata["logn_desc"]; ?>
    <div class="clear"></div>
    </div>

<?php } else {
    ?>
    <div class="content_main" style="text-align: center;">No Page Found !</div>
<?php } ?>
