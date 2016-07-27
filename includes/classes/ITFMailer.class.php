<?php

define("LIBR", "\r\n");

class ITFMailer {

    private $from_name;
    private $from_mail;
    private $mail_to;
    private $mail_cc;
    private $mail_bcc;
    private $site_info = array();
    private $mail_headers;
    private $mail_subject;
    private $mail_body = "";
    private $male_template = "itf_email";
    private $valid_mail_adresses = true;
    private $uid;
    private $mail_priority = 3;
    private $att_files = array();
    private $msg = array();

    function __construct() {
        $siteobj = new Site();
        $this->site_info = $siteobj->CheckSite(1);
        $this->SetFrom($this->site_info['emailid']);
        $this->SetName($this->site_info['sitename']);
    }

    function SetTemplate($templatename) {
        $this->male_template = $templatename;
    }

    function SetTo($mailto) {
        $this->mail_to = $mailto;
    }

    function SetFrom($frommail) {
        $this->from_mail = $frommail;
    }

    function SetName($names) {
        $this->from_name = $names;
    }

    function SetCC($cc) {
        $this->mail_cc = $cc;
    }

    function SetBCC($bcc) {
        $this->mail_bcc = $bcc;
    }

    function SetSubject($mailsubject) {
        $this->mail_subject = $mailsubject;
    }

    function SetBody($bodytxt = "", $bodydata = array()) {
        $allkeysdata = array_keys($bodydata);
        foreach ($allkeysdata as $k => $itfdts)
            $allkeysdata[$k] = strtoupper("{" . $itfdts . "}");
        $allvaluesdata = array_values($bodydata);
        $tmp_bodytxt = str_replace($allkeysdata, $allvaluesdata, $bodytxt);
        $templatedata = file_get_contents(BASEPATHS . "/template/email/" . $this->male_template . ".html");
        $tmp_bodytxt = str_replace("{MAIL_DATA}", $tmp_bodytxt, $templatedata);
        $this->mail_body = $tmp_bodytxt;
        //echo $this->mail_body; die;
    }

   function GetBody() {
     return $this->mail_body;
   }

function SetBodyNewsLetter($bodytxt) {
       $this->mail_body = $bodytxt;
    }
    function MailSend() {
        $this->valid_mail_adresses = true;
        if (!$this->check_mail_address($this->mail_to)) {
            $this->msg[] = "Error, the \"mailto\" address is empty or not valid.";
            $this->valid_mail_adresses = false;
        }

        if (!$this->check_mail_address($this->from_mail)) {
            $this->msg[] = "Error, the \"from\" address is empty or not valid.";
            $this->valid_mail_adresses = false;
        }

        if ($this->mail_cc != "") {
            if (!$this->check_mail_address($this->mail_cc)) {
                $this->msg[] = "Error, the \"Cc\" address is not valid.";
                $this->valid_mail_adresses = false;
            }
        }

        if ($this->mail_bcc != "") {
            if (!$this->check_mail_address($this->mail_bcc)) {
                $this->msg[] = "Error, the \"Bcc\" address is not valid.";
                $this->valid_mail_adresses = false;
            }
        }


        if ($this->valid_mail_adresses) {

            $this->from_name = $this->strip_line_breaks($this->from_name);
            $this->from_mail = $this->strip_line_breaks($this->from_mail);
            $this->mail_to = $this->strip_line_breaks($this->mail_to);
            $this->mail_cc = $this->strip_line_breaks($this->mail_cc);
            $this->mail_bcc = $this->strip_line_breaks($this->mail_bcc);
            $this->mail_subject = $this->strip_line_breaks($this->mail_subject);
            $this->create_mime_boundry();
            $this->mail_body = $this->create_msg_body($this->mail_body);

            $this->mail_headers = $this->create_mail_headers();

            $this->Send();
        } else {
            return;
        }
    }

    function get_msg_str() {
        $messages = "";
        foreach ($this->msg as $val) {
            $messages .= $val . "<br>\n";
        }
        return $messages;
    }

    // use this to prent formmail spamming
    function strip_line_breaks($val) {
        $val = preg_replace("/([\r\n])/", "", $val);
        return $val;
    }

