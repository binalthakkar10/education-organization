<?php

class Student {

    private $username;
    private $password;
    private $name;
    private $uertype;
    private $UserStatus;
    public $dbcon;

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function userLogin($uname, $pass) {
        $sql = "select * from itf_student where email='" . $this->dbcon->EscapeString($uname) . "' and password='" . md5($this->dbcon->EscapeString($pass)) . "' and status ='1' ";
        if ($DD = $this->dbcon->Query($sql)) {
            $_SESSION['FRONTUSER'] = $DD;
            return $DD;
        } else
            return '0';
    }

    function logout() {
        session_unset();
    }

    function GradeByClass($classId) {
        $sql = "select ci.id as class_id,ci.start_eligibility  as start_grade,ci.end_eligibility  as end_grade from itf_class ci where  ci.id=$classId";
        return $this->dbcon->Query($sql);
    }

    function adminAdd($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        unset($datas['id']);
        $datas['created_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
        if ($datas['date_cancel'] != '')
            $datas['date_cancel'] = date('Y-m-d', strtotime($datas['date_cancel']));

        $this->dbcon->Insert('itf_student', $datas);
        return $this->dbcon->LastInsertId();
    }

    function adminUpdate($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        //$datas['ass_class'] = implode(',', $datas['ass_class']);
        if ($datas['date_cancel'] != '') {
            $datas['date_cancel'] = date('Y-m-d', strtotime($datas['date_cancel']));
        }
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $this->dbcon->Modify('itf_student', $datas, $condition);
    }

    function deleteStudentDetails($Id) {
        $sql = "delete from itf_student where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function ShowAllStudent() {
        $sql = "select *  from itf_student";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllActiveStudent() {
        $sql = "select *  from itf_student where reg_status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function SearchStudent($txtsearch) {
        $wheres = array();
        if(!empty($_SESSION['LoginInfo']['org_id']) && isset($_SESSION['LoginInfo']['org_id'])){
        	$org_id = $_SESSION['LoginInfo']['org_id'];
        	$wheres[] =" org_id='$org_id'";
        }
        if ($txtsearch['loc_id'] != '') {
            $wheres[] = " loc_id ='" . $this->dbcon->EscapeString($txtsearch['loc_id']) . "'";
        }
        if ($txtsearch['course'] != '') {
            $wheres[] = " course_id ='" . $this->dbcon->EscapeString($txtsearch['course']) . "'";
        }
        if ($txtsearch['classCode'] != '') {
            $wheres[] = " class_id ='" . $this->dbcon->EscapeString($txtsearch['classCode']) . "'";
        }
        if ($txtsearch['reg_status'] != '') {
            $wheres[] = " reg_status ='" . $this->dbcon->EscapeString($txtsearch['reg_status']) . "'";
        }
        switch ($txtsearch['col']) {
            case 'first_name_asc':
                $order = '  order by first_name ASC';
                break;
            case 'first_name_desc':
                $order = '  order by first_name DESC';
                break;
            case 'last_name_asc':
                $order = '  order by last_name ASC';
                break;
            case 'last_name_desc':
                $order = '  order by last_name DESC';
                break;
            case 'email_asc':
                $order = '  order by primary_email ASC';
                break;
            case 'email_desc':
                $order = '  order by primary_email DESC';
                break;
            case 'status_asc':
                $order = '  order by status ASC';
                break;
            case 'status_desc':
                $order = '  order by status DESC';
                break;

            default:
                $order = '  order by id DESC';
                break;
        }
        //$where = "  where  (status ='1' and type='1')";
        $where = "  where  ( (status ='1' or status='4') and source LIKE 'Class%')";
        if (count($wheres) > 0) {
            $where .= '  and (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ')';
        }
        $sql = "select * from itf_student    " . $where . $order;
        return $this->dbcon->FetchAllResults($sql);
    }

    function searchSummerStudents($txtsearch) {
        $wheres = array();
        if ($txtsearch['loc_id'] != '') {
            $wheres[] = " loc_id ='" . $this->dbcon->EscapeString($txtsearch['loc_id']) . "'";
        }
        if ($txtsearch['course'] != '') {
            $wheres[] = " course_id ='" . $this->dbcon->EscapeString($txtsearch['course']) . "'";
        }
        if ($txtsearch['classCode'] != '') {
            $wheres[] = " class_id ='" . $this->dbcon->EscapeString($txtsearch['classCode']) . "'";
        }
        if ($txtsearch['reg_status'] != '') {
            $wheres[] = " reg_status ='" . $this->dbcon->EscapeString($txtsearch['reg_status']) . "'";
        }
        switch ($txtsearch['col']) {
            case 'first_name_asc':
                $order = '  order by first_name ASC';
                break;
            case 'first_name_desc':
                $order = '  order by first_name DESC';
                break;
            case 'last_name_asc':
                $order = '  order by last_name ASC';
                break;
            case 'last_name_desc':
                $order = '  order by last_name DESC';
                break;
            case 'email_asc':
                $order = '  order by primary_email ASC';
                break;
            case 'email_desc':
                $order = '  order by primary_email DESC';
                break;
            case 'status_asc':
                $order = '  order by status ASC';
                break;
            case 'status_desc':
                $order = '  order by status DESC';
                break;

            default:
                $order = '  order by id DESC';
                break;
        }
        //$where = "  where  (status ='1' and type='2')";
        $where = "  where  ( (status ='1' or status='4') and source='Summer_Camp_Web')";
        if (count($wheres) > 0) {
            $where .= '  and (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ')';
        }
        $sql = "select * from itf_student    " . $where . $order;
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckStudent($UsId) {
        $sql = "select U.* from itf_student U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function ShowAllStatus() {
        $sql = "select *  from itf_student_status where status = '1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowStudentCancelStatus() {
        $sql = "select *  from itf_student_cancellation_reason where status = '1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getStudentCancelStatusName($id) {
        $sql = "select reason as cancel_name from itf_student_cancellation_reason where id = '$id'";
        return $this->dbcon->Query($sql);
    }

    function ShowAllPaymentOption() {
        $sql = "select *  from itf_student_payment_option where status = '1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllClassCode($type = 1) {
        $where = '  and date(end_date)>"' . date('Y-m-d') . '"';
        if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
        	$org_id = $_SESSION['LoginInfo']['org_id'];
        	$datas['org_id'] = $org_id;
        	$sql = "select id, code,course_id,loc_id  from itf_class where type=$type AND org_id=$org_id $where group by code order by code asc";
        }else {
       		$sql = "select id, code,course_id,loc_id  from itf_class where type=$type $where group by code order by code asc";
        }
        return $this->dbcon->FetchAllResults($sql);
    }
 function ShowAllClassCodeAdmin() {
        $where = '  date(end_date)>"' . date('Y-m-d') . '"';
       $sql = "select id,code,course_id,loc_id  from itf_class where " . $where;
        return $this->dbcon->FetchAllResults($sql);
    }


