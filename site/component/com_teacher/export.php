<?php

if (empty($_SESSION['teacherLoginInfo']['user_id'])) {
    redirectUrl(CreateLink(array("teacherlogin")));
    exit;
}
#error_reporting(E_ALL);
#ini_set('display_errors', 'On');
$_REQUEST['requestFrom'] = 'teacher';
$_REQUEST['student_id'] = '10';

$teacherId = $_SESSION['teacherLoginInfo']['user_id'];
$studentsObj = new Student;
$aruArray = array();
$aruArray['studendId'] = $_GET['studendId'];
$studentDetails = $studentsObj->getStudentDetails($aruArray);
//echo '<pre>';print_r($studentDetails);exit;
if ($_REQUEST['requestFrom'] == 'teacher' && $_REQUEST['student_id'] != '') {

    function checkValueInArray($search, $attendanceDetail) {
        ///  echo '<br/>----------' . $search;
        $i = 0;
        foreach ($attendanceDetail as $value) {
            if (array_search($search, $value)) {
                $key = array_search($search, $value);
                if ($value['attendance'] == 1) {
                    return 'Persent';
                } else {
                    return 'Absent';
                }
            }
            $i++;
        }
        return ' ';
    }

    //$downloadInfo = $studentsObj->getStudentsDownload($_REQUEST'type'');

    $datas['class_id'] = $studentDetails['class_id'];
    $datas['student_id'] = $studentDetails['student_id'];
    $dat[] = array('First Name' => $studentDetails['first_name'],
        'Last Name' => $studentDetails['last_name']);
    // $_REQUEST['attendanceMonth'] = '04';
    //$_REQUEST['attendanceYear'] = '2014';
    if ($_REQUEST['attendanceMonth'] != '' && $_REQUEST['attendanceYear'] != '') {

        $datas['start_date'] = $_REQUEST['attendanceYear'] . '-' . $_REQUEST['attendanceMonth'] . '-01';

        $endMonthDate = date('t', strtotime($datas['start_date']));
        $datas['end_date'] = $_REQUEST['attendanceYear'] . '-' . $_REQUEST['attendanceMonth'] . '-' . $endMonthDate;
        $attendanceDetail = $studentsObj->getStudentAttandanceDetail($datas);

        for ($i = 1; $i <= $endMonthDate; $i++) {
            //if
            if (strlen($i) < 2) {
                $i = '0' . $i;
            }
            $currentDate = date("Y-m-$i");
            $checkValue = checkValueInArray($currentDate, $attendanceDetail);
            $dat[0][$_REQUEST['attendanceMonth'] . '/' . $i] = $checkValue;
        }
    } else {
        $datas['start_date'] = date('Y-m-') . '1';

        $endMonthDate = date('t', strtotime($datas['start_date']));
        $datas['end_date'] = date('Y-m-') . $endMonthDate;
        $attendanceDetail = $studentsObj->getStudentAttandanceDetail($datas);
        for ($i = 1; $i <= $endMonthDate; $i++) {
            //if
            if (strlen($i) < 2) {
                $i = '0' . $i;
            }
            $currentDate = date("Y-m-$i");
            $checkValue = checkValueInArray($currentDate, $attendanceDetail);
            $dat[0][date('m') . '/' . $i] = $checkValue;
        }
    }
    $name = $studentDetails['first_name'] . '_' . $studentDetails['last_name'];
    $obj = new ITFExport($dat);
    $obj->download($name);
}
?>