    function check_mail_address($mail_address) {
        $pattern = "/^[\w-]+(\.[\w-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4})$/i";
        if (preg_match($pattern, $mail_address)) {

            return true;
        } else {
            return false;
        }
    }

    function create_mime_boundry() {
        $this->uid = "_" . md5(uniqid(time()));
    }

    function get_file_data($filepath) {
        if (file_exists($filepath)) {
            if (!$str = file_get_contents($filepath)) {
                $this->msg[] = "Error while opening attachment \"" . basename($filepath) . "\"";
            } else {
                return $str;
            }
        } else {
            $this->msg[] = "Error, the file \"" . basename($filepath) . "\" does not exist.";
            return;
        }
    }

    // remember "LIBR" is the line break defined in constact above
    function create_msg_body($mail_msg, $cont_tranf_enc = "7bit", $type = "text/html", $enc = "iso-8859-1") {
        $str = "--" . $this->uid . LIBR;
        $str .= "Content-type:" . $type . "; charset=" . $enc . LIBR;
        $str .= "Content-Transfer-Encoding: " . $cont_tranf_enc . LIBR . LIBR;
        $str .= trim($mail_msg) . LIBR . LIBR;
        return $str;
    }

    function create_mail_headers() {
        if ($this->from_name != "") {
            $headers = "From: " . $this->from_name . " <" . $this->from_mail . ">" . LIBR;
            //$headers .= "Reply-To: ".$this->from_name." <".$this->from_mail.">".LIBR;
        } else {
            $headers = "From: " . $this->from_mail . LIBR;
            $headers .= "Reply-To: " . $this->from_mail . LIBR;
        }
        if ($this->mail_cc != "")
            $headers .= "Cc: " . $this->mail_cc . LIBR;
        if ($this->mail_bcc != "")
            $headers .= "Bcc: " . $this->mail_bcc . LIBR;
        $headers .= "MIME-Version: 1.0" . LIBR;
        $headers .= "X-Mailer: Attachment Mailer ver. 1.0" . LIBR;
        $headers .= "X-Priority: " . $this->mail_priority . LIBR;
        $headers .= "Content-Type: multipart/mixed;" . LIBR . chr(9) . " boundary=\"" . $this->uid . "\"" . LIBR;
        $headers .= "This is a multi-part message in MIME format." . LIBR;
        return $headers;
    }

    // use for $dispo "attachment" or "inline" (f.e. example images inside a html mail
    function FileAttach($file, $dispo = "attachment") {
        if (!$this->valid_mail_adresses)
            return;
        $file_str = $this->get_file_data($file);
        if ($file_str == "") {
            return;
        } else {
            $filename = basename($file);
            $file_type = 'application/jpg';
            $chunks = chunk_split(base64_encode($file_str));
            $mail_part = "--" . $this->uid . LIBR;
            $mail_part .= "Content-type:" . $file_type . ";" . LIBR . chr(9) . " name=\"" . $filename . "\"" . LIBR;
            $mail_part .= "Content-Transfer-Encoding: base64" . LIBR;
            $mail_part .= "Content-Disposition: " . $dispo . ";" . chr(9) . "filename=\"" . $filename . "\"" . LIBR . LIBR;
            $mail_part .= $chunks;
            $mail_part .= LIBR . LIBR;
            $this->att_files[] = $mail_part;
        }
    }

    function Send() {
        if (!$this->valid_mail_adresses)
            return;

        $mail_message = $this->mail_body;


        if (count($this->att_files) > 0) {
            foreach ($this->att_files as $val) {
                $mail_message .= $val;
            }
            $mail_message .= "--" . $this->uid . "--";
        }
        //mail($this->mail_to,"ttt",$mail_message,$this->mail_headers);
        //echo $this->mail_headers;
        //echo $mail_message="test";


        if (mail($this->mail_to, $this->mail_subject, $mail_message, $this->mail_headers)) {
            $this->msg[] = "Your mail is succesfully submitted.";
        } else {
            $this->msg[] = "Error while sending you mail.";
        }



    }

}

?>