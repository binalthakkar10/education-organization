<?php
#error_reporting(E_ALL);
#ini_set('display_errors','On');
$cmsPageCms = new PageCms();
$pageDetails = $cmsPageCms->GetPageDetails('17');
$utilsObj = new Utils;
$classId = $_GET['class_id'];
$classId = $utilsObj->decryptIds($_GET['class_id']);

$itfMeta = array("title" => $pageDetails["pagetitle"], "description" =>
    $pageDetails["pagemetatag"], "keyword" => $pageDetails["pagekeywords"]);
$type = 2;
$classObj = new Class1;
$classDetail = $classObj->getClassListDetails($classId, $type);

$courseDetails = $classObj->getCourseDetails($classDetail['course_id']);
?>
<div class="main_wrapper" id="mid_wrapper">
    <div class="full_width_page">
        <!--<div id="page_title">
        <h1>About <span style="color:#ab281f;">Us</span></h1>
    </div>-->
        <div id="page_content">
            <div class="class_listing_top">
                <div id="title"><h1>Location</h1></div>
                <p>
                    <span><?php echo $classDetail['loc_name'] ?></span>
                    <span><?php echo $classDetail['address'] ?></span>
                    <span><?php echo $classDetail['city'] ?></span>
                    <span><?php echo $classDetail['state'] ?></span>
                    <span><?php echo $classDetail['zip'] ?></span>
                </p>
            </div>
            <div class="class_listing_bottom">
                <div id="title"><h1>Summer Camps</h1></div>
                <div id="table_listing">
                    <table cellpadding="0" cellspacing="0" border="1">
                        <tbody>
                            <tr>
                                <th>Program Code</th>
                                <td><?php echo $courseDetails['code'] ?></td>
                            </tr>
                            <tr>
                                <th>Program Description</th>
                                <td><?php echo $courseDetails['description'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Eligibility</th>
                                <td><?php
                                    $classObj->getGradeDesc($classDetail['start_eligibility']);
                                    $startGrade = $classObj->getGradeDesc($classDetail['start_eligibility']);
                                    $endGrade = $classObj->getGradeDesc($classDetail['end_eligibility']);
                                    if ($startGrade['grade_desc'] != '') {
                                        echo $startGrade['grade_desc'] . ' to ' . $endGrade['grade_desc'];
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <th>Number of Classes</th>
                                <td><?php echo $classDetail['no_of_class'] ?></td>
                            </tr>
                            <tr>
                                <th>Start Date</th>
                                <td><?php echo date('m/d/Y', strtotime($classDetail['start_date'])); ?></td>
                            </tr>
                            <tr>
                                <th>End Date</th>
                                <td><?php echo date('m/d/Y', strtotime($classDetail['end_date'])); ?></td>
                            </tr>
                            <tr>
                                <th>Day(s) of Week</th>
                                <td><?php echo $classObj->getDayofweek($classDetail['day_of_week']) ?></td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td><?php echo $classDetail['duration'] ?></td>
                            </tr>
                            <tr>
                                <th>Single Amount</th>
                                <td><?php
                                    $saveAmount = $classDetail['installment_booking_amt'] + $classDetail['installment_amt'] * $classDetail['no_of_installment'] - $classDetail['single_pay_amt'];
                                    if ($classDetail['installment_booking_amt'] != '0.00' && $classDetail['registration_type'] != 'external')
                                        $saveText = ' (SAVE  ' . $stieinfo['currency_prefix'] . $saveAmount . ')';
                                    else
                                        $saveText = '';
                                    echo $stieinfo['currency_prefix'] . $classDetail['single_pay_amt'] . $saveText;
                                    ?></td>
                            </tr>
                            <?php if ($classDetail['installment_booking_amt'] != '0.00' && $classDetail['registration_type'] != 'external') {
                                ?>
                                <tr>
                                    <th>Installment Option</th>
                                    <td>
                                        <?php if ($classDetail['installment_booking_amt'] != '0.00') {
                                            ?>
                                            Initial payment of <?php echo $stieinfo['currency_prefix'] . $classDetail['installment_booking_amt'] ?><br/>
                                            <?php echo $classDetail['no_of_installment'] ?> equal monthly installments of <?php echo $stieinfo['currency_prefix'] . $classDetail['installment_amt'] ?>
                                            starting <?php echo date('m/d/Y', strtotime($classDetail['installment_start_date'])); ?>
                                        <?php } ?></td>
                                </tr>
                                <!--<tr>
                                    <th>Installment Booking Amount</th>
                                    <td><?php echo $stieinfo['currency_prefix'] . $classDetail['installment_booking_amt'] ?></td>
                                </tr>
                                <tr>
                                    <th>Installment Amount </th>
                                    <td><?php echo $stieinfo['currency_prefix'] . $classDetail['installment_amt'] ?></td>
                                </tr>
                                <tr>
                                    <th>No of Installment </th>
                                    <td><?php echo $classDetail['no_of_installment'] ?></td>
                                </tr>
                                <tr>
                                    <th>Installment Start Date</th>
                                    <td><?php echo date('m/d/Y', strtotime($classDetail['installment_start_date'])); ?></td>
                                </tr>-->
                            <?php } ?>
                            <tr>
                                <th>Other Information</th>
                                <td><?php echo $classDetail['notes'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    if (strtotime($classDetail['start_date']) < strtotime(date('Y-m-d'))) {
                        echo '<div style="color:#FF0000;text-align: right;margin-top: 15px;font-size:12px;"><b>This class is in session. Please call or email us before registering online</b></div>';
                    }
                    ?>
                    <?php
                    $iDs = $utilsObj->encryptIds($classDetail['class_id']);

                    if ($classDetail['registration_type'] == 'external') {
                        // redirectUrl($classDetail['link']);
                        echo '<div id="btn_button"><a class="button_btn_all" href="' . $classDetail['link'] . '" target="_blank">Register</a></div>';
                    } else {
                        ?>
                        <div id="btn_button"><a class="button_btn_all" href="index.php?itfpage=student&type=123EEDDD&class_id=<?php echo $iDs ?>">Register</a></div>

                    <?php } ?>
                </div>
            </div>
           
        </div>
    </div>
</div>
