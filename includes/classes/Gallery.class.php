<?php

class Gallery {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function admin_addGallery($datas) {
        if (isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name'])) {
            $fimgname = "ITFBANNER" . time();
            $objimage = new ITFImageResize();
            $objimage->load($_FILES['bannerimage']['tmp_name']);
            $objimage->save(PUBLICFILE . "image_gallery/" . $datas['album_id'] . "/" . $fimgname);
            $productimage_name = $objimage->createnames;
            $datas['image_name'] = $productimage_name;
        }

        unset($datas['id']);
        $this->dbcon->Insert('itf_image_gallery', $datas);
    }

    function admin_updateImageGallery($datas) {

        if (isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name'])) {
            $fimgname = "ITFBANNER" . time();
            $objimage = new ITFImageResize();
            $objimage->load($_FILES['bannerimage']['tmp_name']);
            $objimage->save(PUBLICFILE . "image_gallery/" . $datas['album_id'] . "/" . $fimgname);
            $productimage_name = $objimage->createnames;
            $datas['image_name'] = $productimage_name;
            $advertiseinfo = $this->CheckImageGallery($datas['id']);
            @unlink(PUBLICFILE . "image_gallery/" . $advertiseinfo["image_name"]);
//exit;
        }

        $condition = array('id' => $datas['id']);

        unset($datas['id']);
        $this->dbcon->Modify('itf_image_gallery', $datas, $condition);
    }

    function Gallery_deleteAdmin($Id) {
        $alladvertise = explode(',', $Id);
        $sql = "select * from itf_image_gallery where id in(" . $Id . ")";
        $datas = $this->dbcon->FetchAllResults($sql);
        foreach ($datas as $data) {
            @unlink(PUBLICFILE . "image_gallery/" . $data[album_id] . "/" . $data['image_name']);
        }
        $sql = "delete from itf_image_gallery where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function showAllGallery($ids) {
        if ($ids) {
            $sql = "select *  from itf_image_gallery where status='1' AND album_id = '" . $ids . "'";
        } else {
            $sql = "select *  from itf_image_gallery where status='1'";
        }
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllAlbum() {
        $sql = "select id,album_name from itf_album where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckImageGallery($UsId) {
        $sql = "select U.* from itf_image_gallery U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function PublishBlock($ids) {
        $infos = $this->CheckImageGallery($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_banner', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    function GetBannerbyId($Id) {
        $sql = "select *  from itf_banner where id in(" . $Id . ")";
        return $this->dbcon->FetchAllResults($sql);
    }

    //front end============================================================
    function getAllBannerFront() {
        $sql = "select *  from itf_banner where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

}

?>
