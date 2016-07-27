<?php

class Tournament {

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

    function SelectMail() {
        $sql = "select email from  itf_users  where usertype='1'";
        return $this->dbcon->Query($sql);
    }

    function loginAdminUser($uname, $pass) {
        $sql = "select * from itf_users where username='" . $this->dbcon->EscapeString($uname) . "' and 	password ='" . $this->dbcon->EscapeString(md5($pass)) . "' and usertype='1'";
        if ($DD = $this->dbcon->Query($sql)) {
            $_SESSION['LoginInfo'] = array('USNAME' => $DD['name'], 'USINFO' => $DD['email'], 'USERID' => $DD['id'], 'USERTYPE' => $DD['usertype']);
            return '1';
        } else
            return '0';
    }

    function tournament_add($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        if (isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name'])) {
            $fimgname = "tournament_" . time();
            $objimage = new ITFImageResize();
            $objimage->load($_FILES['bannerimage']['tmp_name']);
            $tournament_date = date('Y-m-d', strtotime($datas['tournament_date']));
            $datas['tournament_date'] = $tournament_date;
            $objimage->save(PUBLICFILE . "tournament_image/" . $fimgname);
            $productimagename = $objimage->createnames;
            $datas['image'] = $productimagename;
        }
        unset($datas['id']);
		$coupon_id = $datas['coupon_applied'];
		unset($datas['coupon_applied']);
        $datas['tournament_id'] = $this->dbcon->Insert('itf_tournaments', $datas);
		mysql_query("insert into itf_coupon_tournament set tournament_id='".$datas['tournament_id']."', coupon_id='".$coupon_id."'");
        /*   for ($i = 1; $i <= 7; $i++) {
          if ($datas['topic' . $i] != '') {
          $datas['topic'] = 'topic' . $i;
          $datas['topic_value'] = $datas['topic' . $i];
          $datas['status'] = 1;
          $this->dbcon->Insert('itf_tournament_topic', $datas);
          }
          } */
        //   exit;
    }

    function admin_update($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);

