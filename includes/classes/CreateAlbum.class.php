<?php

class CreateAlbum {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function createFolder1($name) {
        $newfolder = BASEPATHS . "/itf_public/image_gallery/" . $name;
        //$newfolder= chmod($newfolder, 0777);          
        if (is_dir($newfolder)) {
            return '0';
        } else {
            mkdir($newfolder, 0777);
            return '1';
            // it is good th check and see if it is_dir before we remove it
        }
    }

    function admin_createAlbum($datas) {
        if ($datas) {
            unset($datas['id']);
            $galleryid = $this->dbcon->Insert('itf_album', $datas);
            $data = $this->createFolder1($galleryid);
            return 1;
        } else {
            return 0;
        }
    }

    function admin_updateAlbum($datas) {
        echo $condition = array('id' => $datas['id']);
        unset($datas['id']);
        $this->dbcon->Modify('itf_album', $datas, $condition);
    }

    function deleteFolder($folderName) {
        $folderid = explode(',', $folderName);
        foreach ($folderid as $fold) {
            $newfolder = BASEPATHS . "/itf_public/image_gallery/" . $fold;
            $dir = $newfolder;
            $image = scandir($dir);
            foreach ($image as $file) {
                if ('.' !== $file || '..' !== $file) {
                    @unlink("$dir/$file");
                }
                //continue;
                /* if (is_dir("$dir/$file"))
                  deleteFolder("$dir/$file");
                  else
                  unlink("$dir/$file"); */
            }
            rmdir($dir);
        }

        return true;
    }

    function Album_deleteAdmin($Id) {

        //$this->deleteFolder($Id);
        $sql = "delete from itf_image_gallery where album_id in(" . $Id . ")";
        $this->dbcon->Query($sql);

        $sql = "delete from itf_album where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function showAllCreateAlbum($album_id = "0") {
        echo $sql = "select S.*,(select count(*) from itf_album where album_id=S.id) as totalcity from itf_album S where S.album_id='" . $album_id . "' ";
        //$sql="SELECT * FROM `itf_image_gallery` WHERE status='1'";                
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllAlbum() {
        //$sql="select S.*,(select count(*) from itf_album where album_id=S.id) as totalcity from itf_album S where S.album_id='".$album_id."' ";
        $sql = "SELECT * FROM `itf_album`";
        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllActiveAlbum() {
        $sql = "SELECT * FROM `itf_album` where status='1' order by id desc";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCreateAlbumSearch($txtsearch) {
        $sql = "select * from itf_album where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckAlbum($UsId) {
        $sql = "select U.* from itf_album U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    //Function for change status	
    function PublishBlock($ids) {

        $infos = $this->CheckAlbum($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_album', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    //front end============================================================
    function getAllCreateAlbumFront($album_id = "0") {
        $sql = "select *  from itf_album where status='1' and album_id='" . $album_id . "' order by id desc";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getAllAlbumImages($album_id = "0") {
        $sql = "select *  from itf_image_gallery where status='1' and album_id='" . $album_id . "' order by id desc";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getAllCreateAlbumCity() {
        $allalbum = $this->showAllCreateAlbum(0);
        foreach ($allalbum as &$cityalbum)
            $cityalbum["CITY"] = $this->showAllCreateAlbum($cityalbum["id"]);
        return $allalbum;
    }

}

?>
