<?php

class Debate {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function admin_addDebate($datas) {
        unset($datas['id']);
        if ($datas['attend_date'] != '') {
            $attend_date = date('Y-m-d', strtotime($datas['attend_date']));
            $datas['attend_date'] = $attend_date;
        }
        if ($datas['dob'] != '') {
            $datas['dob'] = date('Y-m-d', strtotime($datas['dob']));
        }
        $datas['created_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $this->dbcon->Insert('itf_debt', $datas);
    }

    function admin_updateDebate($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        if ($datas['attend_date'] != '') {
            $attend_date = date('Y-m-d', strtotime($datas['attend_date']));
            $datas['attend_date'] = $attend_date;
        }
        if ($datas['dob'] != '') {
            $datas['dob'] = date('Y-m-d', strtotime($datas['dob']));
        }
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $this->dbcon->Modify('itf_debt', $datas, $condition);
    }

    function updateRanking($exceldata) {
        $condition = array('id' => $exceldata['id']);
        unset($exceldata['id']);
        $this->dbcon->Modify('itf_debt', $exceldata, $condition);
    }

    function Debate_deleteAdmin($Id) {
        $sql = "delete from itf_debt where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function showAllDebate($parentid = "0") {
        $sql = "select S.*,(select count(*) from itf_debt ) as totalcity from itf_debt S  order by id asc ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAlldebatelist() {
        $sql = "select * from itf_debt  where status='1' ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCourseSearch($txtsearch) {
        $sql = "select * from itf_debt where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckDebate($UsId) {
        // print_r($UsId);
        $sql = "select * from itf_debt U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function CheckDebatRanking($studentName) {
        // print_r($UsId);
        $sql = "select id,first_name,last_name,debt_score,attend_date,dob from itf_debt U where (U.first_name='" . $studentName['first_name'] . "'"
                . "  and U.last_name='" . $studentName['last_name'] . "' )";
       
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckDebate1() {
        print_r($UsId);
        $sql = "select U.* from itf_debt  ";
        return $this->dbcon->Query($sql);
    }

    //Function for change status	
    function PublishBlock($ids) {
        $infos = $this->CheckDebate($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');
        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_debt', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    //front end============================================================
    function getAllStateFront($parentid = "0") {
        $sql = "select *  from itf_course where status='1'  order by name ";
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
