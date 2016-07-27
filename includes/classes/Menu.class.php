<?php

class Menu {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function UpdateMenu($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        $this->dbcon->Modify('itf_menu', $datas, $condition);
    }

    function DeletMenu($Id) {
        $sql = "delete from itf_menu where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function ShowAllMenu($parentid = "0") {
        $sql = "Select id,name,status,link from itf_menu ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowParentMenuName($id) {
        $sql = "select id,name from itf_menu where id='$id'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllActiveMenu() {
        $sql = "select *  from itf_menu where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllGradeSearch($txtsearch) {
        $sql = "select * from itf_menu where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckMenu($UsId) {
        $sql = "select id,status,name,parent_id,link from itf_menu where id='" . $UsId . "' ";
        return $this->dbcon->Query($sql);
    }

    function ShowAllSubMenus($UsId) {
        $sql = "select m.id,m.name,sm.name as submenu, sm.id as submenu_id from itf_menu m, itf_menu sm where (m.status='1' and sm.parent_id=m.id) ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowParenMenuName($Id) {
        $sql = "select m.id,m.name from itf_menu m where (m.status='1' and m.id!=0) and m.id='$Id' ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllPages() {
        $sql = "select id,name from itf_pagecms where status='1' ";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllActiveParentMenu() {
        $sql = "select id,name,link,parent_id,is_show from itf_menu where status='1' and parent_id='0'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllActiveChildMenu($parentId) {
        $sql = "select id,name,link,is_show from itf_menu where (status='1' and parent_id ='$parentId')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getPageName($Id) {
        $sql = "select id,name from itf_pagecms where id='" . $Id . "'";
        return $this->dbcon->Query($sql);
    }

    function PublishBlock($ids) {

        $infos = $this->CheckMenu($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_menu', $datas, $condition);
        return ($infos['status'] == '1') ? 0 : 1;
    }

}

?>
