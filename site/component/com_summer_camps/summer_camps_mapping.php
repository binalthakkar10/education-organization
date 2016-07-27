/* Version 1.0 - Initial Version
Version 1.1 - 6/3/16 - Enhancement # 12 - Locations with registration enabled/disabled
*/

<?php
$cmsPageCms = new PageCms();
$pageDetails = $cmsPageCms->GetPageDetails('39');
$itfMeta = array("title" => $pageDetails["pagetitle"], "description" => $pageDetails["pagemetatag"],
    "keyword" => $pageDetails["pagekeywords"]);
$gradeObj = new Grade();
$gradesList = $gradeObj->showAllActiveGrade();

$searchCriteria = array();
$searchCriteria['type'] = 2;

$locationObj = new Location();

if ($_REQUEST['grade'] != '') {
    $searchCriteria['grade'] = $_REQUEST['grade'];
} else {
    $searchCriteria['grade'] = 0;
    $searchCriteria['registration_type'] = '2';
}
$locDetails = $locationObj->getLocationDetailForMap($searchCriteria, 2);
$validJs = TemplateUrl() . "js2/jquery.validate.js";


?>
<script src="http://maps.google.com/maps/api/js?sensor=false" 
type="text/javascript"></script>
<script src="<?php echo $validJs ?>"></script>
<script>

    $(document).ready(function() {

        $('#frm').validate({// initialize the plugin
            errorElement: "span",
            rules: {
                grade: {
                    required: true
                }
            },
            messages: {
                grade: "Please select grade",
            }
        });


    });
</script>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <!--<div id="page_title">
        <h1>About <span style="color:#ab281f;">Us</span></h1>
    </div>-->
        <div id="page_content">
            <p><?php echo $pageDetails['logn_desc'] ?></p> <div class="registration">
                <div id="registration_top">
                    <form name="frm" id="frm" method="post" action="" class="map_cl">
                        <div class="mapbox">
                            <span>Show Camps by Grade</span>
                            <span>
                                <select name="grade" id="grade"> 
                                    <option value="">Select Grade</option>
                                    <?php
                                    foreach ($gradesList as $values) {
                                        if ($searchCriteria['grade'] == $values["id"]) {
                                            echo '<option value="' . $values["id"] . '"  selected="selected">' . $values["grade_desc"] . '</option>';
                                        } else {
                                            echo '<option value="' . $values["id"] . '">' . $values["grade_desc"] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </span>
                            <span><button type="submit" name="submit" class="btn_sub">Submit</button></span>
                        </div>
                    </form>
                    <div style="text-align:left;" class="class_list_link"><a href="index.php?itfpage=summer_camps&itemid=summer_camps_listing">List of all Summer Camps</a></div>
                </div>
                <div id="registration_bottom">  <div id="map"></div><!--<img src="images/registration.jpg" />--></div>
            </div>
        </div>
    </div>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->

<script type="text/javascript">

    var locations = [
<?php echo $locDetails ?>
    ];
   // alert(locations)
    if (locations != '')
    {
        //var cen = parseInt(locations.length / 2);
        //  alert(cen);
        var cen = 0;
        // alert(locations);
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: new google.maps.LatLng(locations[cen][1], locations[cen][2]),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();
        var marker, i;

        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent('<div style="height:180px;width:400px;">' + locations[i][0] + '</div>');
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    } else
    {
        document.getElementById('map').innerHTML = "<div style='color:red;font-size:14'>Sorry, We do not have any active classes for this grade at this time. We'll open registration for next session shortly. Sign up for our newsletter and we'll let you know when registration opens up.</div>";
//alert('Test');
    }
</script>
