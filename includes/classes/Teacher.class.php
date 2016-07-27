<?php

class Teacher {

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

    function Get_User($id) {
        $sql = "select * from itf_teachers where id='" . $id . "' and status='1'";
        return $this->dbcon->Query($sql);
    }

    function SelectMail() {
        $sql = "select email from  itf_teachers  where usertype='1'";
        return $this->dbcon->Query($sql);
    }

    function loginAdminUser($uname, $pass) {
        $sql = "select * from itf_teachers where username='" . $this->dbcon->EscapeString($uname) . "' and 	password ='" . $this->dbcon->EscapeString(md5($pass)) . "' and usertype='1'";
        if ($DD = $this->dbcon->Query($sql)) {
            $_SESSION['LoginInfo'] = array('USNAME' => $DD['name'], 'USINFO' => $DD['email'], 'USERID' => $DD['id'], 'first_name' => $DD['first_name'], 'last_name' => $DD['last_name']);
            return '1';
        } else
            return '0';
    }

    function userLogin($uname, $pass) {
        $sql = "select U.*,P.expiry_date from itf_teachers U
                    INNER JOIN itf_user_profile P on U.profile_id = P.id
                    where U.username='" . $this->dbcon->EscapeString($uname) . "' and U.password='" . md5($this->dbcon->EscapeString($pass)) . "' and U.status ='1' ";
        if ($DD = $this->dbcon->Query($sql)) {
            $ex = date('Y-m-d', strtotime($DD['expiry_date']));
            $expired = date('Y-m-d', strtotime('-5 days', strtotime($ex)));
            if (isset($DD) and $DD['expiry_date'] >= date("Y-m-d")) {
                if ($DD['expiry_date'] >= date("Y-m-d") and $expired <= date("Y-m-d")) {

                    $maildatavalue = $this->GetEmail(18);
                    $objmail = new ITFMailer();
                    $objmail->SetSubject($maildatavalue['mailsubject']);
                    $objmail->SetBody($maildatavalue['mailbody'], array('expiry' => $DD['expiry_date']));
                    $objmail->SetTo($DD['email']);
                    $objmail->MailSend();
                }
                $_SESSION['FRONTUSER'] = $DD;
                if ($DD["usertype"] == "4") {
                    $_SESSION['FRONTUSER']["usertype"] = "2";
                    $_SESSION['FRONTUSER']["current"] = "2";
                }
                return $DD;
            } else {
                return '2';
            }
        } else {
            return '0';
        }
    }

    function logout() {
        session_unset();
    }

    function addTeacherDetails($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        unset($datas['id']);
        $datas['creadet_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $password = $datas['password'];
        $datas['password'] = md5($datas['password']);
        $this->dbcon->Insert('itf_teachers', $datas);
        $objUser = new User;
        $url = ' <a  href="' . SITEURL . '/index.php?itfpage=teacherlogin">' . SITEURL . '/index.php?itfpage=teacherlogin</a>';
        $maildatavalue = $objUser->GetEmail(28);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('USERNAME' => $datas['email'],
            'PASSWORD' => $password,
            'SITEURL' => $url));
        $objmail->SetTo($datas['email']);
        $objmail->MailSend();
    }

    function admin_update($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        $this->dbcon->Modify('itf_teachers', $datas, $condition);
    }

    function updateTeacherDetails($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        $condition = array('id' => $datas['id']);
        $profile_condition = array('id' => $profile_info['id']);
        unset($datas['id']);
        if (empty($datas['password'])) {
            unset($datas['password']);
        } else {
            $datas['password'] = md5($datas['password']);
            // $datas['creadet_date'] = date('Y-m-d H:i:s');
        }
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $this->dbcon->Modify('itf_teachers', $datas, $condition);
    }

