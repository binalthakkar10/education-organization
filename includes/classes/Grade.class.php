<?php

class Grade {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function admin_addGrade($datas) {
        unset($datas['id']);
        $this->dbcon->Insert('itf_grade', $datas);
    }

    function admin_updateGrade($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        $this->dbcon->Modify('itf_grade', $datas, $condition);
    }

    function Grade_deleteAdmin($Id) {
        $sql = "delete from itf_grade where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function showAllGrade($parentid = "0") {
        $sql = "select S.*,(select count(*) from itf_grade ) as totalcity from itf_grade S";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllactive() {
        $sql = "select *  from itf_grade where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllActiveGrade() {
        $sql = "select id,grade,concat(grade,' ',grade_desc) as grade_name,grade_desc  from itf_grade where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showClassGrade($start, $end) {
        $sql = "select id,grade,grade_desc  from itf_grade where (status='1' and id >='$start' and id <= '$end')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllGradeSearch($txtsearch) {
        $sql = "select * from itf_grade where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function GradeByClass($classId) {
        $sql = "select ci.id as class_id,c.start_eligibility as start_grade,c.end_eligibility as end_grade from itf_class ci where  ci.id=$classId";
        return $this->dbcon->Query($sql);
    }

    function CheckGrade($UsId) {
        $sql = "select U.* from itf_grade U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    //Function for change status	
    function PublishBlock($ids) {

        $infos = $this->CheckGrade($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_grade', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    //front end============================================================
    function getAllGradeFront($parentid = "0") {
        $sql = "select *  from itf_grade where status='1' and parentid='" . $parentid . "' order by name ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getAllGradeCity() {
        $allgrade = $this->showAllGrade(0);
        foreach ($allgrade as &$citygrade)
            $citygrade["CITY"] = $this->showAllGrade($citygrade["id"]);
        return $allgrade;
    }

}

?>