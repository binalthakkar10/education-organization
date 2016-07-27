<?php

class Utils {

    private $encryptKey = '12122';

    public static function getUserIP() {
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif ($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        elseif ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FROM'])
            $ip = $_SERVER['HTTP_FROM'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];

        return $ip;
    }

    public function encryptIds($pure_string) {


        $identifier = time();
        $salt = $this->encryptKey;
        //   $salt =  $salt;
        $encodedid = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $pure_string, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        return $encodedid;
        //return $pure_string;

        /* $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->encryptKey), $pure_string, MCRYPT_MODE_CBC, md5(md5($this->encryptKey))));
          return $encrypted; */
        /* $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
          $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
          $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $this->encryptKey, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
          return $encrypted_string; */
        $stringCode = date('Y-m-d') . '_' . $pure_string . '_' . $this->encryptKey;
        return urlencode($stringCode);
        return $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->encryptKey), $pure_string, MCRYPT_MODE_CBC, md5(md5($this->encryptKey))));
    }

    public function decryptIds($encrypted) {
        $encrypted = urlencode($encrypted);
        $encrypted = str_replace("+", "%2B", $encrypted);
        $encrypted = urldecode($encrypted);
        //  $salt = sfConfig::get('app_encodingkey');;
        $salt = $this->encryptKey;
        //$id= $id;
        return $id = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($encrypted), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        //return $encrypted;
        /* $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->encryptKey), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($this->encryptKey))), "\0");
          return $decrypted; */

        /* $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
          $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
          $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $this->encryptKey, $encrypted, MCRYPT_MODE_ECB, $iv);
          return $decrypted_string; */
        $stringCode = date('Y-m-d') . '_' . $encrypted . '_' . $this->encryptKey;
        return base64_encode($stringCode);
//echo $encrypted;
        return $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->encryptKey), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($this->encryptKey))), "\0");
    }

}
