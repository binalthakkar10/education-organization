<?php

class Newsletter {

    public $dbcon;
    private $from_name;
    private $from_mail;

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function admin_add($datas) {
        $this->dbcon->Insert(' itf_newsletter', $datas);
    }

    function admin_delete($UId) {
        $sql = "delete from  itf_newsletter where id in(" . $UId . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    function admin_subscriber_delete($UId) {
        $sql = "delete from  itf_subscriber where id in(" . $UId . ")";
        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

    //Update Data

    function admin_update($datas) {
        $condition = array('id' => $datas['id']);
        unset($datas['id']);
        $this->dbcon->Modify(' itf_newsletter', $datas, $condition);
    }

    function ShowAllNewsletter() {
        $sql = "select * from  itf_newsletter";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function ShowActiveNewsletter() {
        $sql = "select * from  itf_newsletter where status='1'";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function ShowAllSubscribers($source = '') {
        if ($source) {
            $where = " where source ='$source'";
        }
        $sql = "select * from itf_subscriber $where";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function ShowAllSubscribersActive($source = '') {
        if ($source) {
            $where = " and source ='$source'";
        }
        $sql = "select * from itf_subscriber where status='1'  $where order by source desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function ShowAllSubscribersActiveSource() {
        $sql = "select distinct(source) from itf_subscriber where status='1' order by source desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function GetPageData($id) {
        $sql = "select * from  itf_newsletter  where id='" . $id . "'";
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    function CheckNewsletter($UsId) {
        $sql = "select * from  itf_newsletter where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
    }

    function getSubscriberInfo($UsId) {
        $sql = "select * from  itf_subscriber where id='" . $UsId . "' and (status !=0 and source !='subscriber_admin')";
        return $this->dbcon->Query($sql);
    }

    function checkSubscriberEmail($UsId) {
        $sql = "select id from  itf_subscriber where email='" . $email . "'";
        return $this->dbcon->Query($sql);
    }

    function CheckSubscribersActive($UsId) {
        $sql = "select * from itf_subscriber  where id='" . $UsId . "'";
        return $this->dbcon->Query($sql);
        //exit;
    }

    function PublishBlock($ids) {
        $infos = $this->CheckNewsletter($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');
        $condition = array('id' => $ids);
        $this->dbcon->Modify(' itf_newsletter', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    function PublishBlocksubscriber($ids) {
        $infos = $this->CheckSubscribersActive($ids);
        if ($infos['status'] == '1')
            $datas = array('status' => '0');
        else
            $datas = array('status' => '1');
        $condition = array('id' => $ids);
        $this->dbcon->Modify('itf_subscriber', $datas, $condition);
        return ($infos['status'] == '1') ? "0" : "1";
    }

    // Front Functions

    function add_member($email) {
        $datas = array('email' => trim(strtolower($email)));
        $this->dbcon->Insert('itf_subscriber', $datas);
    }

    function addSubscriber($email, $source) {

        $date = date('Y-m-d H:i:s');
        $datas = array('email' => trim(strtolower($email)),
            'source' => trim($source),
            'created_date' => $date,
            'modified_date' => $date);
        $this->dbcon->Insert('itf_subscriber', $datas);
    }

    function addSubscriberDetails($subscriberData) {
        $subscriberData['created_date'] = date('Y-m-d H:i:s');
        $subscriberData['modified_date'] = date('Y-m-d H:i:s');
        //$_POST['source'] = 'Class_Admin';
        if ($subscriberData['source'] == 'Class_Admin') {
            $subscriberData['status'] = 1;
        } else {
            $subscriberData['status'] = 0;
        }
        if ($subscriberData['primary_email'] != '') {
            $email = $subscriberData['primary_email'];
        } else {
            $email = $subscriberData['email'];
        }
        $subscriberData['email'] = $email;
        if ($subscriberData['loc_id'] != '') {
            $locationCode = $this->getLocationCode($subscriberData['loc_id']);
            $subscriberData['source'] = $locationCode['code'];
        }
        //  echo '<pre>';print_r($subscriberData);exit;
        if ($this->checkSubscriber($subscriberData['email'])) {
            $this->dbcon->Insert('itf_subscriber', $subscriberData);
        }
    }

    function getLocationCode($locId) {
        $sql = "select id,code from  itf_location where id='" . $locId . "'";
        return $this->dbcon->Query($sql);
    }

    function checkSubscriber($email) {
        $sql = "select id from  itf_subscriber where email='" . trim($email) . "'";
        $res = $this->dbcon->Query($sql);
        if ($res) {
            return false;
        } else {
            return true;
        }
    }

    function ShowAllNewsletterSend($id) {
        $sql = "select * from  itf_newsletter where id='$id'";
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    function GetAdminEmail() {

        $sql = "select * from itf_users  where user_type=1";
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    function admin_send_newsletter($datas) {
        $emailadmin = $this->GetAdminEmail();
        //  echo '<pre>';print_r($emailadmin);
        $mess = $this->ShowAllNewsletterSend($datas['newsletter']);

        $template = $mess['message'];
        $from = $emailadmin['email'];
        $emailids = $datas[select2];
        $headers = "From: $from";
        $subject = $mess['subject'];
        $siteobj = new Site();
        $this->site_info = $siteobj->CheckSite(1);
        $this->from_mail = $this->site_info['emailid'];
        $this->from_name = $this->site_info['sitename'];

        if ($this->from_name != "") {
            $headers = "From: " . $this->from_name . " <" . $this->from_mail . ">" . " \r\n";
            //$headers .= "Reply-To: ".$this->from_name." <".$this->from_mail.">"." \r\n";
        } else {
            $headers = "From: " . $this->from_mail . " \r\n";
            $headers .= "Reply-To: " . $this->from_mail . " \r\n";
        }
        $objmail = new ITFMailer();
         $objmail->SetSubject($subject);
        foreach ($emailids as $email) {
            $str = "--_" . md5(uniqid(time())) . " \r\n";
            $str .= "Content-type:text/html; charset=iso-8859-1 \r\n";
            $str .= "Content-Transfer-Encoding: 7bit \r\n \r\n";
            $str .= trim($template) . " \r\n \r\n";
            $objmail->SetBodyNewsLetter($template);
            $objmail->SetTo($email);
            //$objmail->SetCc($datas['teacher_email']);
           $objmail->MailSend();
       
          //  $ok = mail($email, $subject, $str, $headers);
        }
        return $datas;
    }

}

?>