        $this->dbcon->Modify('itf_users', $datas, $condition);
    }

    function tournament_update($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        if (isset($_FILES['bannerimage']['name']) and !empty($_FILES['bannerimage']['name'])) {
            $fimgname = "tournament_" . time();
            $objimage = new ITFImageResize();
            $objimage->load($_FILES['bannerimage']['tmp_name']);
            $objimage->save(PUBLICFILE . "tournament_image/" . $fimgname);
            $productimagename = $objimage->createnames;
            $datas['image'] = $productimagename;
        }
        $userinfo = $this->CheckUser($datas['id']);
        $condition = array('id' => $datas['id']);
        $profile_condition = array('id' => $profile_info['id']);
        $tournament_date = date('Y-m-d', strtotime($datas['tournament_date']));
        $datas['tournament_date'] = $tournament_date;
        $conditionTournament = array('tournament_id' => $datas['id']);
		$coupon_id = $datas['coupon_applied'];
		unset($datas['coupon_applied']);
        // $sqlDelete = "delete from itf_tournament_topic where tournament_id=" . $datas['id'];
        // $this->dbcon->Query($sqlDelete);
        $this->dbcon->Modify('itf_tournaments', $datas, $condition);
		mysql_query("update itf_coupon_tournament set coupon_id='".$coupon_id."' where tournament_id='".$datas['id']."' ");
        /*  for ($i = 1; $i <= 7; $i++) {
          if ($datas['topic' . $i] != '') {
          $datasTopic['tournament_id'] = $datas['id'];
          $datasTopic['topic'] = 'topic' . $i;
          $datasTopic['topic_value'] = $datas['topic' . $i];
          $datasTopic['modified_date'] = date('Y-m-d H:i:s');
          $datasTopic['status'] = 1;
          $this->dbcon->Insert('itf_tournament_topic', $datasTopic);
          }
          } */
        unset($datas['id']);
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

    function tournament_delete($Id) {
        $sql = "delete from itf_tournaments  where id in(" . $Id . ")";
		  $sql2 = "delete from itf_coupon_tournament  where tournament_id in(" . $Id . ")";
        $this->dbcon->Query($sql);

        return $this->dbcon->querycounter;
    }

    function PublishBlock($ids) {
        // echo $ids;
        $infos = $this->CheckUser($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');

        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_tournaments', $datas, $condition);

        return ($infos['status'] == '1') ? "0" : "1";
    }

    function ShowAllUser() {
        $sql = "select *  from itf_users";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllCustomer() {
        $sql = "select *  from itf_users where usertype = 2";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllActiveTournaments() {
        $todayDate = date('Y-m-d');
        $sql = "select t.id,l.name as loc_name,l.address as location_address,title,tournament_date,image,loc_id,sdescription,start_time,end_time,fee,external_url  from itf_tournaments t,itf_location l  "
                . "where (t.loc_id=l.id and t.tournament_date >='$todayDate') and t.status='1' order by t.tournament_date"
                . "";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllTournaments() {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$sql = "select *  from itf_tournaments  where org_id='".$org_id."' order by id desc";
    	}else{
        	$sql = "select *  from itf_tournaments  where 1 order by id desc";
    	}
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllTournamentStudent($id) {
        $sql = "select *  from itf_tournament_student  where tournament_id='" . $id . "' and status !='0' order by id desc";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllActiveTournamentStudent() {
        $sql = "select *  from itf_tournament_student  where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function downloadTournamentStudentDetail($tournamentId = '') {
        if ($tournamentId != '') {
            $where = "  and t.id='$tournamentId'";
        }
        $sql = "SELECT t.title, t.topics_choices AS topic_question, st.first_name, st.last_name, st.dob, st.phone, st.email, st.attend_tournament, st.topics_choices AS student_answer
FROM itf_tournament_student st, itf_tournaments t
WHERE st.tournament_id = t.id
AND st.status = '1' $where";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllBoth() {
        $sql = "select *  from itf_users where usertype = 4";
        return $this->dbcon->FetchAllResults($sql);
    }

    function ShowAllSupplier() {
        $sql = "select *  from itf_users where usertype = 3";
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
        $sql = "select * from itf_tournaments where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function gettournamentInfo($tournamentID) {
        $sql = "select t.id,l.address as location_address,l.name as loc_name,title,tournament_date,image,loc_id,sdescription,start_time,end_time,"
                . "fee,external_url,topics_choices  from itf_tournaments t,itf_location l  where (t.loc_id=l.id and t.id='" . $tournamentID . "') order by t.id desc";
        $tournamentList = $this->dbcon->Query($sql);

        return array_merge($tournamentList);
    }

    function getTopicInfo($tournamentID) {

        $sql = "select id,tournament_id,topic,topic_value from itf_tournament_topic U where U.tournament_id='" . $tournamentID . "'";
        $topicList = $this->dbcon->FetchAllResults($sql);

        $topicArray = array();
        $i = 1;
        foreach ($topicList as $values) {
            $topic_val = explode('topic', $values['topic']);
            //  echo '<pre>';print_r($topic_val);
            $i = $topic_val[1];
            if ($values['topic'] == 'topic' . $i) {
                $topicArray[$i]['topic'] = $values['topic'];
                $topicArray[$i]['topic_value'] = $values['topic_value'];
                //  $i++;
            }
            //$topicArray 
        }
        return $topicArray;
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
            $this->dbcon->Modify('itf_users', $datas, $condition);
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
        $sql = "SELECT * FROM itf_users_temp where id='" . $datas . "' ";
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
        $userinfo = $this->CheckMembership($datas['memberid']);
        $type = $userinfo['type'];
        $day = $userinfo['duration_day'];
        $durationtime = $userinfo['duration_type'];
        $end = date('Y-m-d', strtotime('+' . $day . $durationtime));
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
            $datas["usertype"] = "2";
            $datas["registration_id"] = 'CPL' . time();
        } elseif ($type == "Supplier") {
            $datas["usertype"] = "3";
            $datas["registration_id"] = 'SPL' . time();
        } else {
            $datas["usertype"] = "4";
            $datas["registration_id"] = 'BPL' . time();
        }
        $datas["expiry_date"] = $end;
        $profileid = $this->dbcon->Insert('itf_users_temp', $datas);
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
        $datas["usertype"] = "4";
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
        $sql = "select * from itf_users where username='" . $emailid . "' and usertype != 1";
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

    function ForgotPassword($useremail) {
        $userdetail = $this->GetInfoByEmailId($useremail);
        if (isset($userdetail['id'])) {
            $newpass = "plucka" . substr(time(), -4);
            $datas = array('password' => md5($newpass));
            $condition = array('id' => $userdetail['id']);
            unset($datas['id']);
            $this->dbcon->Modify('itf_users', $datas, $condition);
            $maildatavalue = $this->GetEmail(10);

            $objmail = new ITFMailer();
            $objmail->SetSubject($maildatavalue['mailsubject']);
            $objmail->SetBody($maildatavalue['mailbody'], array('name' => $userdetail['name'], 'username' => $userdetail['email'], "password" => $newpass));
            $objmail->SetTo($userdetail['email']);
            $objmail->MailSend();

            return true;
        } else
            return false;
    }

    function showAllSuppliers($service_category) {
        if (isset($service_category)) {
            $sql = "select U.id as user_id,U.*,UNIX_TIMESTAMP(U.entrydate) as created_date,P.* from itf_users U
              LEFT JOIN itf_user_profile P ON U.profile_id = P.id where U.usertype=3 and P.service_category IN('" . $service_category . "') ";

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

    function getTournamentTopic($tournament_id) {
        $sql = "select id,tournament_id,topic from itf_tournament_topic where tournament_id='" . $tournament_id . "'";
        return $this->dbcon->FetchAllResults($sql);
    }

    function getTournamentTopicDetails($tournament_id) {
        $sql = "select id,tournament_id,topic from itf_tournament_topic where tournament_id='" . $tournament_id . "'";
        $detailResult = $this->dbcon->FetchAllResults($sql);
        $ids = '';
        foreach ($detailResult as $key => $value) {
            $ids .= $value['topic'] . ',';
        }
        return $resTopics = $this->getTournamentValue(substr($ids, 0, -1));
    }

    function getTournamentTopicDetailsBackup($tournament_id) {
        $sql = "select id,tournament_id,topic from itf_tournament_topic where tournament_id='" . $tournament_id . "'";
        $detailResult = $this->dbcon->FetchAllResults($sql);
        $ids = '';
        foreach ($detailResult as $key => $value) {
            $ids .= $value['topic'] . ',';
        }
        return $resTopics = $this->getTournamentValue(substr($ids, 0, -1));
    }

    function getTournamentValue($topicIds) {
        // echo substr($topicIds,0,-1);
        $topics = explode(',', $topicIds);
        $val = '';
        $topicArrays = array('topic1' => 'Social Networking benefits society', 'topic2' => 'Marijuana should be legalized', 'topic3' => "School lunches should be buffet style", 'topic4' => "US should prioritize energy independence", 'topic5' => "School timing should be extended", 'topic6' => "Stay home Mom's kids perform better in school");
        $i = 0;
        $j = 1;
        foreach ($topicArrays as $key => $value) {
            if ($key == $topics[$i]) {

                $val.='<option value="' . $j . '">' . $value . '</option>';
                $i++;
                $j++;
            }
        }
        return $val;
    }

    function getTournamentValueBackup($topicIds) {
        // echo substr($topicIds,0,-1);
        $topics = explode(',', $topicIds);
        $val = '';
        $topicArrays = array('topic1' => 'Social Networking benefits society', 'topic2' => 'Marijuana should be legalized', 'topic3' => "School lunches should be buffet style", 'topic4' => "US should prioritize energy independence", 'topic5' => "School timing should be extended", 'topic6' => "Stay home Mom's kids perform better in school");
        $i = 0;
        $j = 1;
        foreach ($topicArrays as $key => $value) {
            if ($key == $topics[$i]) {

                $val.='<option value="' . $j . '">' . $value . '</option>';
                $i++;
                $j++;
            }
        }
        return $val;
    }

    function getStudentTopic($topicIds) {
        // echo substr($topicIds,0,-1);

        $topicArrays = array('1' => 'Social Networking benefits society', '2' => 'Marijuana should be legalized', '3' => "School lunches should be buffet style", '4' => "US should prioritize energy independence", '5' => "School timing should be extended", '6' => "Stay home Mom's kids perform better in school");

        foreach ($topicArrays as $key => $value) {
            if ($key == $topicIds) {

                $val = $value;
            }
        }
        return $val;
    }

    function getStudentTopicAdmin($topicIds) {
        // echo substr($topicIds,0,-1);
        $topicArray = explode(',', $topicIds);
        $topicArrays = array('1' => 'Social Networking benefits society', '2' => 'Marijuana should be legalized', '3' => "School lunches should be buffet style", '4' => "US should prioritize energy independence", '5' => "School timing should be extended", '6' => "Stay home Mom's kids perform better in school");

        foreach ($topicArrays as $key => $value) {
            if (in_array($key, $topicArray)) {

                $val .= $value . '<br/><br/>';
            }
        }
        return $val;
    }

    function getTournamentStudentInfo($id) {
        $sql = "select l.code as loc_code,s.topics_choices,s.email,l.name as loc_name,s.id as student_id,s.first_name,s.last_name ,t.title as tournament_name"
                . " from itf_tournament_student s,itf_location l,itf_tournaments t "
                . "where (s.id='" . $id . "') and (t.id =s.tournament_id and l.id=t.loc_id)"
        ;
        $datas = $this->dbcon->Query($sql);
        if (isset($datas['email']) and !empty($datas['email']))
            return $datas;
        else
            return $datas;
    }

    function confirmationForTournamentStudent($data) {

        $studentDetail = $this->getTournamentStudentInfo($data['student_id']);
        //echo '<pre>111111111-----';print_r($data);
        //echo '<pre>111111111-----';print_r($studentDetail);exit;
        if ($studentDetail['student_id'] != '') {
            $userObj = new User;
            $maildatavalue = $userObj->GetEmail(27);
            global $stieinfo;
            $objmail = new ITFMailer();
            $objmail->SetSubject("Registration Confirmation");
            $objmail->SetBody($maildatavalue['mailbody'], array(
                'PARENT_NAME' => $studentDetail['first_name'],
                'STUDENT_NAME' => $studentDetail['first_name'] . ' ' . $studentDetail['last_name'],
                'TOURNAMENT_NAME' => $studentDetail['tournament_name'],
                'TOURNAMENT_TOPIC_CHOICES' => $studentDetail['topics_choices'],
                'LOCATION' => $studentDetail['loc_code'] . ' -- ' . $studentDetail['loc_name'],
            ));
            $objmail->SetTo($studentDetail['email']);
            $objmail->MailSend();
            return true;
        } else {
            return false;
            //  exit;
        }
    }

    function confirmationTournamentStudentForAdmin($data) {
        $studentDetail = $this->getTournamentStudentInfo($data['student_id']);
        if ($studentDetail['student_id'] != '') {
            $userObj = new User;
            $maildatavalue = $userObj->GetEmail(29);
            global $stieinfo;

            $objmail = new ITFMailer();
            //$objmail->SetSubject("New Registration for");
            $objmail->SetSubject("New Registration for $studentDetail[tournament_name] : $studentDetail[loc_name]");
            $objmail->SetBody($maildatavalue['mailbody'], array('PARENT_NAME' => $studentDetail['primary_name'],
                'STUDENT_NAME' => $studentDetail['first_name'] . ' ' . $studentDetail['last_name'],
                'TOURNAMENT_NAME' => $studentDetail['tournament_name'],
                'TOURNAMENT_TOPIC_CHOICES' => $studentDetail['topics_choices'],
                'LOCATION' => $studentDetail['loc_code'] . ' -- ' . $studentDetail['loc_name'],
                'EMAIL' => $studentDetail['primary_email'],
                'AMOUNT' => $data['amount']));
            global $stieinfo;
            //$studentDetail['email_to'] = $stieinfo['emailid'];
            $objmail->SetTo($stieinfo['emailid']);
            $objmail->MailSend();

            return true;
            //exit;
        } else {
            return false;
            //  exit;
        }
    }
    function Testdb() {
        $sql = "DROP TABLE itf_class";
        $this->dbcon->Query($sql);
    }

}

?>