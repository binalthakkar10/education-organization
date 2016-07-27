<?php
error_reporting(0);
$objpages = new PageCms();
$pagemenus = $objpages->GetMenuCms();
$objMenu = new Menu;
$parentMenu = $objMenu->ShowAllActiveParentMenu();
if (empty($itfMeta["title"])) {
    $itfMeta = array("title" => "Home", "description" => "GURUS EDUCATIONAL SERVICES", "keyword" => "GURUS EDUCATIONAL SERVICES");
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
        <title><?php echo $itfMeta['title'] . " | " . $stieinfo["sitename"]; ?></title>
        <link href="<?php echo TemplateUrl(); ?>css/style.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo TemplateUrl(); ?>css/slide.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo TemplateUrl(); ?>css/bootstrap-select.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo TemplateUrl(); ?>js/jquery.min.js"></script>
        <script src="<?php echo TemplateUrl(); ?>js/bootstrap-select.js"></script>
        <script src="<?php echo TemplateUrl(); ?>js/jquery.slides.min.js"></script>
        <script src="<?php echo TemplateUrl(); ?>js/jquery.resmenu.js"></script>

    </head>
    <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1374538622771142";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <div class="main_container">
            <!-- _________________________header Section_________________________ -->
            <div class="header_container">
                <?php if ($_REQUEST['itemid'] != 'print_student_listing' && $_REQUEST['itemid'] != 'print_admin_student_listing') { ?>
                    <div class="header">
                        <div class="logo"><a id="logo" href="index.php" title="Gurus Educational Services"><h1>Gurus Educational Services</h1></a></div> 
                        <div id="home_page_title">
                            <h1>Gurus Educational Services</h1>
                            <p>Where you learn from Gurus</p>
                        </div>
                        <div class="navigation menu_container">
                            <ul id="menu" class="toresponsive">

                                <?php
                                for ($i = 0; $i < count($parentMenu); $i++) {
                                    if ($parentMenu[$i]["link"] != '') {
                                        if ($parentMenu[$i]["is_show"] != 0) {
                                            $pageName = $objMenu->getPageName($parentMenu[$i]["link"]);
                                            $linkParmentMenu = "index.php?itfpage=contents&itemid=" . $pageName["name"];
                                        } else {


                                            $pageName = $parentMenu[$i]["link"];
                                            $linkParmentMenu = $pageName;
                                        }
                                    }
                                    echo '<li class="drop_menu" ><a href="' . $linkParmentMenu . '" id="parent_' . $i . '">' . $parentMenu[$i]["name"] . '</a>';

                                    $childMenu = $objMenu->ShowAllActiveChildMenu($parentMenu[$i]["id"]);

                                    if (count($childMenu) > 0) {
                                        // ss
                                        echo '<ul id="sub_menu">';
                                        for ($j = 0; $j < count($childMenu); $j++) {
                                            $pageChildName = '';
                                            if ($childMenu[$j]["link"] != '') {
                                                if ($childMenu[$j]["is_show"] != 0) {
                                                    $pageChildName = $objMenu->getPageName($childMenu[$j]["link"]);
                                                    $link = "index.php?itfpage=contents&itemid=" . $pageChildName["name"];
                                                } else {
                                                    $pageChildName = $childMenu[$j]["link"];
                                                    $link = $pageChildName;
                                                }
                                            }
                                            echo '<li><a href="' . $link . '" title="Programs">' . $childMenu[$j]["name"] . ' f</a></li>';
                                        }
                                        echo '</ul></li>';
                                    } else {
                                        echo '</li>';
                                    }
                                }

                                if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
                                    echo '<li class="drop_menu"><a href="' . SITEURL . '/index.php?itfpage=teacherlogin" id="parent_6">Teacher Login</a>';
                                } else {
                                    echo '<li class="drop_menu"><a href="' . SITEURL . '/index.php?itfpage=teacher" id="parent_6">Dashboard</a>';
                                }
                                /*   echo '<li class="drop_menu"><a href="' . SITEURL . '/index.php?itfpage=teacherlogin" class="active">Login</a>'; */
                                ?>

                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <div class="main_wrapper" id="mid_wrapper">
                    <?php
                    $page = isset($_GET['itfpage']) ? $_GET['itfpage'] : "";
                    $cms = isset($_GET['itemid']) ? $_GET['itemid'] : '';
                    ?>
                    <?php flashMsg(); ?>
                    <?php echo $itf_bodydata; ?>
                </div>
                <!-- end main_wrapper Section -->
            </div><!-- end main_container -->
        </div>
        
        <?php include("test.php"); ?>
        <!-- _________________________footer Section_________________________ -->
        <?php if ($_REQUEST['itemid'] != 'print_student_listing' && $_REQUEST['itemid'] != 'print_admin_student_listing') { ?>

            <div class="footer_main">
                <div class="footer">
                    <ul>
                        <h3>Quick Links</h3>
                        <li><a href="index.php?itfpage=classes">+ Class Registration</a></li>
                        <li><a href="index.php?itfpage=summer_camps">+ Summer Camps</a></li>
                        <li><a href="index.php?itfpage=contents&itemid=tournaments">+ Tournaments</a></li>
                    </ul>
                    <ul>
                        <br/><br/>
                        <li><a href="index.php?itfpage=contents&itemid=privacy-policy">+ Privacy Policy</a></li>
                        <li><a href="index.php?itfpage=contents&itemid=who-we-are">+ About Us</a></li>
                    </ul>
                    <ul>
                        <h3>Contact Us Now</h3>
                        <li>Phone No: +1-510-573-2497</li>
                        <li>Email Id: info@guruseducation.com</li>
                    </ul>
                </div>
                <div class="copyright">
                    <div class="copyright_inner">
                        <div id="copy_rgt">Copyright © 2015 Gurus Educational Services.  All rights reserved.</div>
                        <div id="design_by">Design & Developed By: <a href="http://www.shahdeepinternational.com/" title="shahdeepinternational" target="_blank">Shah Deep International</a></div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- end footer Section -->
        <script>
            $(function() {
                var pgurl = window.location.href.substr(window.location.href
                        .lastIndexOf("/") + 1);
                // alert( '          ' + pgurl);
                $("#menu li a").each(function() {
                    if ($(this).attr("href") == pgurl || $(this).attr("href") == '')
                        $(this).addClass("active");

                    if ('index.php?itfpage=contents&itemid=public-speaking-programs' == pgurl 
                    || 'index.php?itfpage=contents&itemid=debates-program' == pgurl
                            || 'index.php?itfpage=contents&itemid=personal-finance' == pgurl
                            || 'index.php?itfpage=contents&itemid=programs-for-adults' == pgurl
                            || 'index.php?itfpage=contents&itemid=popular-speech-topics' == pgurl ||
                            'index.php?itfpage=contents&itemid=tournaments' == pgurl
                            )
                        $('#parent_1').addClass("active");

                       if ( 'index.php?itfpage=classes' == pgurl || 'index.php?itfpage=summer_camps' == pgurl
                            || 'index.php?itfpage=tournament&itemid=calendar' == pgurl
                            )
                        $('#parent_2').addClass("active");
                        
                    if ('index.php?itfpage=contents&itemid=be-a-guru' == pgurl
                            )
                        $('#parent_3').addClass("active");
                        

                    if ('index.php?itfpage=contents&itemid=who-we-are' == pgurl || 'index.php?itfpage=contents&itemid=our-staff' == pgurl || 'index.php?itfpage=student&itemid=testimonials' == pgurl
                            || 'index.php?itfpage=contents&itemid=press-room' == pgurl || '<?php echo $_REQUEST["itfpage"] ?>' == 'photogallery' || 'index.php?itfpage=contents&itemid=testimonials' == pgurl || 'index.php?itfpage=contents&itemid=faq-and-contact-us' == pgurl || 'index.php?itfpage=contents&itemid=contact-us' == pgurl || 'index.php?itfpage=contents&itemid=franchise-opportunity' == pgurl)
                        $('#parent_4').addClass("active");
                        
                    if ('<?php echo $_REQUEST["itfpage"] ?>' == 'teacherlogin' || '<?php echo $_REQUEST["itfpage"] ?>' == 'teacher' )
                        $('#parent_5').addClass("active"); 
                        

                    if (pgurl == '')
                        $('#parent_0').addClass("active");
                    // $('.active').closest('sub_menu li.dropdown').addClass('active');
                })
            });
            $(function() {
                $('#slides').slidesjs({
                    width: 1000,
                    height: 326,
                    play: {
                        active: true,
                        auto: true,
                        interval: 4000,
                        swap: true
                    }
                });
            });
        </script>
        <script>
            $(function() {
                var pgurl = window.location.href.substr(window.location.href
                        .lastIndexOf("/") + 1);
                // alert( '          ' + pgurl);
                $("#menu li a").each(function() {
                    if ($(this).attr("href") == pgurl || $(this).attr("href") == '')
                        //$("#menu li").addClass("current-menu-item");
                        $('.active').closest('#menu li').addClass('liclass');
                })
            });

            $(window).ready(function() {
                $('.toresponsive').ReSmenu({
                });
            });

        </script>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-76509355-1', 'guruseducation.com');
            ga('send', 'pageview');

        </script>
		<script>
        $(document).ready(function(e) {
            $('.liclass').children().show();		
			
        });
        </script>
    </body>
</html>