    function ShowAllUser() {
        $sql = "select *  from itf_teachers";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCustomer() {
        $sql = "select *  from itf_teachers where usertype = 2";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllTeacher() {
    	if(!empty($_SESSION['LoginInfo']['org_id']) && isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		 $sql = "select *  from itf_teachers where org_id='".$org_id."' order by id desc";
    	}else{
    		 $sql = "select *  from itf_teachers  order by id desc";
    	}
        return $this->dbcon->FetchAllResults($sql);
    }

    function checkTeacherId($UsId) {
        $sql = "select * from itf_teachers where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }
 function getActiveTeacherInfo() {
 	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
 		$org_id = $_SESSION['LoginInfo']['org_id'];
 		$datas['org_id'] = $org_id;
 		$sql = "select id,first_name,last_name from itf_teachers t where t.status='1' and t.org_id=$org_id order by first_name asc ";
 	}else{
 		$sql = "select id,first_name,last_name from itf_teachers t where t.status='1' order by first_name asc ";
 	}
        return $this->dbcon->FetchAllResults($sql);
    }
    function getTeacherInfo($Id) {
    		$sql = "select U.id as user_id,U.* from itf_teachers U where U.id='" . $Id . "' ";
        return $this->dbcon->Query($sql);
    }

    function CheckMembership($UsId) {
        $sql = "select U.* from itf_teachers U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    //Function for change status	

    function PublishBlock($ids) {
        $infos = $this->checkTeacherId($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_teachers', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    function PublishMember($ids) {
        $infos = $this->CheckMembership($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_teachers', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    function ChangePassword() {
        $userid = $_SESSION['LoginInfo']['USERID'];
        $userinfo = $this->checkTeacherId($userid);
        $data = $_REQUEST;

        if ($userinfo['password'] == md5($data["oldpassword"])) {
            $datas = array('password' => md5($data["newpassword"]));
            $condition = array('id' => $userid);
            $this->dbcon->Modify('itf_teachers', $datas, $condition);
            return true;
        } else {
            return false;
        }
    }

    function ChangePasswordFront($newpassword) {
        $userid = $_SESSION['FRONTUSER']['id'];
        $userinfo = $this->checkTeacherId($userid);

        $datas = array('password' => md5($newpassword));
        $condition = array('id' => $userid);
        $this->dbcon->Modify('itf_teachers', $datas, $condition);
        return true;
    }

    function GetEmail($id) {
        $sql = "select * from itf_mails where id='" . $id . "'";
        return $this->dbcon->Query($sql);
    }

    function forgotPasswordAdmin($tomail) {

        $userdetail = $this->CheckEmail($tomail);

        if (isset($userdetail['id'])) {
            $newpass = "plucka" . substr(time(), -4);
            $datas = array('password' => md5($newpass));
            $condition = array('id' => $userdetail['id']);
            unset($datas['id']);
            $this->dbcon->Modify('itf_teachers', $datas, $condition);
            $maildatavalue = $this->GetEmail(9);
            $objmail = new ITFMailer();
            $objmail->SetSubject($maildatavalue['mailsubject']);
            $objmail->SetBody($maildatavalue['mailbody'], array('username' => $userdetail['username'], "password" => $newpass));
            $objmail->SetTo($userdetail['emailid']);
            $objmail->MailSend();
            return true;
        } else
            return false;
    }

    function customerRegister($datas) {


        $sql = "SELECT * FROM itf_teachers_temp where id='" . $datas . "' ";
        //mail('shahdeep.ishant@gmail.com','ishant.saxena@gmail.com', "test",  $sql);
        $data = $this->dbcon->Query($sql);
        $profileid = $this->dbcon->Insert('itf_user_profile', $data);
        $data["profile_id"] = $profileid;
        $userid = $this->dbcon->Insert('itf_teachers', $data);

        $maildatavalue = $this->GetEmail(2);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('sitename' => $siteinfo['sitename'], 'name' => $data['name'], "username" => $data['username'], "emailid" => $data['username'], "password" => $data['password2']));
        $objmail->SetTo($data['email']);
        $objmail->MailSend();
        return $userid;
    }

    function customerRegisterTemp($datas) {


        $datas['password2'] = $datas['password'];
        //echo "<pre>";print_r($datas);die;
        $userinfo = $this->CheckMembership($datas['memberid']);
        //echo "<pre>";print_r($userinfo);die;
        $type = $userinfo['type'];
        $day = $userinfo['duration_day'];
        $durationtime = $userinfo['duration_type'];
        $end = date('Y-m-d', strtotime('+' . $day . $durationtime));

        //echo $total=$day+$durationtime;die;
        $admin_mail = $this->checkTeacherId(1);
        $objsite = new Site();
        $siteinfo = $objsite->CheckSite("1");

        unset($datas['id']);
        if ($datas['productGroup']) {
            $datas['product_group_id'] = implode(",", $datas['productGroup']);
        }
        if ($datas['serviceArea']) {
            $datas['city_id'] = implode(",", $datas['serviceArea']);
        }
        if ($datas['serviceGroup']) {
            $datas['service_category'] = implode(",", $datas['serviceGroup']);
        }


        $datas["password"] = md5($datas["password"]);

        if ($type == "Customer") {
            $datas["usertype"] = "2";
            $datas["registration_id"] = 'CPL' . time();
        } elseif ($type == "Supplier") {
            $datas["usertype"] = "3";
            $datas["registration_id"] = 'SPL' . time();
        } else {
            $datas["usertype"] = "4";
            $datas["registration_id"] = 'BPL' . time();
        }
        //$datas["usertype"] = "2";
        $datas["expiry_date"] = $end;

        $profileid = $this->dbcon->Insert('itf_teachers_temp', $datas);
        //$datas["profile_id"] = $profileid;
        //$userid = $this->dbcon->Insert('itf_teachers_temp',$datas);
//		$maildatavalue = $this->GetEmail(2);
//		$objmail = new ITFMailer();
//		$objmail->SetSubject($maildatavalue['mailsubject']);
//		$objmail->SetBody($maildatavalue['mailbody'],array('sitename'=>$siteinfo['sitename'],'name'=>$datas['name'],"emailid"=>$datas['email'],"password"=>$_POST['password']));
//		$objmail->SetTo($datas['email']);
//		$objmail->MailSend();
        return $profileid;
    }

    function supplierRegister($datas) {


        unset($datas['id']);
        $datas["registration_id"] = 'SPL' . time();
        if ($datas['productGroup']) {
            $datas['product_group_id'] = implode(",", $datas['productGroup']);
        }
        if ($datas['serviceArea']) {
            $datas['city_id'] = implode(",", $datas['serviceArea']);
        }
        if ($datas['serviceGroup']) {
            $datas['service_category'] = implode(",", $datas['serviceGroup']);
        }

        $datas["password"] = md5($datas["password"]);
        $datas["usertype"] = "3";
        $profileid = $this->dbcon->Insert('itf_user_profile', $datas);
        $datas["profile_id"] = $profileid;
        $userid = $this->dbcon->Insert('itf_teachers', $datas);

        $maildatavalue = $this->GetEmail(3);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('sitename' => $siteinfo['sitename'], 'name' => $datas['name'], "emailid" => $datas['email'], "password" => $_POST['password']));
        $objmail->SetTo($datas['email']);
        $objmail->MailSend();
        return $userid;
    }

    function supcusRegister($datas) {


        unset($datas['id']);
        $datas["registration_id"] = 'SPL' . time();
        if ($datas['productGroup']) {
            $datas['product_group_id'] = implode(",", $datas['productGroup']);
        }
        if ($datas['serviceArea']) {
            $datas['city_id'] = implode(",", $datas['serviceArea']);
        }
        if ($datas['serviceGroup']) {
            $datas['service_category'] = implode(",", $datas['serviceGroup']);
        }

        $datas["password"] = md5($datas["password"]);
        $datas["usertype"] = "4";
        $profileid = $this->dbcon->Insert('itf_user_profile', $datas);
        $datas["profile_id"] = $profileid;
        $userid = $this->dbcon->Insert('itf_teachers', $datas);

        $maildatavalue = $this->GetEmail(3);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('sitename' => $siteinfo['sitename'], 'name' => $datas['name'], "emailid" => $datas['email'], "password" => $_POST['password']));
        $objmail->SetTo($datas['email']);
        $objmail->MailSend();
        return $userid;
    }

    function checkTeacherEmailId($email) {
        $sql = "select id from itf_teachers where email='" . $email . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['id']) and !empty($datas['id']))
            return true;
        else
            return false;
    }

    function GetInfoByEmailId($emailid) {
        $sql = "select * from itf_teachers where username='" . $emailid . "' and usertype != 1";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function CheckEmail($emailid) {

        $sql = "select * from itf_teachers where email='" . $emailid . "'";
        $datas = $this->dbcon->Query($sql);

        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function userUniqueByUsername($username) {
        $sql = "select * from itf_teachers where username='" . $username . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['username']) and !empty($datas['username']))
            return "1";
        else
            return "0";
    }

    function UsercheckTeacherIdname($username) {
        $sql = "select * from itf_teachers where username='" . $username . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['username']) and !empty($datas['username']))
            return "1";
        else
            return "0";
    }

    

    function deleteTeacherDetails($Id) {
        $sql = "delete from itf_teachers where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function PublishStudentBlock($ids) {
        $infos = $this->CheckStudent($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_child', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

}

?>
