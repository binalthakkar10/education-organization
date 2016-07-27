<?php
// Version 1.0 - Initial Version
// Version 1.1 - 6/3/16 - Enhancement#7 and #20 - Search in Class listing page

$cmsPageCms = new PageCms();
$pageDetails = $cmsPageCms->GetPageDetails('17');
$itfMeta = array("title" => $pageDetails["pagetitle"], "description" => $pageDetails["pagemetatag"],
    "keyword" => $pageDetails["pagekeywords"]);
$classObj = new Class1;
$utilsObj = new Utils;
$searchCriteria = array();
$searchCriteria['type'] = 2;
if ($_REQUEST['grade'] != '') {
    $searchCriteria['grade'] = $utilsObj->decryptIds($_REQUEST['grade']);
}
if ($_REQUEST['loc_id'] != '') {
    $searchCriteria['city'] = $utilsObj->decryptIds($_REQUEST['loc_id']);
} else {
    $searchCriteria['city'] = 0;
}

if (isset($_REQUEST['cityname']) && $_REQUEST['cityname'] != '') {
	$searchCriteria['cityname'] = $_REQUEST['cityname'];
} else {
	$searchCriteria['cityname'] = 0;
}
if (isset($_REQUEST['coursecode']) && $_REQUEST['coursecode'] != '') {
	$searchCriteria['coursecode'] = $_REQUEST['coursecode'];
} else {
	$searchCriteria['coursecode'] = 0;
}

$classList = $classObj->getClassListFrontend($searchCriteria);
$utilsObj = new Utils;	

$locationObj = new Location;
$cityNames = $locationObj->getCityName();
//echo '<pre>';print_r($cityNames);

$perpage = $stieinfo['paging_size'];
$urlpath = 'index.php?' . $_SERVER['QUERY_STRING'] . '&';
$urlpathSearch=$_SERVER['REQUEST_URI'];
$pagingobj = new ITFPagination($urlpath, $perpage);
$classList = $pagingobj->setPaginateData($classList);


?>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <!--<div id="page_title">
        <h1>About <span style="color:#ab281f;">Us</span></h1>
    </div>-->
        <div id="page_content">
            <div class="list_classes">
                <div id="table_top_list">
                    <span>Select City</span>
                    <span>
                       <select name="filterByCity" onchange="filterBycity(this.value);" class="selectpicker" data-live-search="true">
                        <option <?php if($_GET['city'] == ''){ ?> selected="selected" <?php } ?> value="">All Cities</option>
                        <?php foreach($cityNames as $cities) { ?>
                            <option <?php if($_GET['cityname'] == $cities['city']){ ?> selected="selected" <?php } ?> value="<?php echo $cities['city']; ?>"><?php echo $cities['city']; ?></option>
							<?php } ?>
                        </select>
                    </span>
                </div>
                   <script>
                    $('.selectpicker').selectpicker();
                function filterBycity(value) {
					<?php if(isset($_GET['city'])){ ?>
						var CityId = value;
						window.location.href = '<?php echo $urlpathSearch; ?>&cityname='+CityId; 
					<?php } else { ?>
						var CityId = value
						window.location.href = '<?php echo $urlpathSearch; ?>&cityname='+CityId;
					<?php } ?>
					
				}
                var maxHeight = 400;

                $(function(){

                    $(".dropdown > li").hover(function() {
                    
                         var $container = $(this),
                             $list = $container.find("ul"),
                             $anchor = $container.find("a"),
                             height = $list.height() * 1.1,       // make sure there is enough room at the bottom
                             multiplier = height / maxHeight;     // needs to move faster if list is taller
                        
                        // need to save height here so it can revert on mouseout            
                        $container.data("origHeight", $container.height());
                        
                        // so it can retain it's rollover color all the while the dropdown is open
                        $anchor.addClass("hover");
                        
                        // make sure dropdown appears directly below parent list item    
                        $list
                            .show()
                            .css({
                                paddingTop: $container.data("origHeight")
                            });
                        
                        // don't do any animation if list shorter than max
                        if (multiplier > 1) {
                            $container
                                .css({
                                    height: maxHeight,
                                    overflow: "hidden"
                                })
                                .mousemove(function(e) {
                                    var offset = $container.offset();
                                    var relativeY = ((e.pageY - offset.top) * multiplier) - ($container.data("origHeight") * multiplier);
                                    if (relativeY > $container.data("origHeight")) {
                                        $list.css("top", -relativeY + $container.data("origHeight"));
                                    };
                                });
                        }
                        
                    }, function() {
                    
                        var $el = $(this);
                        
                        // put things back to normal
                        $el
                            .height($(this).data("origHeight"))
                            .find("ul")
                            .css({ top: 0 })
                            .hide()
                            .end()
                            .find("a")
                            .removeClass("hover");
                    
                    });
                    
                    // Add down arrow only to menu items with submenus
                    $(".dropdown > li:has('ul')").each(function() {
                        $(this).find("a:first").append("<img src='images/down-arrow.png' />");
                    });
                    
                });
                </script>
                <!-- <div id="registration_top">
                     <form name="frm" id="frm" method="post" action="" class="map_cl">
                         <div>
                             <span>Show Camps by City</span>
                             <span>
                                 <select name="city" id="city"> 
                                     <option value="">Select City</option>
                <?php
                foreach ($cityNames as $values) {
                    if ($searchCriteria['city'] == $values["id"]) {
                        echo '<option value="' . $values["id"] . '"  selected="selected">' . $values["city"] . '</option>';
                    } else {
                        echo '<option value="' . $values["id"] . '">' . $values["city"] . '</option>';
                    }
                }
                ?>
                                 </select>
                             </span>
                             <span><button type="submit" name="submit" class="btn_sub">Submit</button></span>
                         </div>
                     </form>
                 </div>-->
                <table cellpadding="0" cellspacing="0" border="1">
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Location</th>
                            <th>Class</th>
                            <th>Eligibility</th>
                            <th>Number of Classes</th>
                            <th>Start Time</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Day(s) of Week</th>
                            <th>Duration</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($classList) > 0) {
                            foreach ($classList as $values) {
                                $startGrade = $classObj->getGradeDesc($values['start_eligibility']);
                                $endGrade = $classObj->getGradeDesc($values['end_eligibility']);
                                $encryptClassIds = $utilsObj->encryptIds($values['class_id']);
                                ?>
                                <tr>
                                    <td><?php echo $values['city'] ?></td>
                                    <td><?php echo $values['loc_name'] ?></td>
                                    <td><?php echo $values['course_name'] ?></td>
                                    <td><?php echo $startGrade['grade_desc'] . '----' . $endGrade['grade_desc'] ?></td>
                                    <td><?php echo $values['no_of_class'] ?></td>
                                    <td><?php echo $values['class_time'] ?></td>
                                    <td><?php echo date('m/d/Y', strtotime($values['start_date'])); ?></td>
                                    <td><?php echo date('m/d/Y', strtotime($values['end_date'])); ?></td>
                                    <td><?php echo $classObj->getDayofweek($values['day_of_week']) ?></td>
                                    <td><?php echo $values['duration'] ?></td>
                                    <td><a href="index.php?itfpage=summer_camps&itemid=summer_camp_detail&class_id=<?php echo $encryptClassIds ?>">More Info...</a></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="12">No Records Available!</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

            </div>
            <div class="entry">
                <div class="pagination">
                    <?php echo $pagingobj->Pages(); ?>
                </div>
                <div class="sep"></div>
            </div>
        </div>
    </div>
</div>