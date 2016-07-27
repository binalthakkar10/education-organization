<?php

class PageCms {

    public $dbcon;

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    //Add sports	
    function admin_add($datas) {

        $datas["name"] = empty($datas["name"]) ? Html::seoUrl($datas["pagetitle"]) : Html::seoUrl($datas["name"]);
        $this->dbcon->Insert('itf_pagecms', $datas);
    }

    //Delete sports
    function admin_delete($UId) {
        $sql = "delete from itf_pagecms where id in(" . $UId . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function admin_update($datas) {
        $condition = array('id' => $datas['id']);
        $datas["name"] = Html::seoUrl($datas["name"]);
        unset($datas['id']);
        $this->dbcon->Modify('itf_pagecms', $datas, $condition);
    }

    function ShowAllPageCms() {
        $sql = "select * from itf_pagecms";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function GetPageData($id) {
        $sql = "select * from itf_pagecms  where id='" . $id . "'";
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    function CheckPageCms($UsId) {
        $sql = "select * from itf_pagecms where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function PublishBlock($ids) {
        $infos = $this->CheckPageCms($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');
        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_pagecms', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    function GetArticales($pagename) {
        $sql = "select * from itf_pagecms where name='" . $pagename . "'";
        return $this->dbcon->Query($sql);
    }

    function GetPageDetails($Id) {
        $sql = "select * from itf_pagecms where id='" . $Id . "'";
        return $this->dbcon->Query($sql);
    }

    function GetMenuCms() {
        $sql = "select id,name,pagetitle from itf_pagecms order by id";
        $res = $this->dbcon->FetchAllResults($sql);
        $menudata = array();
        foreach ($res as $dd)
            $menudata[$dd["id"]] = array("name" => $dd["name"], "title" => $dd["pagetitle"]);

        return $menudata;
    }

    function contactUs($datas) {
        $objuser = new User();
        $emaildata = $objuser->GetEmail(4);
        $bodydata = ComposeBody($emaildata["mailbody"], $datas);
        MailSend("info@ikonnectpages.ca", $emaildata["mailsubject"], $bodydata);
        $emaildata = $objuser->GetEmail(3);
        $bodydata = ComposeBody($emaildata["mailbody"], $datas);
        MailSend($datas["emailid"], $emaildata["mailsubject"], $bodydata);
    }

}

?>
