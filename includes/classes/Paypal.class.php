<?php
class Paypal {
   var $last_error;                 // holds the last error encountered
   var $ipn_log;                    // bool: log IPN results to text file?
   var $ipn_log_file;               // filename of the IPN log
   var $ipn_response;               // holds the IPN response from paypal   
   var $ipn_data = array();         // array contains the POST values for IPN
   var $fields = array();           // array holds the fields to submit to paypal

   function __construct() {
      $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
      $this->last_error = '';
      $this->ipn_log_file = 'ipn_log.txt';
      $this->ipn_log = true;
      $this->ipn_response = '';
      $this->add_field('rm','2');           // Return method = POST
      $this->add_field('cmd','_cart');
      $this->add_field('upload',1);
   }
   
   function add_field($field, $value) {
      $this->fields["$field"] = $value;
   }

   function paypalPost() {


      $urlParams = http_build_query($this->fields, '', '&');
      header('location:' .$this->paypal_url. '?' . $urlParams);
      exit;
      
      
   }
   
   function validateIpn() {

      $url_parsed=parse_url($this->paypal_url);        
      $post_string = '';    
      foreach ($_POST as $field=>$value) {
         $this->ipn_data["$field"] = $value;
         $post_string .= $field.'='.urlencode($value).'&'; 
      }
      $post_string.="cmd=_notify-validate"; // append ipn command

      // open the connection to paypal
      $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30); 
      if(!$fp) {
          
         $this->last_error = "fsockopen error no. $errnum: $errstr";
         $this->logIpnResults(false);       
         return false;
         
      } else { 
         fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
         fputs($fp, "Host: $url_parsed[host]\r\n"); 
         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
         fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
         fputs($fp, "Connection: close\r\n\r\n"); 
         fputs($fp, $post_string . "\r\n\r\n"); 
         while(!feof($fp)) { 
            $this->ipn_response .= fgets($fp, 1024); 
         } 
         fclose($fp); // close connection
      }
      
      if (eregi("VERIFIED",$this->ipn_response)) {
         $this->log_ipn_results(true);
         return true;       
         
      } else {
         $this->last_error = 'IPN Validation Failed.';
         $this->logIpnResults(false);   
         return false;
         
      }
      
   }
   
   function logIpnResults($success) {
       
      if (!$this->ipn_log) return;  // is logging turned off?
      $text = '['.date('m/d/Y g:i A').'] - '; 
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($this->ipn_data as $key=>$value) {
         $text .= "$key=$value, ";
      }
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n"); 

      fclose($fp);  // close file
   }

   function dumpFields() {
      echo "<h3>paypal_class->dump_fields() Output:</h3>";
      echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>"; 
      
      ksort($this->fields);
      foreach ($this->fields as $key => $value) {
         echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
      }
 
      echo "</table><br>"; 
   }
} 
