<?php

class Attendance {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function showAllStudent() {
        $sql = "select c.first_name,c.last_name,c.grade,(select s.status_name from itf_student_status  s where s.id=c.status) as status from itf_student as c";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllStuSearch($txtsearch) {
        $search = $this->dbcon->EscapeString($txtsearch);
        //echo $sql="select ic. from itf_student where ass_class like ( '%".$serch."%')";
        $sql = "select c.first_name,c.last_name,c.grade,"
                . "(select s.status_name from itf_student_status  s where s.id=c.status) as status"
                . " from itf_student as c "
                . "where c.ass_class like ( '%" . $serch . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCourseStudents($txtsearch) {
        $search = $this->dbcon->EscapeString($txtsearch);
        $searchDate = date('Y-m-d H:i:s', strtotime($txtsearch['txtsearch']));

        // echo '<pre>';
        // print_r($txtsearch);
        $wheres = array();
        if ($txtsearch['loc_id'] != '') {
            $wheres[] = " cl.loc_id ='" . $this->dbcon->EscapeString($txtsearch['loc_id']) . "'";
        }
        if ($txtsearch['course'] != '') {
            $wheres[] = " co.id ='" . $this->dbcon->EscapeString($txtsearch['course']) . "'";
        }
        if ($txtsearch['classCode'] != '') {
            $wheres[] = " isa.class_id ='" . $this->dbcon->EscapeString($txtsearch['classCode']) . "'";
        }
        switch ($txtsearch['col']) {
            case 'first_name_asc':
                $order = '  order by st.first_name ASC';
                break;
            case 'first_name_desc':
                $order = '  order by st.first_name DESC';
                break;
            case 'last_name_asc':
                $order = '  order by st.last_name ASC';
                break;
            case 'last_name_desc':
                $order = '  order by st.last_name DESC';
                break;
            case 'class_code_asc':
                $order = '  order by cl.code ASC';
                break;
            case 'class_code_desc':
                $order = '  order by cl.code DESC';
                break;
            case 'course_asc':
                $order = '  order by co.name ASC';
                break;
            case 'course_desc':
                $order = '  order by co.name DESC';
                break;
            case 'loc_name_asc':
                $order = '  order by lo.name ASC';
                break;
            case 'loc_name_desc':
                $order = '  order by lo.name DESC';
                break;
            case 'class_date_asc':
                $order = '  order by isa.class_date ASC';
                break;
            case 'class_date_desc':
                $order = '  order by isa.class_date DESC';
                break;
            case 'attendance_asc':
                $order = '  order by isa.attendance ASC';
                break;
            case 'attendance_desc':
                $order = '  order by isa.attendance DESC';
                break;
            case 'email_asc':
                $order = '  order by st.email ASC';
                break;
            case 'email_desc':
                $order = '  order by st.email DESC';
                break;
            default:
                $order = '  order by isa.id DESC';
                break;
        }
        if (count($wheres) > 0) {
            $where = ' AND (' . implode(($phrase == 'all' ? ') AND (' : ') AND ('), $wheres) . ')';
        }
        if (count($txtsearch) > 0) {
            $sqlAttendance = "SELECT isa.student_id, isa.course_id, isa.class_id, isa.attendance, date( isa.class_date ) AS attendance_date, co.name, cl.room, st.first_name, last_name, grade, st.status AS student_status,cl.code,lo.name as loc_name
FROM itf_student_attendance isa, itf_class cl, itf_course co, itf_location lo, itf_student st
WHERE (isa.status = '1' AND cl.id = isa.class_id) AND (cl.course_id = co.id AND lo.id = cl.loc_id AND st.id = isa.student_id
)" . $where . $order;
            ;
        } else {
            $sqlAttendance = "SELECT isa.student_id, isa.course_id, isa.class_id, isa.attendance, date( isa.class_date ) AS attendance_date, co.name, cl.room, st.first_name, last_name, grade, st.status AS student_status,cl.code,lo.name as loc_name
FROM itf_student_attendance isa, itf_class cl, itf_course co, itf_location lo, itf_student st
WHERE (isa.status = '1' AND cl.id = isa.class_id) AND (cl.course_id = co.id AND lo.id = cl.loc_id AND st.id = isa.student_id
)";
        }

        //echo $sqlAttendance; exit;
        $result = $this->dbcon->FetchAllResults($sqlAttendance);
        
        //echo '<pre>'; print_r($result); exit;
        
        return $result;
        
    }

}

