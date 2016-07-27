<?php
error_reporting(0);
Html::AddCssFile(TemplateUrl() . "css/jScrollPane.css");
Html::AddCssFile("itfbox/itfboxcss.css");
Html::AddJsFile("itfbox/itfboxpack.js");
Html::AddJsFile(TemplateUrl() . "js/jquery.mousewheel.js");
Html::AddJsFile(TemplateUrl() . "js/jScrollPane.js");
Html::AddJsFile(TemplateUrl() . "js/swissarmy.js");

if (isset($_POST['email']) && $_POST['email'] != "Enter your Email Id") {
//echo $_POST['email'];exit;
    if (!empty($_POST['email']) && $_POST['email'] != "Enter your Email Id" && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $obj = new Newsletter();
        if ($obj->checkSubscriber($_POST["email"])) {
            $obj->addSubscriber($_POST["email"], 'Subscriber_Web');
            echo '<script>alert("Your Email Id is successfully subscribed.")</script>';
            $_POST['email'] = '';
            // flashMsg("Your Email Id is successfully subscribed.");
            //redirectUrl(SITEURL);
        } else {
            echo '<script>alert("This Email Id is already registered.")</script>';
            $_POST['email'] = '';
            //  flashMsg("This Email Id is already registered", "3");
            // redirectUrl(SITEURL);
        }
    } else {
        flashMsg("Please enter valid Email Id", "2");
        redirectUrl(SITEURL);
    }
}
$objSlidingImage = new SlidingImage;
$slidingImage = $objSlidingImage->getAllBannerFront();
$validJs = TemplateUrl() . "js2/jquery.validate.js";
?>
<style>
#newsletter input.error {
    border: 1px solid #FF0000;
}
#newsletter label.error {
    display: none !important;
}
</style>
<script src="<?php echo $validJs ?>"></script>
<script>

    $(document).ready(function() {

        $('#subscribe').validate({// initialize the plugin
            rules: {
                email: {
                    required: true,
                    email: true
                }
            }
        });


    });
</script>
<div class="responsive_slide">
    <div id="slides">
        <?php
        for ($i = 0; $i < count($slidingImage); $i++) {
            echo '<img src="' . PUBLICPATH . 'banner/' . $slidingImage[$i]["image_name"] . '" alt="" width="1000" height="326">';
        }
        ?>
    </div>
</div>
<div class="left_page">
    <!--
    <div id="home_page_title">
        <h1>Bay Area Debate Club</h1>
        <p>Providing Public Speaking, Debate and Confidence Building programs.</p>
    </div>
    -->
    <div id="program">
        <div id="title">&nbsp;</div>
        <ul>
            <li><a href="<?php echo CreateLink(array("contents", "itemid" => 'programs-for-children')); ?>" <?php
                if ($cms == 'help') {
                    echo 'class="active"';
                }
                ?> ><img src="<?php echo SITEURL ?>/template/default/images/program1.jpg" border="0"/><span>Programs for Children</a></span></li>
            <li><a href="<?php echo CreateLink(array("contents", "itemid" => 'programs-for-adults')); ?>" <?php
                if ($cms == 'help') {
                    echo 'class="active"';
                }
                ?> ><img src="<?php echo SITEURL ?>/template/default/images/program2.jpg"  border="0"/><span>Programs for Adults</a></span></li>
            <li><a href="<?php echo CreateLink(array("contents", "itemid" => 'tournaments')); ?>" <?php
                if ($cms == 'help') {
                    echo 'class="active"';
                }
                ?> ><img src="<?php echo SITEURL ?>/template/default/images/program3.jpg"  border="0"/><span>Tournaments</a></span></li>
        </ul>
    </div>
</div>
<div class="right_siderbar">
    <div id="fb_connect">
        <!-- <fb:like href="https://www.facebook.com/pages/Bayareadebateclub/378255839133" width="165" layout="button" action="like" show_faces="false" share="true"></fb:like>-->

        <!--<fb:like href="https://www.facebook.com/pages/Bayareadebateclub/378255839133" width="165" layout="button" action="like" show_faces="false" share="true"></fb:like>-->
        <a href="https://www.facebook.com/pages/Bayareadebateclub/378255839133" target="_blank"><img src="<?php echo SITEURL ?>/template/default/images/connect_fb.png" alt="Connect with us on Facebook" title="Connect with us on Facebook" /></a></div>
    <form id="subscribe" name="subscribe" method="post" action="<?php echo CreateLink(array('index')); ?>">
        <div id="newsletter">
            <label> <input type="text" size="20" name="email" value="Enter your Email Id" onfocus="this.value = ''" /></label>
            <input type="submit" name="submit" value="Subscribe" class="buttons">
            <!--<button type="button" value="Subscribe" title="Subscribe">Subscribe</button>-->
        </div>
    </form>
</div>

<script type="text/javascript">
    var classname = window.location.href.substr(window.location.href
            .lastIndexOf("/") + 1);
    jQuery(document).ready(function(e) {
        if (classname == 'index.php') {
            jQuery('body').addClass('home');
        }
        if (classname == 'trunk') {
            jQuery('body').addClass('home');
        }
        if (classname == '') {
            jQuery('body').addClass('home');
        }
        if (classname == 'index.php?itfpage=index') {
            jQuery('body').addClass('home');
        }
    });

</script>