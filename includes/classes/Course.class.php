<?php

class Course {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function admin_addCourse($datas) {
        unset($datas['id']);
        $created_date = date('Y-m-d');
        $datas['created_date'] = $created_date;
        $this->dbcon->Insert('itf_course', $datas);
    }

    function admin_updateCourse($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        $modified_date = date('Y-m-d');
        $datas['modified_date'] = $modified_date;
        $this->dbcon->Modify('itf_course', $datas, $condition);
    }

    function Course_deleteAdmin($Id) {
        echo $sql = "delete from itf_course where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function showAllCourse() {
        $sql = "select * from itf_course   order by id desc";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllSummerCamps() {
        $sql = "select * from itf_course ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllCourselist() {
        $sql = "select id, name from itf_course  where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCourseSearch($txtsearch) {
        $sql = "select * from itf_course where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckCourse($UsId) {
        $sql = "select U.* from itf_course U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    //Function for change status	
    function PublishBlock($ids) {

        $infos = $this->CheckCourse($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_course', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    //front end============================================================
    function getAllStateFront($parentid = "0") {
        $sql = "select *  from itf_course where status='1' and parentid='" . $parentid . "' order by name ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getAllStateCity() {
        $allstate = $this->showAllState(0);
        foreach ($allstate as &$citystate)
            $citystate["CITY"] = $this->showAllState($citystate["id"]);
        return $allstate;
    }

}

?>