    function getClassInfo($classId) {
        //  echo $classId;
        $where = '  and date(end_date)>"' . date('Y-m-d') . '" and  (l.id=c.loc_id and co.id=c.course_id) ';
        $sql = "select c.id as class_id,c.start_eligibility,c.end_eligibility,c.code as class_code ,l.id as loc_id,co.id as course_id,l.name as loc_name,l.code as loc_code,co.name as course_name"
                . "  from itf_class c, itf_location l, itf_course co where c.id='$classId' " . $where;
        return $this->dbcon->Query($sql);
    }

    function ShowAllSummerClassCode() {
        $where = '  and date(end_date)>"' . date('Y-m-d') . '"';
        $sql = "select id,code,course_id,loc_id  from itf_class where type=2" . $where;
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCourseName() {
        $sql = "select id,name  from itf_course where status='1' order by name asc";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CourseNameByClass($classId) {

        $sql = "SELECT co.id, name FROM itf_course co, itf_class ci WHERE ci.course_id = co.id and ci.id=$classId";
        return $this->dbcon->FetchAllResults($sql);
    }

    function LocationNameByClass($classId) {
        $sql = "select lo.id,lo.code,lo.name,lo.address from itf_location lo,itf_class ci where (ci.loc_id = lo.id and lo.status='1') and ci.id=$classId";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllLocation() {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    		$sql = "select lo.id,lo.code,lo.name,lo.address from itf_location lo where status='1'AND org_id='".$org_id."' order by lo.name asc";
    	}else{
    		$sql = "select lo.id,lo.code,lo.name,lo.address from itf_location lo where status='1' order by lo.name asc";
    	}
        
        return $this->dbcon->FetchAllResults($sql);
    }

    function getStatusbyID($ids) {
        $sql = "select status_name  from itf_student_status where id = '" . $ids . "'";
        return $this->dbcon->Query($sql);
    }

    //Function for change status	

    function PublishBlock($ids) {
        //echo 'Amit';

        $infos = $this->CheckStudent($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');
        $condition = array('id' => $ids);

        $this->dbcon->Modify('itf_student', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    function forgotPasswordAdmin($tomail) {
        $userdetail = $this->CheckEmailId($tomail);
        if (isset($userdetail['id'])) {
            $newpass = "mag" . substr(time(), -4);
            $datas = array('password' => md5($newpass));
            $condition = array('id' => $userdetail['id']);
            unset($datas['id']);
            $this->dbcon->Modify('users', $datas, $condition);

            $objuser = new User(); //User object
            $maildatavalue = $objuser->GetEmail(9);

            $objmail = new ITFMailer();
            $objmail->SetSubject($maildatavalue['mailsubject']);
            $objmail->SetBody($maildatavalue['mailbody'], array('username' => $userdetail['username'], "password" => $newpass));
            $objmail->MailSend();
            return true;
        } else
            return false;
    }

    //WebService

    function register($datas) {
        unset($datas['id']);
        $datas["password"] = md5($datas["password"]);
        $datas["usertype"] = "2";
        $userid = $this->dbcon->Insert('itf_student', $datas);
        $objuser = new User(); //User object
        $maildatavalue = $objuser->GetEmail(2);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('name' => $datas['name'], "emailid" => $datas['email'], "password" => "------"));
        $objmail->SetTo($datas['email']);
        $objmail->MailSend();
        return $userid;
    }

    function CheckEmailId($emailid) {
        $sql = "select * from itf_student where email='" . $emailid . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return true;
        else
            return false;
    }

    function GetInfoByEmailId($emailid) {
        $sql = "select * from itf_student where email='" . $emailid . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function UserCheckUsername($username) {
        $sql = "select * from itf_student where username='" . $username . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['username']) and !empty($datas['username']))
            return "1";
        else
            return "0";
    }

    function NormalUserLogin($datainfo) {
        $sql = "select id,name,last_name,username,email from  itf_student where  (email='" . $this->dbcon->EscapeString($datainfo['username']) . "' or username = '" . $this->dbcon->EscapeString($datainfo['username']) . "')
		        and password='" . md5($this->dbcon->EscapeString($datainfo['password'])) . "' and status ='1'";
        if ($DD = $this->dbcon->Query($sql)) {
            return $DD;
        } else
            return '0';
    }

    function getStudentDetails($studentArray) {
        $sql = "select s.id as student_id,c.id as class_id,t.id as teacher_id,c.course_id,s.first_name,s.primary_email,s.primary_name,s.primary_rel,s.sec_name,sec_rel,sec_contact,s.last_name,concat(s.first_name,' ',s.last_name)"
                . " as student_name,s.primary_contact,s.sec_contact,s.sec_email,date(c.start_date) as startDate,date(c.end_date) as endDate,"
                . "concat(t.first_name,' ',t.last_name) as teacher_name,c.code as class_code   from itf_student s,itf_class c,"
                . "itf_teachers t where (s.class_id=c.id and FIND_IN_SET(t.id, c.teacher_assigned) > 0) and s.id in ('" . $studentArray['studendId'] . "')  ";
        return $this->dbcon->Query($sql);
    }

    function getStudentDownloadAll($class_id, $teacher_id) {
        $sql = "select s.id as student_id,c.id as class_id,t.id as teacher_id,c.course_id,s.first_name,s.primary_email,s.primary_name,s.primary_rel,s.sec_name,sec_rel,sec_contact,s.last_name,concat(s.first_name,' ',s.last_name)"
                . " as student_name,s.primary_contact,s.sec_contact,s.sec_email,date(c.start_date) as startDate,date(c.end_date) as endDate,"
                . "concat(t.first_name,' ',t.last_name) as teacher_name,c.code as class_code   from itf_student s,itf_class c,"
                . "itf_teachers t where (s.class_id=c.id and FIND_IN_SET($teacher_id, c.teacher_assigned) > 0) and FIND_IN_SET(t.id, c.teacher_assigned) > 0 and c.id ='" . $class_id . "' and s.status='1' ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getStudentsDownload($type = 1, $class_id = '', $teacherId = '') {
    	if ($class_id != '') {
    		$wheres[] = " c.id ='$class_id' and FIND_IN_SET($teacherId,teacher_assigned)";
    	} else {
    		$wheres[] = " c.type ='$type'";
    	}
    	
    	if(!empty($_SESSION['LoginInfo']['org_id']) && isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$wheres[] =" s.org_id='$org_id'";
    	}
    	if (count($wheres) > 0) {
    		$where .= '(' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ')';
    	}
        
        $sql = "select s.id as student_id,c.id as class_id,c.type,c.start_date,c.end_date,s.paypal_txn_id,s.payment_amt,s.installment_booking_amt,s.no_of_installments,s.installment_amt,s.installment_start_date,c.room,c.notes,c.course_id,s.first_name,s.primary_email,s.primary_name,s.primary_rel,s.sec_name,sec_rel,"
                . "sec_contact,s.last_name,concat(s.first_name,' ',s.last_name) as student_name,s.primary_contact,s.sec_contact,s.sec_email,date(c.start_date) as startDate,date(c.end_date) as endDate,g.grade_desc,s.payment_option,s.created_date as student_created_date,l.name as loc_name,l.address as loc_address,co.name as course_name,c.code as class_code,sp.payment_name as payment_option_name,st.status_name from itf_student s,itf_class c,itf_location l,itf_course co,itf_grade g,itf_student_payment_option sp,itf_student_status st where s.class_id=c.id and  (l.id=s.loc_id and co.id=s.course_id) and (g.id=s.grade) and (sp.id=s.payment_option and st.id=s.reg_status and $where) "
                . "and (s.reg_status = 1 or s.reg_status = 4) ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getStudentsAdminPrint($class_id) {
        if ($class_id != '') {
            //FIND_IN_SET('1',preferred_categories);
            $where = " c.id ='$class_id'";
        }
         $sql = "select s.id as student_id,c.id as class_id,c.room,c.notes,c.course_id,"
                . "s.first_name,s.primary_email,s.primary_name,s.primary_rel,s.sec_name,sec_rel,sec_contact,s.last_name,concat(s.first_name,' ',s.last_name) as student_name,"
                . "s.primary_contact,s.sec_contact,s.sec_email,date(c.start_date) as startDate,date(c.end_date) as endDate,g.grade_desc,s.payment_option,"
                . "s.created_date as student_created_date,l.name as loc_name,"
                . "l.address as loc_address,co.name as course_name,c.code as class_code,sp.payment_name as payment_option_name,st.status_name from "
                . " itf_student s,itf_class c,itf_location l,itf_course co,itf_grade g,itf_student_payment_option sp,itf_student_status st "
                . "where s.class_id=c.id and  (l.id=s.loc_id and co.id=s.course_id) and (g.id=s.grade) and (sp.id=s.payment_option and st.id=s.reg_status and $where) and (s.reg_status = 1 or s.reg_status = 4)  "; 

/*$sql = "select s.id as student_id,c.id as class_id,c.room,c.course_id,"
                . "s.first_name,s.primary_email,s.primary_name,s.primary_rel,s.sec_name,sec_rel,sec_contact,s.last_name,concat(s.first_name,' ',s.last_name) as student_name,"
                . "s.primary_contact,s.sec_contact,s.sec_email,date(c.start_date) as startDate,date(c.end_date) as endDate,g.grade_desc,s.payment_option,"
                . "s.created_date as student_created_date,l.name as loc_name,"
                . "l.address as loc_address,co.name as course_name,c.code as class_code,sp.payment_name as payment_option_name,st.status_name from "
                . " itf_student s,itf_class c,itf_location l,itf_course co,itf_grade g,itf_student_payment_option sp,itf_student_status st "
                . "where s.class_id=c.id and  (l.id=s.loc_id and co.id=s.course_id) and (g.id=s.grade) and (sp.id=s.payment_option and st.id=s.reg_status and $where) and (s.reg_status = 1)  ";*/
        return $this->dbcon->FetchAllResults($sql);
    }

    function getStudentsDetailInfo($studentId) {
        $sql = "select s.id as student_id,c.id as class_id,c.course_id,s.first_name,s.primary_email,s.primary_name,s.primary_rel,s.sec_name,sec_rel,sec_contact,s.last_name,concat(s.first_name,' ',s.last_name) as student_name,s.primary_contact,s.sec_contact,s.sec_email,date(c.start_date) as startDate,date(c.end_date) as endDate,g.grade_desc,s.payment_option,s.created_date as student_created_date,l.name as loc_name,co.name as course_name,c.code as class_code,sp.payment_name as payment_option_name,st.status_name from itf_student s,itf_class c,itf_location l,itf_course co,itf_grade g,itf_student_payment_option sp,itf_student_status st where s.class_id=c.id and  (l.id=s.loc_id and co.id=s.course_id) and (g.id=s.grade) and (sp.id=s.payment_option and st.id=s.reg_status "
                . "and s.id ='" . $studentId . "') and s.status='1' ";
        return $this->dbcon->Query($sql);
    }

    function getStudentsDetailByClass($classId) {
        $sql = "select s.id as student_id,c.id as class_id,c.course_id,s.first_name,s.primary_email,s.primary_name,s.primary_rel,s.sec_name,sec_rel,sec_contact,s.last_name,concat(s.first_name,' ',s.last_name) as student_name,s.primary_contact,s.sec_contact,s.sec_email,date(c.start_date) as startDate,date(c.end_date) as endDate,g.grade_desc,s.payment_option,s.created_date as student_created_date,l.name as loc_name,co.name as course_name,c.code as class_code,sp.payment_name as payment_option_name,st.status_name from itf_student s,itf_class c,itf_location l,itf_course co,itf_grade g,itf_student_payment_option sp,itf_student_status st where s.class_id=c.id and  (l.id=s.loc_id and co.id=s.course_id) and (g.id=s.grade) and (sp.id=s.payment_option and st.id=s.reg_status "
        . "and c.id ='" . $classId . "') and s.status='1' ";
        return $this->dbcon->Query($sql);
    }

    function checkStudentAttendance($datas) {
        $sql = "select id,class_date from itf_student_attendance where status='1' and (class_id='" . $datas['class_id'] . "' and student_id='" . $datas['student_id'] . "' and class_date='" . $datas['class_date'] . "')";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['id']))
            return $datas['id'];
        else
            return "0";

        $datas['creadet_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $datas['date_cancel'] = date('Y-m-d', strtotime($datas['date_cancel']));
        $this->dbcon->Insert('itf_student', $datas);
    }

    function getStudentAttandanceDetail($datas) {
        $sql = "select id,attendance,class_date from itf_student_attendance where"
                . " status='1' and (class_id='" . $datas['class_id'] . "' and student_id='" . $datas['student_id'] . "' "
                . "and class_date>='" . $datas['start_date'] . "' and class_date<='" . $datas['end_date'] . "')";
        return $datas = $this->dbcon->FetchAllResults($sql);
    }

    function studentAttandance($datas) {
        //echo '<pre>';print_r($datas);
        $checkAttandance = $this->checkStudentAttendance($datas);

        // $this->adminUpdate($datas);
        if ($checkAttandance == 0) {
            $datas['id'] = '';
            $datas['created_date'] = date('Y-m-d H:i:s');
            $datas['modified_date'] = date('Y-m-d H:i:s');
            $datas['status'] = 1;
            $this->dbcon->Insert('itf_student_attendance', $datas);
        } else {
            $condition = array('id' => $checkAttandance['id']);
            unset($datas['id']);
            if ($checkAttandance['class_date'] == '0000-00-00')
                $datas['modified_date'] = date('Y-m-d H:i:s');
            $this->dbcon->Modify('itf_student_attendance', $datas, $condition);
        }
    }

    function studentAttandanceBackup($datas) {
        $checkAttandance = $this->checkStudentAttendance($datas);
        $this->adminUpdate($datas);
        if ($checkAttandance == 0) {
            $datas['id'] = '';
            $datas['created_date'] = date('Y-m-d H:i:s');
            $datas['modified_date'] = date('Y-m-d H:i:s');
            $datas['status'] = 1;
            $this->dbcon->Insert('itf_student_attendance', $datas);
        } else {
            $condition = array('id' => $checkAttandance['id']);
            unset($datas['id']);
            if ($checkAttandance['class_date'] == '0000-00-00')
                $datas['modified_date'] = date('Y-m-d H:i:s');
            $this->dbcon->Modify('itf_student_attendance', $datas, $condition);
        }
    }

    function studentTournamentRegistration($datas) {
        unset($datas['id']);
        $datas["created_date"] = date('Y-m-d H:i:s');
        $datas["modified_date"] = date('Y-m-d H:i:s');
        $datas["attend_tournament"] = $datas['attend_tournament'];
        $datas['topic_id'] = implode(',', $datas['topic_id']);

        $datas['reg_status'] = 3;
        $datas['status'] = 0;
        $userid = $this->dbcon->Insert('itf_tournament_student', $datas);

        return $userid;
    }

    function getTournamentStudentDetails($id) {
        $sql = "select * from itf_tournament_student where  id='" . $id . "'";
        return $this->dbcon->Query($sql);
    }

    function cancelRegistration($datas) {
        $checkStudentData = $this->checkStudentForCancellation($datas);

        $cancelReasonName = $this->getStudentCancelStatusName($datas['cancel_reason_id']);

     //   print_r($checkStudentData);
        $studentDetail = $this->getStudentInfo($checkStudentData['id']);
        $studentDetail['reason_cancel'] = $cancelReasonName['cancel_name'];
		$datas['reason_cancel'] = $cancelReasonName['cancel_name'];
        if ($cancelReasonName['cancel_name'] == 'Other Reasons') {
            $studentDetail['reason_cancel'] = $cancelReasonName['cancel_name'] . '<br/>Cancel Detail :' . $datas['cancel_detail'];
      		$datas['reason_cancel'] = $cancelReasonName['cancel_name'] . '<br/>Cancel Detail :' . $datas['cancel_detail'];	
	    }
        /*if ($studentDetail['student_id'] != '') {*/
		
		//////////////Added
		$emailDetails = $datas;
		
		////////////Added
            global $stieinfo;
            $classObj = new Class1;
            $teacherDetails = $classObj->checkActiveClass($checkStudentData['class_id']);

            if ($teacherDetails['teacher_assigned'] != '') {
                $teacherDetail = $this->getTeacherDetails($teacherDetails['teacher_assigned']);
                $studentDetail['teacher_email'] = $teacherDetail;
                $emailDetails['email_to'] = $studentDetail['teacher_email'];
               // $studendDetails = $this->studentCancelAdminMail($studentDetail); 
				 $studendDetails = $this->studentCancelAdminMail($emailDetails); 
				
            }
            $emailDetails['email_to'] = $stieinfo['emailid'];
            $studendDetails = $this->studentCancelAdminMail($emailDetails);
            $emailDetails['email_to'] = $checkStudentData['primary_email'];
             //$studendDetails = $this->studentCancelMail($studentDetail);
			 $studendDetails = $this->studentCancelMail($emailDetails);
			
       /* } else {
            return 0;
        }*/
    }

    function checkStudentForCancellation($datas) {
       $sql = "select id,class_id,primary_email,first_name from itf_student where status='1' and (primary_email='" . $datas['primary_email'] . "' "
                . "and primary_contact='" . $datas['primary_contact'] . "') and (first_name='" . $datas['first_name'] . "' and last_name='" . $datas['last_name'] . "')";

        $datas = $this->dbcon->Query($sql);
        if (isset($datas['id']))
            return $datas;
        else
            return "0";
    }

    function studentCancelForm($datas) {
        unset($datas['id']);
        $datas["created_date"] = date('Y-m-d H:i:s');
        $datas["modified_date"] = date('Y-m-d H:i:s');
        $datas["attend_tournament"] = $datas['attend_tournament'];
        $userid = $this->dbcon->Insert('itf_tournament_student', $datas);
        $objuser = new User(); //User object
        $maildatavalue = array();

        $maildatavalue['mailsubject'] = 'Cancellation Form';
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $maildatavalue['mailbody'] = 'We have recieved your cancellation request and it will be processed within 48 hrs.';
        $objmail->SetBody($maildatavalue['mailbody'], array('name' => $datas['first_name'], "emailid" => $datas['email']));
        $objmail->SetTo($datas['primary_email']);
        $objmail->SetCC($datas['teacher_email']);
        $objmail->MailSend();
    }

    function studentCancelAdminMail($datas) {
	
        $maildatavalue = array();
        $maildatavalue['mailsubject'] = 'Cancellation Request Received';

        $userObj = new User;
        $maildatavalue = $userObj->GetEmail(23);
		//echo '<pre>'; print_r($datas); 
	//echo '<pre>'; print_r($maildatavalue); die;
        $objmail1 = new ITFMailer();
        $objmail1->SetSubject($maildatavalue['mailsubject']);
        $objmail1->SetBody($maildatavalue['mailbody'], array( 'PHONE' => $datas['primary_contact'],
            'PARENT_EMAIL' => $datas['primary_email'], 'STUDENT_NAME' => $datas['first_name'] . ' ' . $datas['last_name'],
            "LOCATION" => $datas['loc_id'], 'REASON_CANCEL' => $datas['reason_cancel']));


        $siteobj = new Site();
        $site_info = $siteobj->CheckSite(1);
        
        if($site_info) {
           require_once('includes/classes/class.phpmailer.php');
           $mail = new PHPMailer();

try {
    $mail->isMail();
    $mail->setFrom('info@bayareadebateclub.com', $site_info['sitename']);
//echo $datas['email_to']; die;
    $mail->addAddress($datas['email_to'], '');
    //$mail->addCC('lmani@shahdeepinternational.com', '');
    $mail->addCC('ashishkhurana1@gmail.com', '');
    $mail->Subject = $maildatavalue['mailsubject'];
    $mail->msgHTML($objmail1->GetBody());

    // optional - msgHTML will create an alternate automatically
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    //$mail->addAttachment('../examples/images/phpmailer.png'); // attachment
    //$mail->addAttachment('../examples/images/phpmailer_mini.png'); // attachment
   // $mail->action_function = 'callbackAction';
    $mail->send();

    //echo "Message Sent OK"; 
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}


}
        //$objmail1->SetTo($datas['email_to']);
        //$objmail1->SetCC('ankitengr@yahoo.com');
        //$objmail1->MailSend();
      }

    function studentCancelMail($datas) {
        $maildatavalue = array();
        $maildatavalue['mailsubject'] = 'Cancellation Request Received';
        $userObj = new User;
        $maildatavalue = $userObj->GetEmail(22);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array(
            'STUDENT_NAME' => $datas['first_name'] . ' ' . $datas['last_name'], "LOCATION" => $datas['loc_id'], "PARENT_EMAIL" => $datas['primary_email']));
        $objmail->SetTo($datas['email_to']);
        $objmail->MailSend();
    }

    function getAdminMail() {
        $sql = "select email from itf_users where user_type='1'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']))
            return $datas['email'];
        else
            return "0";
    }

    function getTeacherDetails($teacherId) {
        $sql = "select id,email from itf_teachers where id in ($teacherId) and status='1'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']))
            return $datas['email'];
        else
            return "0";
    }

    function getStudentInfo($id) {
        $sql = "select c.id as class_id,c.code as class_code,co.id as course_id,co.code as course_code,co.name as course_name,l.code as loc_code,l.name as loc_name,s.id as student_id,s.first_name,s.last_name,s.primary_name,s.primary_contact,s.primary_email,s.primary_rel"
                . " from itf_student s,itf_course co,itf_location l,itf_class c "
                . "where (s.id='" . $id . "') and (co.id =s.course_id and l.id=s.loc_id)"
                . "and s.class_id=c.id";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function addStudentByTeacherAdminMail($studentId) {

        $studentDetail = $this->getStudentInfo($studentId);
        if ($studentDetail['student_id'] != '') {
            $utils = new Utils;
            $userObj = new User;
            $encrypted = $utils->encryptIds($validEmail['id']);
            $maildatavalue = $userObj->GetEmail(21);
            global $stieinfo;

            $objmail = new ITFMailer();
            $objmail->SetSubject("Unregistered Student for $studentDetail[class_code] : $studentDetail[loc_name]");
            $objmail->SetBody($maildatavalue['mailbody'], array('PARENT_NAME' => $studentDetail['primary_name'],
                'STUDENT_NAME' => $studentDetail['first_name'] . ' ' . $studentDetail['last_name'],
                'COURSE' => $studentDetail['course_name'],
                'LOCATION' => $studentDetail['loc_code'] . ' -- ' . $studentDetail['loc_name'],
                'PARENT_EMAIL' => $studentDetail['primary_email'],
                'PARENT_PHONE' => $studentDetail['primary_contact']));
            $objmail->SetTo($stieinfo['emailid']);
            $objmail->MailSend();

            return true;
        } else
            return false;
    }

    function registrationConfirmationForStudent($data) {

        $studentDetail = $this->getStudentInfo($data['student_id']);
       if ($studentDetail['student_id'] != '') {
            $userObj = new User;
            $maildatavalue = $userObj->GetEmail(24);
            global $stieinfo;
            $objmail = new ITFMailer();
            $objmail->SetSubject("Registration Confirmation");
            $objmail->SetBody($maildatavalue['mailbody'], array('PARENT_NAME' => $studentDetail['primary_name'],
                'STUDENT_NAME' => $studentDetail['first_name'] . ' ' . $studentDetail['last_name'],
                'COURSE' => $studentDetail['course_name'],
                'LOCATION' => $studentDetail['loc_code'] . ' -- ' . $studentDetail['loc_name'],
                'PARENT_EMAIL' => $studentDetail['primary_email'],
                'PARENT_PHONE' => $studentDetail['primary_contact']));
            $objmail->SetTo($studentDetail['primary_email']);
            $objmail->MailSend();
            return true;
        } else {
            return false;
        }
    }

    function registrationConfirmationForAdmin($data) {
//$data['student_id']=84;
        $studentDetail = $this->getStudentInfo($data['student_id']);
        //echo '<pre>';print_r($studentDetail);exit;
        if ($studentDetail['student_id'] != '') {
            $userObj = new User;
            $maildatavalue = $userObj->GetEmail(25);
            global $stieinfo;

            $objmail = new ITFMailer();
 $objmail->setFrom('info@bayareadebateclub.com', 'BayArea');
            //$objmail->SetSubject("New Registration for");
            $objmail->SetSubject("New Registration for $studentDetail[class_code] : $studentDetail[loc_name]");
            $objmail->SetBody($maildatavalue['mailbody'], array('PARENT_NAME' => $studentDetail['primary_name'],
                'STUDENT_NAME' => $studentDetail['first_name'] . ' ' . $studentDetail['last_name'],
                'COURSE' => $studentDetail['course_name'],
                'LOCATION' => $studentDetail['loc_code'] . ' -- ' . $studentDetail['loc_name'],
                'PARENT_EMAIL' => $studentDetail['primary_email'],
                'AMOUNT' => $data['amount'],
                'PARENT_PHONE' => $studentDetail['primary_contact']));
           
            $objmail->SetTo($stieinfo['emailid']);
            $objmail->SetBCC('ankitengr@yahoo.com');
            $objmail->MailSend();
            return true;
        } else
            return false;
    }

    function registrationConfirmationForTeacher($data) {

        $studentDetail = $this->isTeacherRegistrationMail($data['student_id']);
        if ($studentDetail['student_name'] != '') {
            $userObj = new User;
            $maildatavalue = $userObj->GetEmail(26);
            global $stieinfo;
            $objmail = new ITFMailer();
            $objmail->SetSubject("New Registration for $studentDetail[class_code] : $studentDetail[loc_name]");
            $objmail->SetBody($maildatavalue['mailbody'], array(
                'PARENT_NAME' => $studentDetail['parent_name'],
                'STUDENT_NAME' => $studentDetail['student_name'],
                'COURSE' => $studentDetail['course_name'],
                'LOCATION' => $studentDetail['loc_code'] . ' -- ' . $studentDetail['loc_name'],
                'PARENT_EMAIL' => $studentDetail['primary_email'],
                'STUDENT_GRADE' => $studentDetail['grade_desc'],
                'PARENT_CONTACT' => $studentDetail['primary_contact']));
            $objmail->SetTo($studentDetail['email']);
            $objmail->MailSend();
            return true;
        } else
            return false;
    }

    function isTeacherRegistrationMail($studentId) {

        $sql = " SELECT c.id AS class_id, co.name AS course_name, CONCAT( st.first_name, ' ', st.last_name ) AS student_name, st.primary_name AS parent_name, st.primary_email, st.primary_contact, l.code AS loc_code, c.code AS class_code, c.start_date, c.end_date, l.name AS loc_name, l.id AS location_id, l.address, l.city, c.day_of_week, g.grade_desc, t.email, t.id AS teacher_id, t.first_name AS teacher_name
FROM itf_student st, itf_class c, itf_location l, itf_course co, itf_grade g, itf_teachers t
 WHERE (st.class_id = c.id AND co.id = st.course_id AND st.loc_id = l.id AND g.id = st.grade
AND FIND_IN_SET( t.id, c.teacher_assigned ) >0 ) and  (date(c.start_date) <='" . date('Y-m-d') . "' and date(c.end_date) > '" . date('Y-m-d') . "') and (st.id='$studentId')
            order by l.city,c.end_date";
        $countTot = $this->dbcon->Query($sql);
        return $countTot;
    }

}

?>
