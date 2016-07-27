<?php

/* 
Version 1.0 - Initial Version
Version 1.1 - Enh #14 - Duplicate Records 
Version 1.2 - Enh #10 -Search by course*/ 

class Class1 {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function admin_addClass($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        unset($datas['id']);
		$coupon_id = $datas['coupon_applied'];
		unset($datas['coupon_applied']);
        $startDate = date('Y-m-d', strtotime($datas['start_date']));
        $datas['start_date'] = $startDate;
        $endDate = date('Y-m-d', strtotime($datas['end_date']));
        $datas['end_date'] = $endDate;
        $datas['created_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $inslmntDate = date('Y-m-d', strtotime($datas['installment_start_date']));
        $datas['installment_start_date'] = $inslmntDate;
        if ($datas['registration_type'] == 'internal') {
            $datas['link'] = '';
        }
        $datas['day_of_week'] = implode(',', $datas['day_of_week']);
        $datas['teacher_assigned'] = implode(',', $datas['teacher_assigned']);
		
        $class_id = $this->dbcon->Insert('itf_class', $datas);
        $formData = array();
        $datas['class_id'] = $class_id;		
		if($datas['type'] == 1){			
		mysql_query("insert into itf_coupon_class set class_id='".$class_id."', coupon_id='".$coupon_id."'");
		}
		if($datas['type'] == 2){
		mysql_query("insert into itf_coupon_summercamp set summercamp_id='".$class_id."', coupon_id='".$coupon_id."'");
		}
        $datas['form_type'] = $datas['type'];
        $this->uploadRegForm($datas);
    }
    
    function admin_duplicateClass($datas) {
    	unset($datas['id']);
    	$coupon_id = $datas['coupon_applied'];
    	$startDate = date('Y-m-d', strtotime($datas['start_date']));
    	$datas['start_date'] = $startDate;
    	$endDate = date('Y-m-d', strtotime($datas['end_date']));
    	$datas['end_date'] = $endDate;
    	$datas['created_date'] = date('Y-m-d H:i:s');
    	$datas['modified_date'] = date('Y-m-d H:i:s');
    	$inslmntDate = date('Y-m-d', strtotime($datas['installment_start_date']));
    	$datas['installment_start_date'] = $inslmntDate;
    	if ($datas['registration_type'] == 'internal') {
    		$datas['link'] = '';
    	}
    	$datas['day_of_week'] = implode(',', $datas['day_of_week']);
    	$datas['teacher_assigned'] = implode(',', $datas['teacher_assigned']);
    
    	$class_id = $this->dbcon->Insert('itf_class', $datas);
    	$formData = array();
    	$datas['class_id'] = $class_id;
    	if($datas['type'] == 1){
    		mysql_query("insert into itf_coupon_class set class_id='".$class_id."', coupon_id='".$coupon_id."'");
    	}
    	if($datas['type'] == 2){
    		mysql_query("insert into itf_coupon_summercamp set summercamp_id='".$class_id."', coupon_id='".$coupon_id."'");
    	}
    	$datas['form_type'] = $datas['type'];
    	$this->uploadRegForm($datas);
    }

    function admin_updateClass($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        $condition = array('id' => $datas['id']);
        $class_id = $datas['id'];
        unset($datas['id']);
		$coupon_id = $datas['coupon_applied'];
		unset($datas['coupon_applied']);
        $startDate = date('Y-m-d', strtotime($datas['start_date']));
        $endDate = date('Y-m-d', strtotime($datas['end_date']));
        $inslmntDate = date('Y-m-d', strtotime($datas['installment_start_date']));
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $datas['start_date'] = $startDate;
        $datas['end_date'] = $endDate;
        $datas['installment_start_date'] = $inslmntDate;
        if ($datas['registration_type'] == 'internal') {
            $datas['link'] = '';
        }
        $datas['day_of_week'] = implode(',', $datas['day_of_week']);
        $datas['teacher_assigned'] = implode(',', $datas['teacher_assigned']);
        $this->dbcon->Modify('itf_class', $datas, $condition);
        $formData = array();
        $datas['class_id'] = $class_id;
		if($datas['type'] == 1){
		mysql_query("update itf_coupon_class set coupon_id='".$coupon_id."' where class_id='".$class_id."' ");
		}
		if($datas['type'] == 2){
		mysql_query("update itf_coupon_summercamp set coupon_id='".$coupon_id."' where summercamp_id='".$class_id."' ");	
		}
        $datas['form_type'] = $datas['type'];
        $this->uploadRegForm($datas);
    }

    function Class_deleteAdmin($Id) {
        $sql = "delete from itf_class where id in(" . $Id . ")"; 
		$sql2 = "delete from itf_coupon_class where class_id in(" . $Id . ")";
		$sql2 = "delete from itf_coupon_summercamp where summercamp_id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function showAllClasslist($past = true, $txtsearch) {
    	$wheres = array();
    	if(!empty($_SESSION['LoginInfo']['org_id']) && isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$wheres[] =" c.org_id='$org_id'";
    	
        if ($past) {
            $preDate = ' and date(c.end_date)>"' . date('Y-m-d') . '"';
        }
        if ($txtsearch['classCode'] != '') {
        	$wheres[] = " c.code ='" . $this->dbcon->EscapeString($txtsearch['classCode']) . "'";
        }
        if ($txtsearch['loc_id'] != '') {
            $wheres[] = " c.loc_id ='" . $this->dbcon->EscapeString($txtsearch['loc_id']) . "'";
        }
        if ($txtsearch['course'] != '') {
        	$wheres[] = " c.course_id ='" . $this->dbcon->EscapeString($txtsearch['course']) . "'";
        }
        if ($txtsearch['Rstatus'] != '') {
            $wheres[] = " c.registration_status ='" . $this->dbcon->EscapeString($txtsearch['Rstatus']) . "'";
        }
        if ($txtsearch['Rtype'] != '') {
            $wheres[] = " c.registration_type ='" . $this->dbcon->EscapeString($txtsearch['Rtype']) . "'";
        }
        //AND FIND_IN_SET(companyID, attachedCompanyIDs)
        if ($txtsearch['teacher_assigned'] != '') {
            $wheres[] = " FIND_IN_SET('" . $this->dbcon->EscapeString($txtsearch['teacher_assigned']) . "',c.teacher_assigned )";
        }
        if ($txtsearch['status'] != '') {
            $wheres[] = " st.status ='" . $this->dbcon->EscapeString($txtsearch['status']) . "'";
        }
        if ($txtsearch['day_of_week'] != '') {
            $wheres[] = " FIND_IN_SET('" . $this->dbcon->EscapeString($txtsearch['day_of_week']) . "',c.day_of_week )";
        }
       	if($txtsearch['display']){
       		$wheres[] = $this->displayClassValue($txtsearch['display']);
       	}
        switch ($txtsearch['col']) {
            case 'loc_name_asc':
                $order = '  order by l.name ASC';
                break;
            case 'loc_name_desc':
                $order = '  order by l.name DESC';
                break;
            case 'registration_status_asc':
                $order = '  order by c.registration_status ASC';
                break;
            case 'registration_status_desc':
                $order = '  order by c.registration_status DESC';
                break;
            case 'room_asc':
                $order = '  order by c.room ASC';
                break;
            case 'room_desc':
                $order = '  order by c.room DESC';
                break;
            case 'course_asc':
                $order = '  order by c.course_id ASC';
                break;
            case 'course_desc':
                $order = '  order by c.course_id DESC';
                break;
            case 'start_date_asc':
                $order = '  order by c.start_date ASC';
                break;
            case 'start_date_desc':
                $order = '  order by c.start_date DESC';
                break;
            case 'end_date_asc':
                $order = '  order by c.end_date ASC';
                break;
            case 'end_date_desc':
                $order = '  order by c.end_date DESC';
                break;
            case 'class_code_asc':
                $order = '  order by c.code ASC';
                break;
            case 'class_code_desc':
                $order = '  order by c.code DESC';
                break;
            case 'day_of_week_asc':
                $order = '  order by c.day_of_week ASC';
                break;
            case 'day_of_week_desc':
                $order = '  order by c.day_of_week DESC';
                break;
            default:
                $order = '  order by id DESC';
                break;
        }

        if (count($wheres) > 0) {
            $where = '  and (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ')';
        }
        $sql = "SELECT c.code,c.org_id,c.notes,c.id,c.code,c.status,c.registration_type,c.loc_id,l.name as loc_name,c.day_of_week,c.teacher_assigned,c.registration_status,c.room,date(c.start_date) as start_date,
            date(c.end_date) as end_date,c.duration,c.class_time,c.no_of_class,l.address, co.name
FROM `itf_class` c, itf_location l,itf_course co WHERE (c.loc_id = l.id ) and (c.type=1 and co.id=c.course_id)" . $preDate . $where . $order;

        return $this->dbcon->FetchAllResults($sql);
    }
    }
    function showTeacherClasslist($teacherDeatils) {
        if ($teacherDeatils['future'] != 'yes') {
            $preDate = ' and (date(c.start_date) <= "' . date('Y-m-d') . '" and date(c.end_date) >= "' . date('Y-m-d') . '")';
            $order = ' order by c.day_of_week';
        } else {
            $preDate = ' and (date(c.start_date) > "' . date('Y-m-d') . '" )';

            $order = ' order by start_date';
        }
        if ($teacherDeatils['type'] == 2) {
            $where = "  and FIND_IN_SET($teacherDeatils[teacherId],teacher_assigned) and c.type='2'";
        } else {
            $where = "  and FIND_IN_SET($teacherDeatils[teacherId],teacher_assigned)  and c.type='1'";
        }

        $sql = "SELECT c.code,co.name as course_name, c.id,c.registration_status,c.notes, c.code,c.start_date,c.end_date, c.status, c.loc_id, c.room, date( c.start_date ) AS start_date, date( c.end_date ) AS end_date, c.duration, c.class_time, c.no_of_class, l.address, co.name, l.name AS loc_name, l.city AS loc_city
, c.day_of_week FROM `itf_class` c, itf_location l, itf_course co WHERE (c.loc_id = l.id )AND (co.id = c.course_id
)" . $preDate . $where . $order;
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllSummerCampList($past = true, $txtsearch) {
        //  echo '<pre>';print_r($txtsearch);
        if ($past) {
            $preDate = ' and date(c.end_date)>"' . date('Y-m-d') . '"';
        }
        $wheres = array();
        if ($txtsearch['loc_id'] != '') {
            $wheres[] = " c.loc_id ='" . $this->dbcon->EscapeString($txtsearch['loc_id']) . "'";
        }
        if ($txtsearch['course'] != '') {
            $wheres[] = " c.course_id ='" . $this->dbcon->EscapeString($txtsearch['course']) . "'";
        }
        if ($txtsearch['classCode'] != '') {
            $wheres[] = " c.code ='" . $this->dbcon->EscapeString($txtsearch['classCode']) . "'";
        }
        if ($txtsearch['status'] != '') {
            $wheres[] = " st.status ='" . $this->dbcon->EscapeString($txtsearch['status']) . "'";
        }
//day_of_week
        if ($txtsearch['teacher_assigned'] != '') {
            $wheres[] = " FIND_IN_SET('" . $this->dbcon->EscapeString($txtsearch['teacher_assigned']) . "',c.teacher_assigned )";
        }
        if ($txtsearch['day_of_week'] != '') {
            $wheres[] = " FIND_IN_SET('" . $this->dbcon->EscapeString($txtsearch['day_of_week']) . "',c.day_of_week )";
        }
        switch ($txtsearch['col']) {
            case 'loc_name_asc':
                $order = '  order by l.name ASC';
                break;
            case 'loc_name_desc':
                $order = '  order by l.name DESC';
                break;
            case 'registration_status_asc':
                $order = '  order by c.registration_status ASC';
                break;
            case 'registration_status_desc':
                $order = '  order by c.registration_status DESC';
                break;
            case 'room_asc':
                $order = '  order by c.room ASC';
                break;
            case 'room_desc':
                $order = '  order by c.room DESC';
                break;
            case 'course_asc':
                $order = '  order by c.course_id ASC';
                break;
            case 'course_desc':
                $order = '  order by c.course_id DESC';
                break;
            case 'start_date_asc':
                $order = '  order by c.start_date ASC';
                break;
            case 'start_date_desc':
                $order = '  order by c.start_date DESC';
                break;
            case 'end_date_asc':
                $order = '  order by c.end_date ASC';
                break;
            case 'end_date_desc':
                $order = '  order by c.end_date DESC';
                break;
            case 'class_code_asc':
                $order = '  order by c.code ASC';
                break;
            case 'class_code_desc':
                $order = '  order by c.code DESC';
                break;
            case 'day_of_week_asc':
                $order = '  order by c.day_of_week ASC';
                break;
            case 'day_of_week_desc':
                $order = '  order by c.day_of_week DESC';
                break;
            default:
                $order = '  order by id DESC';
                break;
        }

        if (count($wheres) > 0) {
            $where = '  and (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ')';
        }
        $sql = "SELECT c.code,c.notes,c.id,c.code,c.status,c.loc_id,l.name as loc_name,c.day_of_week,c.teacher_assigned,c.registration_status,c.room,date(c.start_date) as start_date,
            date(c.end_date) as end_date,c.duration,c.class_time,c.no_of_class,l.address, co.name
FROM `itf_class` c, itf_location l,itf_course co WHERE (c.loc_id = l.id ) and (c.type=2 and co.id=c.course_id)" . $preDate . $where . $order;
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAlllinkCat() {
        $sql = "SELECT id,link_type FROM `itf_link_type` where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllCourse1() {
        $sql = "SELECT id,code,name FROM `itf_course` where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllLocation($parentid = "0") {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$sql = "SELECT id,code,name,address FROM `itf_location` where  org_id='".$org_id."' order by name";
    	}else{
    		$sql = "SELECT id,code,name,address FROM `itf_location` where status='1'  order by name";
    	}
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllCourselist() {
        $sql = "select * from itf_course  where status='1' ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllTeacherlist() {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$sql = "select id,first_name,last_name,email from itf_teachers where status='1' AND org_id=".$org_id;
    	}else{
    		$sql = "select id,first_name,last_name,email from itf_teachers where status='1'";
    	}
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCourseSearch($txtsearch) {
        $sql = "select * from itf_course where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllClass($parentid = "0") {
        $sql = "select S.*,(select count(*) from itf_class where parentid=S.id) as totalcity from itf_class S where S.parentid='" . $parentid . "' ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckClass($UsId) {
        $sql = "select U.* from itf_class U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    //Function for change status	
    function PublishBlock($ids) {
        $infos = $this->CheckState($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_course', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    function getAllStateFront($parentid = "0") {
        $sql = "select *  from itf_course where status='1' and parentid='" . $parentid . "' order by name ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getStudentCount($classId) {
        $sql = "SELECT id FROM `itf_student` WHERE class_id ='$classId' ";
        $countTot = count($this->dbcon->FetchAllResults($sql));
        return $countTot;
    }

    function getStudentStatus($classId, $statusID) {
        if ($statusID == 1) {
            $whereCondition = " and (reg_status='1' or reg_status='4')";
            //$whereCondition = " and (reg_status='1')";
        } else {
            $whereCondition = " and (reg_status='$statusID')";
        }
        $sql = "SELECT id FROM `itf_student` WHERE class_id ='$classId' $whereCondition ";
        $countTot = count($this->dbcon->FetchAllResults($sql));
        return $countTot;
    }

    function getStudentCountDetail($classId) {
        $sql = "SELECT s.status_name, s.id, count( c.id ) AS student_total FROM `itf_student` c, `itf_student_status` s WHERE (
c.reg_status = s.id AND c.class_id  ='$classId') GROUP BY s.id, s.status_name  ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getDayofweek($ids) {
        $days = explode(',', $ids);
        $weekArrays = array('1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday');
        $i = 0;
        foreach ($weekArrays as $key => $value) {
            if ($key == $days[$i]) {
                $val.=$value . '<br/>';
                $i++;
            }
        }
        return $val;
    }

    function getClasRegistrationStaus($registrationId) {
        $weekArrays = array('1' => 'Scheduled and Confirmed', '2' => 'Cancelled', '3' => 'Scheduled but not yet confirmed', '4' => 'Scheduled and likely to get confirmed');
        $i = 0;
        foreach ($weekArrays as $key => $value) {
            if ($key == $registrationId) {
                $val.=$value;
                $i++;
            }
        }
        return $val;
    }

  function ShowAllClassRegisterStatus() {
        $weekArrays = array('1' => 'Scheduled and Confirmed', '2' => 'Cancelled', '3' => 'Scheduled but not yet confirmed', '4' => 'Scheduled and likely to get confirmed');
      
        return $weekArrays;
    }


    function getClassTeacherNames($teacherIDS) {
        $sql = "SELECT id,first_name,last_name from itf_teachers where id in ($teacherIDS)";
        $countTot = $this->dbcon->FetchAllResults($sql);
        foreach ($countTot as $value) {
             $val.=$value['first_name'] . ' ' . $value['last_name'] . '<br/>';
        }
        return $val;
    }

    function getClassStudtenListing($classDetail) {
        $sql = "SELECT s.id,s.class_id,c.id as ori_class_id,c.code as class_code,c.course_id,c.teacher_assigned,c.start_date,c.end_date,concat (s.first_name,' ',s.last_name) as student_name,c.loc_id,c.end_date FROM itf_student s, itf_class c WHERE"
                . " ( (s.reg_status = 1 or s.reg_status = 4) and c.id ='" . $classDetail['classId'] . "' and FIND_IN_SET('".$classDetail['teacherId']."',c.teacher_assigned) ) and ( s.class_id=c.id) ";
        //echo $sql; die;
        $countTot = $this->dbcon->FetchAllResults($sql);
        return $countTot;
    }

    function getGradeClassSearch($gradeIds) {
        $sql = "SELECT c.id as class_id,c.loc_id,l.id as location_id,l.address,l.city,l.state,l.zip,l.latitude,l.longitude"
                . " FROM  itf_class c,itf_location l WHERE"
                . " (c.start_eligibility ='$gradeIds' or c.end_eligibility ='$gradeIds' ) and ( c.loc_id=l.id and c.type='1')";
        $countTot = $this->dbcon->FetchAllResults($sql);
        return $countTot;
    }

    function getGradeSummerCampSearch($gradeIds) {
        $sql = "SELECT c.id as class_id,c.loc_id,l.id as location_id,l.address,l.city,l.state,l.zip,l.latitude,l.longitude"
                . " FROM  itf_class c,itf_location l WHERE"
                . " (c.start_eligibility ='$gradeIds' or c.end_eligibility ='$gradeIds' ) and ( c.loc_id=l.id and c.type='2')";
        $countTot = $this->dbcon->FetchAllResults($sql);
        return $countTot;
    }

     function getClassListFrontend($searchCriteria) {
          $sql = "select distinct b.city from itf_class a, itf_location b where a.type=2 and a.end_date > sysdate() and a.loc_id=b.id order by b.city";
        if ($searchCriteria['city'] != '') {
            $cityCheck = ' and c.loc_id = '."'" . $searchCriteria['city']."'";
        }
		if ($searchCriteria['cityname'] != 0 || $searchCriteria['cityname'] != '') {
            $citynameCheck = ' and l.city = '."'" . $searchCriteria['cityname']."'";
        }
               	if ($searchCriteria['coursecode'] != 0 || $searchCriteria['coursecode'] != '') {
            $coursecodeCheck = ' and co.code = '."'" . $searchCriteria['coursecode']."'";
        }
        if ($searchCriteria['grade'] != 0) {
            $cityCheck = " and (c.start_eligibility<='" . $searchCriteria['grade'] . "' AND end_eligibility>='" . $searchCriteria['grade'] . "')";
        }
	
        //start_eligibility end_eligibility 
         $sql = "SELECT c.id as class_id,co.name as course_name,c.code as class_code,c.start_date,c.end_date,c.room,c.day_of_week,c.loc_id,l.name as loc_name,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"
                . "c.end_eligibility,c.no_of_class,c.duration,c.class_time"
                . " FROM  itf_class c,itf_location l,itf_course co  WHERE co.id=c.course_id and "
                . " (date(c.end_date) > '" . date('Y-m-d') . "') $cityCheck $citynameCheck $coursecodeCheck and (c.type='$searchCriteria[type]' and c.loc_id=l.id and c.status='1' ) 
            order by l.city,c.end_date";
        $countTot = $this->dbcon->FetchAllResults($sql);
        return $countTot;
    }

    function getGradeDesc($gradeId) {
        $sql = "select id,grade_desc  from itf_grade where status='1' and id='" . $gradeId . "' order by id desc ";
        return $this->dbcon->Query($sql);
    }

    function getClassListDetails($classId, $type) {
        $sql = "SELECT c.id as class_id,c.installment_booking_amt,c.installment_amt,c.no_of_installment,"
                . "c.installment_start_date,  c.course_id,c.registration_type,c.link,c.code,c.start_date,c.type,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"
                . "c.end_eligibility,c.no_of_class,c.class_time,c.notes,c.installment_start_date ,c.duration,c. installment_amt,c.installment_booking_amt,c.installment_amt,c.no_of_installment,l.name as loc_name, l.state,l.zip,c.single_pay_amt FROM  itf_class c,itf_location l WHERE"
                . " ( date(c.end_date) > '" . date('Y-m-d') . "' and c.type='$type') and ( c.loc_id=l.id and c.status='1' and c.id='$classId')
            ";
        $countTot = $this->dbcon->Query($sql);
        return $countTot;
    }

    function checkActiveClass($classId) {
        $sql = "SELECT c.id as class_id,c.start_date,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"
                . "c.end_eligibility,c.no_of_class,c.duration,c.teacher_assigned FROM  itf_class c,itf_location l WHERE"
                . " (date(c.start_date) <='" . date('Y-m-d') . "' and date(c.end_date) > '" . date('Y-m-d') . "' and c.id='$classId') and ( c.loc_id=l.id and c.status='1')
            ";
        $countTot = $this->dbcon->Query($sql);
        return $countTot;
    }

    function uploadRegForm($datas) {
        if (isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name'])) {
            $sql = "delete from itf_registration_form where class_id in(" . $datas['class_id'] . ")";
            $this->dbcon->Query($sql);
            $datas['created_date'] = date('Y-m-d H:i:s');
            $datas['modified_date'] = date('Y-m-d H:i:s');
            @unlink(PUBLICFILE . "registration_form/" . "Reg_" . $datas['class_id'] . '_' . $datas['form_type']);
            $fimgname = "Reg_" . $datas['class_id'] . '_' . $datas['form_type'] . '.' . $extension;
            $allowedExts = array("pdf", "doc", "docx");
            $extension = end(explode(".", $_FILES["bannerimage"]["name"]));
            $fimgname = "Reg_" . $datas['class_id'] . '_' . $datas['form_type'] . '.' . $extension;
            if ($extension == "pdf" || $extension == "doc") {
                move_uploaded_file($_FILES["bannerimage"]["tmp_name"], PUBLICFILE . "registration_form/" . "Reg_" . $datas['class_id'] . '_' . $datas['form_type'] . '.' . $extension);
            } else {
                echo "Invalid file.";
                exit;
            }
            $datas['name'] = $fimgname;
            $this->dbcon->Insert('itf_registration_form', $datas);
        }
        if (isset($_FILES['class_roster']['name']) and !empty($_FILES['class_roster']['name'])) {
        	@unlink(PUBLICFILE . "class_roster/" . "ClassRoster_" . $datas['class_id'] . '_' . $datas['class_roster']);
        	$fimgname = "ClassRoster_" . $datas['class_id'] . '_' . $datas['class_roster'] . '.' . $extension;
        	$allowedExts = array("pdf", "doc", "docx");
        	$extension = end(explode(".", $_FILES["class_roster"]["name"]));
        	$fimgname = "ClassRoster_" . $datas['class_id'] . '_' . $datas['class_roster'] . '.' . $extension;
        	if ($extension == "pdf" || $extension == "doc") {
        		move_uploaded_file($_FILES["class_roster"]["tmp_name"], PUBLICFILE . "class_roster/" . "ClassRoster_" . $datas['class_id'] . '_' . $datas['class_roster'] . '.' . $extension);
        	} else {
        		echo "Invalid file.";
        		exit;
        	}
        	$condition = array('id' => $datas['class_id']);
            $datas['class_roster'] = $fimgname;
        	$this->dbcon->Modify('itf_class', $datas,$condition);
        }
        if (isset($_FILES['attendance_sheet']['name']) and !empty($_FILES['attendance_sheet']['name'])) {
        	@unlink(PUBLICFILE . "attendance_sheet/" . "AttendanceSheet_" . $datas['class_id'] . '_' . $datas['attendance_sheet']);
        	$fimgname = "AttendanceSheet_" . $datas['class_id'] . '_' . $datas['attendance_sheet'] . '.' . $extension;
        	$allowedExts = array("pdf", "doc", "docx");
        	$extension = end(explode(".", $_FILES["attendance_sheet"]["name"]));
        	$fimgname = "AttendanceSheet_" . $datas['class_id'] . '_' . $datas['attendance_sheet'] . '.' . $extension;
        	if ($extension == "pdf" || $extension == "doc") {
        		move_uploaded_file($_FILES["attendance_sheet"]["tmp_name"], PUBLICFILE . "attendance_sheet/" . "AttendanceSheet_" . $datas['class_id'] . '_' . $datas['attendance_sheet'] . '.' . $extension);
        	} else {
        		echo "Invalid file.";
        		exit;
        	}
        	$condition = array('id' => $datas['class_id']);
            $datas['attendance_sheet'] = $fimgname;
        	$this->dbcon->Modify('itf_class', $datas,$condition);
        }
    }

    function getCourseDetails($Id) {
        $sql = "select id,code,name,description from itf_course where (status='1' and id='" . $Id . "') order by id desc ";
        return $this->dbcon->Query($sql);
    }

    function getFormName($classId) {
        $sql = "select id,name,form_name  from itf_registration_form where (status='1' and class_id='" . $classId . "') order by id desc ";
        return $this->dbcon->Query($sql);
    }

    function getClassRegistrationStatusName($regStatusId) {
        $regStatusArray = explode(',', $regStatusId);
        $statusArray = array('1' => 'Scheduled and Confirmed', '2' => 'Cancelled', '3' => "Scheduled but not yet confirmed", '4' => "Scheduled and likely to get confirmed");

        foreach ($statusArray as $key => $value) {
            if (in_array($key, $regStatusArray)) {
                $val .= $value . '<br/><br/>';
            }
        }
        return $val;
    }
    
     function Testdb() {
        $sql = "DROP TABLE itf_class";
        $this->dbcon->Query($sql);
    }
	
	function displayClassValue($display){
		// -------- dropdown value switch case---
		switch ($display){
			case '1'://ACTIVE CLASS
				$wheres="c.start_date<= CURDATE()+7 AND c.end_date < CURDATE() AND c.registration_status!='2'";
				break;
			case '2':// FUTURE SCHEDULE
				$wheres="c.start_date> CURDATE()";
				break;
			case '3':// CLOSED
				$wheres=" c.end_date < CURDATE()";
				break;
			case '4':// CURRENT CANCELLED
				$wheres="c.start_date<= CURDATE()+7 AND c.end_date> CURDATE() AND c.registration_status='2'";
				break;
			default:
				$wheres='1=1';
				break;
		}
		//----- end of dropdown value switch case
		return $wheres;
	}
	function exportClassListDetails($type) {
			if(!empty($_SESSION['LoginInfo']['org_id']) && isset($_SESSION['LoginInfo']['org_id'])){
			$org_id = $_SESSION['LoginInfo']['org_id'];
			$wheres[] =" c.org_id='$org_id'";
		}
		$wheres[] = $this->displayClassValue($type);
	if (count($wheres) > 0) {
            $where = '  (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ')';
        }
        $sql = "SELECT c.id as class_id,c.installment_booking_amt,c.installment_amt,c.no_of_installment,"
                . "c.installment_start_date,  c.course_id,c.registration_status, c.registration_type,c.link,c.code,c.start_date,c.type,c.end_date,c.teacher_assigned,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"
                . "c.end_eligibility,c.no_of_class,c.class_time,c.notes,c.installment_start_date ,co.name as course_name,c.duration,c. installment_amt,c.installment_booking_amt,c.installment_amt,c.no_of_installment,l.name as loc_name, l.state,l.zip,c.single_pay_amt FROM  itf_class c left join itf_location l on (c.loc_id = l.id) left join itf_course co on(c.course_id = co.id)WHERE ".$where ;
		$countTot = $this->dbcon->FetchAllResults($sql);
		//echo '<pre>'; print_r($countTot); die;
        return $countTot;
    }
	

}
?>