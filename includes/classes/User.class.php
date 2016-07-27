<?php

class User {

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
        $sql = "select * from itf_users where id='" . $id . "'";
        return $this->dbcon->Query($sql);
    }

    function getAdminInfo($id) {
        $sql = "select * from itf_users where username='admin'";
        return $this->dbcon->Query($sql);
    }

    function SelectMail() {
        $sql = "select email from  itf_users  where user_type='1'";
        return $this->dbcon->Query($sql);
    }

    function loginAdminUser($uname, $pass) {
        $sql = "select * from itf_users where username='" . $this->dbcon->EscapeString($uname) . "' and 	password ='" . $this->dbcon->EscapeString(md5($pass)) . "' and user_type='1'";
        if ($DD = $this->dbcon->Query($sql)) {
            $_SESSION['LoginInfo'] = array('USNAME' => $DD['name'], 'USINFO' => $DD['email'], 'USERID' => $DD['id'], 'first_name' => $DD['first_name'], 'last_name' => $DD['last_name']);
            return '1';
        } else
            return '0';
    }
    function loginDashboardUser($uname, $pass) {
    	$sql = "select * from itf_users where username='" . $this->dbcon->EscapeString($uname) . "' and 	password ='" . $this->dbcon->EscapeString(md5($pass)) . "' and user_type='2'";
    	if ($DD = $this->dbcon->Query($sql)) {
    		$_SESSION['LoginInfo'] = array('USNAME' => $DD['name'], 'USINFO' => $DD['email'], 'USERID' => $DD['id'], 'first_name' => $DD['first_name'], 'last_name' => $DD['last_name'], 'org_id' => $DD['org_id']);
    		return '1';
    	} else
    		return '0';
    }
    function loginTeacher($email, $pass) {
        $sql = "select id,first_name,last_name,email,url from itf_teachers where email='" . $this->dbcon->EscapeString($email) . "' and 	password ='" . $this->dbcon->EscapeString(md5($pass)) . "' and status='1'";
        ;
        if ($row = $this->dbcon->Query($sql)) {
            $_SESSION['teacherLoginInfo'] = array('email' => $row['email'], 'user_id' => $row['id'], 'first_name' => $row['first_name'], 'last_name' => $row['last_name'], 'url' => $row['url']);
            return '1';
        } else
            return '0';
    }

    function userLogin($uname, $pass) {
        $sql = "select U.*,P.expiry_date from itf_users U
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
                if ($DD["user_type"] == "4") {
                    $_SESSION['FRONTUSER']["user_type"] = "2";
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

    function user_add($datas) {
        unset($datas['id']);
        $datas['ass_course'] = implode(',', $datas['ass_course']);
        $datas['ass_summercamp'] = implode(',', $datas['ass_summercamp']);
        $this->dbcon->Insert('itf_users', $datas);
    }

    function admin_update($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        $this->dbcon->Modify('itf_users', $datas, $condition);
    }

    function user_update($datas) {
        $userinfo = $this->CheckUser($datas['id']);
        $profile_info = $this->CheckProfile($userinfo['profile_id']);
        $condition = array('id' => $datas['id']);
        $profile_condition = array('id' => $profile_info['id']);
        $datas['ass_course'] = implode(',', $datas['ass_course']);
        $datas['ass_summercamp'] = implode(',', $datas['ass_summercamp']);
        unset($datas['id']);
        if (empty($datas['password'])) {
            unset($datas['password']);
        } else {
            $datas['password'] = md5($datas['password']);
        }
        $this->dbcon->Modify('itf_users', $datas, $condition);
    }

    function member_update($datas) {

        $condition = array('id' => $datas['id']);

        $this->dbcon->Modify('itf_membership', $datas, $condition);
    }

    function memberadded($datas) {
        $this->dbcon->Insert('itf_membership', $datas);
    }

    function front_user_update($datas) {
        $userinfo = $this->CheckUser($datas['id']);
        $profile_info = $this->CheckProfile($userinfo['profile_id']);
        $condition = array('id' => $datas['id']);
        $profile_condition = array('id' => $profile_info['id']);
        unset($datas['id']);

        if (isset($_FILES['image']['name'])) {
            if (!empty($_FILES['image']['name'])) {
                @unlink(PUBLICFILE . "profile/" . $profile_info['profile_image']);
                $fimgname = "plucka_" . $datas['name'] . "_" . rand();
                $objimage = new ITFImageResize();
                $objimage->load($_FILES['image']['tmp_name']);
                $objimage->save(PUBLICFILE . "profile/" . $fimgname);
                $imagename = $objimage->createnames;

                $datas['profile_image'] = $imagename;
            }
        }
        if (isset($datas['serviceArea']) and !empty($datas['serviceArea'])) {
            $datas['city_id'] = implode(',', $datas['serviceArea']);
        }

        if (isset($datas['serviceGroup']) and !empty($datas['serviceGroup'])) {
            $datas['service_category'] = implode(',', $datas['serviceGroup']);
        }
        $this->dbcon->Modify('itf_users', $datas, $condition);
        $this->dbcon->Modify('itf_user_profile', $datas, $profile_condition);
    }

    function user_delete($Id) {
        $profile = $this->getUserInfo($Id);
        $sql1 = "delete from itf_user_profile where id in(" . $profile['profile_id'] . ")";
        $this->dbcon->Query($sql1);
        $sql = "delete from itf_users where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function member_delete($Id) {
        $sql = "delete from itf_membership where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function ShowAllUser() {
        $sql = "select *  from itf_users";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCustomer() {
        $sql = "select *  from itf_users where user_type = 2";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllTeacher() {
        $sql = "select *  from itf_users where user_type = 3 order by id desc";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllBoth() {
        $sql = "select *  from itf_users where user_type = 4";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllSupplier() {
        $sql = "select *  from itf_users where user_type = 3";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllMemberPlan() {
        $sql = "select *  from itf_membership";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllRequests() {
        $sql = "select M.*,U.name  from itf_money_request M LEFT JOIN itf_users U on U.id = M.user_id";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllUserSearch($txtsearch) {
        $sql = "select * from itf_users where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckUser($UsId) {
        $sql = "select * from itf_users where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function checkTeacherId($Ids, $password) {
        $sql = "select id,first_name,last_name,email,password from itf_teachers where id='" . $Ids . "' and password='" . md5($password) . "' limit 1";
        $row = $this->dbcon->Query($sql);
        return $row;
    }

    function checkTeacherPassword($Ids, $password) {
        $sql = "select id from itf_teachers where id='" . $Ids . "' and password='" . md5($password) . "' limit 1";
        $row = $this->dbcon->Query($sql);
        if ($row['id']) {
            return true;
        } else {
            return false;
        }
    }

    function CheckProfile($id) {
        $sql = "select * from itf_user_profile where id='" . $id . "'";
        return $this->dbcon->Query($sql);
    }

    function getUserInfo($UsId) {
        $sql = "select U.id as user_id,U.* from itf_users U where U.id='" . $UsId . "' ";
        return $this->dbcon->Query($sql);
    }

    function CheckMembership($UsId) {
        $sql = "select U.* from itf_membership U where U.id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function PublishBlock($ids) {
        $infos = $this->CheckUser($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_users', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    function PublishMember($ids) {
        $infos = $this->CheckMembership($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_membership', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    function ChangePassword($data) {

        $userid = $_SESSION['LoginInfo']['USERID'];
        $userinfo = $this->CheckUser($userid);
        $data = $_REQUEST;
        $userinfo['password'] . '==' . md5($data["oldpassword"]);
        if ($userinfo['password'] == md5($data["oldpassword"])) {


            $datas = array('password' => md5($data["newpassword"]));
            $condition = array('id' => $userid);
            $this->dbcon->Modify('itf_users', $datas, $condition);
            return true;
        } else {
            return false;
        }
    }

    function changeTeacherPassword($userid, $password) {
        // $userinfo = $this->checkTeacherId($userid, $password);
        if ($userid != '') {
            $datas = array('password' => md5($password));
            $condition = array('id' => $userid);
            $this->dbcon->Modify('itf_teachers', $datas, $condition);
            return true;
        } else {
            return false;
        }
    }

    function ChangePasswordFront($newpassword) {
        $userid = $_SESSION['FRONTUSER']['id'];
        $userinfo = $this->CheckUser($userid);
        $datas = array('password' => md5($newpassword));
        $condition = array('id' => $userid);
        $this->dbcon->Modify('itf_users', $datas, $condition);
        return true;
    }

    function GetEmail($id) {
        $sql = "select * from itf_mails where id='" . $id . "'";
        return $this->dbcon->Query($sql);
    }

    function forgotPasswordAdmin($tomail) {

        $userdetail = $this->CheckEmail($tomail);


        if (isset($userdetail['id'])) {
            $newpass = "Bay_Area_" . substr(time(), -4);
            $datas = array('password' => md5($newpass));
            $condition = array('id' => $userdetail['id']);
            unset($datas['id']);
            $this->dbcon->Modify('itf_users', $datas, $condition);
            $maildatavalue = $this->GetEmail(9);
            $maildatavalue['mailsubject'] = 'New Password';
            //     echo '<pre>';print_r($userdetail); echo '<pre>';print_r($datas);
            $objmail = new ITFMailer();
            $objmail->SetSubject($maildatavalue['mailsubject']);
            $objmail->SetBody($maildatavalue['mailbody'], array('USERNAME' => $userdetail['username'], "PASSWORD" => $newpass));
            $objmail->SetTo($userdetail['email']);
            $objmail->MailSend();
            //   exit;
            return true;
        } else
            return false;
    }

    function customerRegister($datas) {


        $sql = "SELECT * FROM itf_users_temp where id='" . $datas . "' ";
        //mail('shahdeep.ishant@gmail.com','ishant.saxena@gmail.com', "test",  $sql);
        $data = $this->dbcon->Query($sql);
        $profileid = $this->dbcon->Insert('itf_user_profile', $data);
        $data["profile_id"] = $profileid;
        $userid = $this->dbcon->Insert('itf_users', $data);

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
        $admin_mail = $this->CheckUser(1);
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
            $datas["user_type"] = "2";
            $datas["registration_id"] = 'CPL' . time();
        } elseif ($type == "Supplier") {
            $datas["user_type"] = "3";
            $datas["registration_id"] = 'SPL' . time();
        } else {
            $datas["user_type"] = "4";
            $datas["registration_id"] = 'BPL' . time();
        }
        //$datas["user_type"] = "2";
        $datas["expiry_date"] = $end;
        //echo "<pre>";print_r($datas);die;
//        $datas["registration_id"] = 'CPL'.time();
//        if($datas['payment_type']=="account"){
//            $datas['status'] = 0;
//            $maildatavalue = $this->GetEmail(12);
//            $objmail = new ITFMailer();
//            $objmail->SetSubject($maildatavalue['mailsubject']);
//            $objmail->SetBody($maildatavalue['mailbody'],array('name'=>$datas['name'],"emailid"=>$datas['email']));
//            $objmail->SetTo($admin_mail['email']);
//            $objmail->MailSend();
//        }
        $profileid = $this->dbcon->Insert('itf_users_temp', $datas);
        //$datas["profile_id"] = $profileid;
        //$userid = $this->dbcon->Insert('itf_users_temp',$datas);
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
        $datas["user_type"] = "3";
        $profileid = $this->dbcon->Insert('itf_user_profile', $datas);
        $datas["profile_id"] = $profileid;
        $userid = $this->dbcon->Insert('itf_users', $datas);

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
        $datas["user_type"] = "4";
        $profileid = $this->dbcon->Insert('itf_user_profile', $datas);
        $datas["profile_id"] = $profileid;
        $userid = $this->dbcon->Insert('itf_users', $datas);

        $maildatavalue = $this->GetEmail(3);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('sitename' => $siteinfo['sitename'], 'name' => $datas['name'], "emailid" => $datas['email'], "password" => $_POST['password']));
        $objmail->SetTo($datas['email']);
        $objmail->MailSend();
        return $userid;
    }

    function getProductGroups() {
        $sql = "select * from itf_product_group where status = 1";

        return $this->dbcon->FetchAllResults($sql);
    }

    function getServiceArea() {
        $sql = "select * from itf_service_area where status = 1";

        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckEmailId($emailid) {
        $sql = "select * from itf_users where email='" . $emailid . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return true;
        else
            return false;
    }

    function GetInfoByEmailId($emailid) {
        $sql = "select * from itf_users where username='" . $emailid . "' and user_type != 1";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function checkTeacherEmailId($emailid) {
        $sql = "select id,first_name,last_name,email from itf_teachers where email='" . $emailid . "' and status='1'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function checkTeachersId($id) {
        $sql = "select id,first_name,last_name,email from itf_teachers where id='" . $id . "' and status='1'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function CheckEmail($emailid) {

        $sql = "select * from itf_users where email='" . $emailid . "'";
        $datas = $this->dbcon->Query($sql);

        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function userUniqueByUsername($username) {
        $sql = "select * from itf_users where username='" . $username . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['username']) and !empty($datas['username']))
            return "1";
        else
            return "0";
    }

    function UserCheckUsername($username) {
        $sql = "select * from itf_users where username='" . $username . "'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['username']) and !empty($datas['username']))
            return "1";
        else
            return "0";
    }

    function NormalUserLogin($datainfo) {
        $sql = "select id,name,last_name,username,email from  itf_users where  (email='" . $this->dbcon->EscapeString($datainfo['username']) . "' or username = '" . $this->dbcon->EscapeString($datainfo['username']) . "')
		        and password='" . md5($this->dbcon->EscapeString($datainfo['password'])) . "' and status ='1'";


        if ($DD = $this->dbcon->Query($sql)) {
            return $DD;
        } else
            return '0';
    }

    function ForgotTeacherPassword($useremail) {
        $validEmail = $this->checkTeacherEmailId($useremail);
        if ($validEmail[first_name] != '') {
            $newpass = "Bayar_" . substr(time(), -4);
            $datas = array('password' => md5($newpass));
            $condition = array('id' => $validEmail['id']);
            unset($datas['id']);
            $this->dbcon->Modify('itf_teachers', $datas, $condition);
            $maildatavalue = $this->GetEmail(10);
            $objmail = new ITFMailer();
            $objmail->SetSubject('New Password');
            $objmail->SetBody($maildatavalue['mailbody'], array('NAME' => $validEmail['first_name'],
                'USERNAME' => $validEmail['email'], "PASSWORD" => $newpass));
            $objmail->SetTo($validEmail['email']);
            $objmail->MailSend();
            return true;
        } else
            return false;
    }

    function updatePassword($id, $newpass) {
        $validEmail = $this->checkTeachersId($id);
        //echo $newpass.'<pre>';print_r($validEmail);
        if ($validEmail[first_name] != '') {
            //$newpass = "Bayar_" . substr(time(), -4);
            $datas = array('password' => md5($newpass));
            $condition = array('id' => $validEmail['id']);

            unset($validEmail['id']);
            $this->dbcon->Modify('itf_teachers', $datas, $condition);
            return true;
        } else
            return false;
    }

    function ForgotPasswordUrl($useremail) {
        $validEmail = $this->checkTeacherEmailId($useremail);
        if ($validEmail[first_name] != '') {
            $utils = new Utils;
            $encrypted = $utils->encryptIds($validEmail['id']);
            $url = '<a  href="' . SITEURL . '/index.php?itfpage=teacherlogin&itemid=generate_password&checkinfo=' . $encrypted .
                    '"  >Click Here For Generate New Password</a>';
            $maildatavalue = $this->GetEmail(20);
            $objmail = new ITFMailer();
            $objmail->SetSubject('Generate Password Url');
            $objmail->SetBody($maildatavalue['mailbody'], array('NAME' => $validEmail['first_name'],
                'LINK' => $url));
            $objmail->SetTo($validEmail['email']);
            $objmail->MailSend();
            return true;
        } else
            return false;
    }

    function checkGeneratePasswordUrl($Ids) {
        //    echo $Ids;
        $utils = new Utils;
        $teacherId = $utils->decryptIds($Ids);
        $sql = "select id from itf_teachers where id='" . $teacherId . "' and status='1' limit 1";
        $row = $this->dbcon->Query($sql);
        if ($row['id']) {
            return $row['id'];
        } else {
            return false;
        }
    }

    function generatePassword($teacherInfo) {
        $utils = new Utils;
        $teacherId = $utils->decryptIds($teacherInfo['id']);
        $sql = "select id,first_name,last_name,email from itf_teachers where id='" . $teacherId . "' and status='1'";
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email'])) {
            $datas = array('password' => md5($teacherInfo['password1']));
            $condition = array('id' => $datas['id']);
            unset($datas['id']);
            $this->dbcon->Modify('itf_teachers', $datas, $condition);
            return $datas;
        } else {
            return false;
        }
        return $datas;
    }

    function showAllSuppliers($service_category) {
        if (isset($service_category)) {
            $sql = "select U.id as user_id,U.*,UNIX_TIMESTAMP(U.entrydate) as created_date,P.* from itf_users U
              LEFT JOIN itf_user_profile P ON U.profile_id = P.id where U.user_type=3 and P.service_category IN('" . $service_category . "') ";
            return $this->dbcon->FetchAllResults($sql);
        } else {

            return array();
        }
    }

    function addCategory($postdata) {
        //echo"<pre>"; print_r($postdata); die;
        $sql = "select U.*,UP.product_group_id from itf_users U
              INNER JOIN itf_user_profile UP on UP.id = U.profile_id where U.id = '" . $_SESSION['FRONTUSER']['id'] . "'  ";
        $res = $this->dbcon->Query($sql);

        $sql2 = "select id from itf_category where catname = '" . $postdata['category'] . "'";
        $res2 = $this->dbcon->Query($sql2);

        $data = array('product_group_id' => $res2['id'] . ',' . $res['product_group_id']);

        $condition = array('id' => $res['profile_id']);
        $this->dbcon->Modify('itf_user_profile', $data, $condition);
    }

    function removeCategory($postdata) {
        $sql = "select U.*,UP.product_group_id from itf_users U
              INNER JOIN itf_user_profile UP on UP.id = U.profile_id where U.id = '" . $_SESSION['FRONTUSER']['id'] . "'  ";
        $res = $this->dbcon->Query($sql);

        $cat = explode(',', $res['product_group_id']);
        foreach ($cat as $key => $category) {
            if ($category == $postdata['id']) {
                unset($cat[$key]);
            }
        }
        $data = array('product_group_id' => implode(',', $cat));
        $condition = array('id' => $res['profile_id']);
        $this->dbcon->Modify('itf_user_profile', $data, $condition);
    }

    function removeMoneyRequest($id) {
        $sql = "delete from itf_money_request where id in(" . $id . ")";
        $this->dbcon->Query($sql);

        return $this->dbcon->querycounter;
    }

    function adminNotifMail($id) {
        $name = $_SESSION['FRONTUSER']['name'];
        $em = $_SESSION['FRONTUSER']['email'];

        $mailerdiv = '<div class="cart_cont"><div class="suply">
            <table>
                <tr>
                    <th bgcolor="#666" style="text-align:center; padding:5px 20px; width:100px">Supplier ID</th>
                    <th bgcolor="#666" style="text-align:center; padding:5px 20px; width:100px">Quote Title</th>
                    <th bgcolor="#666" style="text-align:center; padding:5px 20px; width:100px">Bid Price</th>
                </tr>
            </table>
</div>
</div>';
        $totalgrand = 0;
        foreach ($id as $key => $value) {

            $mailerdiv.='<div class="cart_cont"><div class="suply">
                 <table>
                    <tr>
                        <td  style="text-align:center; padding:5px 10px; width:100px">' . $value['supplier_id'] . '</td>
                        <td style="text-align:center; padding:5px 10px; width:100px">' . $value['quote_name'] . '</td>
                        <td style="text-align:center; padding:5px 10px; width:100px">' . $value['bid_amount'] . '</td>
                    </tr>
                </table>
</div>
</div>';
            $totalgrand += $value['bid_amount'];
        }
        $mailerdiv.='<div class="cart_cont"><div class="suply">
<p> Grand Total : ' . $totalgrand . ' </p>
</div>
</div>';
        $admin_mail = $this->CheckUser(1);
        $email = $this->SelectMail();
        $adminemail = $email['email'];


        $maildatavalue = $this->GetEmail(13);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('name' => $name, 'email' => $em, 'Productdetail' => $mailerdiv));
        $objmail->SetTo($adminemail);
        $objmail->MailSend();
    }

    function getAllmemberPlan() {
        $sql = "select *  from itf_membership where type='Customer' and status=1";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getAllmemberPlanSup() {
        $sql = "select *  from itf_membership where type='Supplier' and status=1";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getAllmemberPlanBoth() {
        $sql = "select *  from itf_membership where type='Both' and status=1";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getAmountPlan($id) {
        $sql = "select *  from itf_membership where id=$id";
        return $this->dbcon->Query($sql);
    }

    function tempData($id) {
        $sql = "select *  from itf_users_temp where id=$id";
        return $this->dbcon->Query($sql);
    }

    function getCheckPayMem($UsId) {
        $sql = "select *from itf_membership where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function freeRegister($datas) {

        $datas['password2'] = $datas['password'];
        //echo "<pre>";print_r($datas);die;
        $userinfo = $this->CheckMembership($datas['memberid']);
        //echo "<pre>";print_r($userinfo);die;
        $type = $userinfo['type'];
        $day = $userinfo['duration_day'];
        $durationtime = $userinfo['duration_type'];
        $end = date('Y-m-d', strtotime('+' . $day . $durationtime));

        //echo $total=$day+$durationtime;die;
        $admin_mail = $this->CheckUser(1);
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
            $datas["user_type"] = "2";
            $datas["registration_id"] = 'CPL' . time();
        } elseif ($type == "Supplier") {
            $datas["user_type"] = "3";
            $datas["registration_id"] = 'SPL' . time();
        } else {
            $datas["user_type"] = "4";
            $datas["registration_id"] = 'BPL' . time();
        }
        //$datas["user_type"] = "2";
        $datas["expiry_date"] = $end;
        //echo "<pre>";print_r($datas);die;
//        if($datas['payment_type']=="account"){
//            $datas['status'] = 0;
//            $maildatavalue = $this->GetEmail(12);
//            $objmail = new ITFMailer();
//            $objmail->SetSubject($maildatavalue['mailsubject']);
//            $objmail->SetBody($maildatavalue['mailbody'],array('name'=>$datas['name'],"emailid"=>$datas['email']));
//            $objmail->SetTo($admin_mail['email']);
//            $objmail->MailSend();
//        }
        $profileid = $this->dbcon->Insert('itf_user_profile', $datas);
        $datas["profile_id"] = $profileid;
        $userid = $this->dbcon->Insert('itf_users', $datas);


        $maildatavalue = $this->GetEmail(2);
        $objmail = new ITFMailer();
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('sitename' => $siteinfo['sitename'], 'name' => $datas['name'], "username" => $datas['username'], "emailid" => $datas['username'], "password" => $datas['password2']));
        $objmail->SetTo($datas['email']);
        $objmail->MailSend();
        return $userid;
    }

    ########################## Student Function ###############################

    function ShowAllStu() {
        $sql = "select *  from itf_child where user_type=2 order by id DESC";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllStuSearch($txtsearch) {
        $sql = "select * from itf_child where  first_name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%') and user_type=2";
        return $this->dbcon->FetchAllResults($sql);
    }

    function Student_add($datas) {
        $pass = $this->getToken(6);
        $datas['password'] = md5($pass);
        $datas['password2'] = $pass;
        $datas['username'] = $datas['email'];
        $datas['user_type'] = 5;
        $datas['student_uni_id'] = "ST" . substr(time(), -4);


        $uploadimgs = new ITFImageResize();
        if (isset($_FILES['profile_image']['name']) and !empty($_FILES['profile_image']['name'])) {
            $uploadimgs->load($_FILES['profile_image']['tmp_name']);
            $image_tmp = BASEPATHS . "/itf_public/profile/ITF" . time();
            $uploadimgs->save($image_tmp);
            $newnames = $uploadimgs->createnames;
            $datas['profile_image'] = $newnames;
        }

        unset($datas['id']);
        $this->dbcon->Insert('itf_child', $datas);
        $maildatavalue = $this->GetEmail(12);
        $objmail = new ITFMailer();
        $objmail->SetTo($datas['email']);
        $objmail->SetSubject($maildatavalue['mailsubject']);
        $objmail->SetBody($maildatavalue['mailbody'], array('name' => $datas['name'], 'username' => $datas['email'], "password" => $pass));
        //echo"<pre>";print_r($objmail);die;
        $objmail->MailSend();
    }

    function Student_update($datas) {
        if (isset($datas['password']) and $datas['password'] != null) {
            $datas['password2'] = $datas['password'];
            $datas['password'] = md5($datas['password']);
        }
        $datas['user_type'] = 5;
        $condition = array('id' => $datas['id']);
        if (isset($_FILES['profile_image']['name']) and !empty($_FILES['profile_image']['name'])) {
            $uploadimgs->load($_FILES['profile_image']['tmp_name']);
            $image_tmp = BASEPATHS . "/itf_public/profile/ITF" . time();
            $uploadimgs->save($image_tmp);
            $newnames = $uploadimgs->createnames;
            $datas['profile_image'] = $newnames;
        }

        //  echo"<pre>";print_r($datas);die;
        unset($datas['id']);
        $this->dbcon->Modify('itf_child', $datas, $condition);
        if (isset($datas['password']) and $datas['password'] != null) {
            $data = CheckStudent($datas['id']);
            $maildatavalue = $this->GetEmail(14);
            $objmail->SetTo($data['email']);
            $objmail = new ITFMailer();
            $objmail->SetSubject($maildatavalue['mailsubject']);
            $objmail->SetBody($maildatavalue['mailbody'], array('name' => $datas['name'], "password" => $datas['password2']));
            //echo"<pre>";print_r($objmail);die;
            $objmail->MailSend();
        }
    }

    function stu_delete($Id) {
        $sql = "delete from itf_child where id in(" . $Id . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function CheckStudent($UsId) {
        $sql = "select * from itf_child  where class_id='" . $UsId . "'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function CheckStud($UsId) {
        $sql = "select * from itf_child  where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
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

    function PublishStudentsBlock($ids) {
        $infos = $this->CheckStudent($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_homework', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    function checkHomework($ids) {
        //echo $ids;die;
        $sql = "select * from itf_homework  where class_id='" . $ids . "'";
        return $this->dbcon->Query($sql);
    }

    function PublishHomeworkBlock($ids) {
        $infos = $this->checkHomework($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_homework', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    function AllClassListAcctoSchool($id) {
        $sql = "SELECT * FROM itf_class WHERE school_id ='" . $id . "' ORDER BY title ASC";
        return $this->dbcon->FetchAllResults($sql);
    }

    ########################## Student Function  Ends ###############################  

    function ShowAllStatus() {
        $sql = "select *  from itf_student_status where status = '1'";
        return $this->dbcon->FetchAllResults($sql);
    }
    function GetDashboardUser(){
    	$sql ="SELECT itf_org.`org_name`,itf_org.org_id FROM `itf_org` JOIN itf_users on itf_org.org_id = itf_users.org_id WHERE itf_users.user_type='2' and itf_users.status and  itf_users.id=".$_SESSION['LoginInfo']['USERID'];
    	return $this->dbcon->FetchAllResults($sql);
    
    }

}

?>
