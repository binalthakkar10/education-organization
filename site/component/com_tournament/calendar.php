<?php
$tounamentObj = new Tournament;
$aruArray = array();
$aruArray['teacherId'] = $teacherId;
$aruArray['classId'] = $_GET['classId'];
$tournamentList = $tounamentObj->ShowAllActiveTournaments();
$utilsObj = new Utils;
?>
<div class="full_width_page">
    <div id="page_title">
        <h1>Calendar</h1>
    </div>
    <div id="page_content">
        <div class="calender">
            <ul>
                <?php
                $i = 0;
                if(count($tournamentList)>0)
                {
                foreach ($tournamentList as $values) {
                    $i++;
                    $encryptTornamentIds = $utilsObj->encryptIds($values['id']);
                    ?>
                    <li>
                        <a href="index.php?itfpage=tournament&itemid=calendar_details&calID=<?php echo $encryptTornamentIds ?>"> 
                            <img src="<?php echo PUBLICPATH . "tournament_image/" . $values['image']; ?>" width="224" height="149"/></a>
                        <div id="calender_content">
                            <h2> <!-- <a class='text_1' href="index.php?itfpage=tournament&itemid=calendar_details&calID=<?php echo $values['id'] ?>"> --> <?php echo $values['title'] ?> <!-- </a> --></h2>
                            <span>Event Location:  <?php echo $values['loc_name'].'&nbsp;'.$values['location_address'] ?></span>
                            <span><?php echo $values['start_time'] . '  (' . date('d M Y', strtotime($values['tournament_date'])) . ')' ?></span>
                            <?php
                            if ($values['external_url'] != '') {
                                echo '<a  href="' . $values['external_url'] . '" target="_blank">VIEW DETAIL</a>';
                            } else {
                                echo '<a href = "index.php?itfpage=tournament&itemid=calendar_details&calID=' . $encryptTornamentIds . '">VIEW DETAIL</a>';
                            }
                            ?>
                        </div> 
                    </li>
                <?php }
                }
                else {
                    echo '<li><div id="calender_content"><h2>We currently do not have tournament scheduled. '
                    . 'We are regularly scheduling new tournaments. Please check back again. Subscribe'
                    . ' to our newsletter or like us on Facebook to get updates as new tournaments are added.</h2></div></li>';
                }
?>

        </div>
    </div>
</div>
</div>
<!-- end main_wrapper Section -->
</div><!-- end main_container -->

