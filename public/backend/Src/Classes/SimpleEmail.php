<?php 
    namespace Src\Classes;

    class SimpleEmail {
        
        private $header;
        private $subject;
        private $message;
        private $from_mail;
        private $from_name;
        private $mail_to;

        public function __construct($user){
            if(function_exists('mail')){
                $this->from_mail = 'jdc_santos@outlook.com';
                $this->from_name = "TAP";
                $this->mail_to = $user;
            }else{
                echo 'mail() has been disabled';
            }
            
        }

        private function headers(){

            $encoding = "utf-8";

            // Preferences for Subject field
            $subject_preferences = array(
                "input-charset" => $encoding,
                "output-charset" => $encoding,
                "line-length" => 76,
                "line-break-chars" => "\r\n"
            );

            $this->header = "Content-type: text/html; charset=".$encoding." \r\n";
            $this->header .= "From: ".$this->from_name." <".$this->from_mail."> \r\n";
            $this->header .= "MIME-Version: 1.0 \r\n";
            $this->header .= "Content-Transfer-Encoding: 8bit \r\n";
            $this->header .= "Date: ".date("r (T)")." \r\n";
            $this->header .= iconv_mime_encode("Subject", $this->subject, $subject_preferences);
        }

        public function send_reset_password_request(){
            $this->subject = "Redefinição de senha";
            $this->message = file_get_contents(DIR_REQ.'Src/Includes/Email/redefinirSenha.php');
            $this->send();
        }

        public function send_confirm_account_request(){

        }

        public function send_delete_account_request(){

        }
        
        private function send(){
            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
        
            /*
            * Setup email addresses and change it to your own
            */
            $from = "jdc_santos@outlook.com";
            $to = "jdc_santos@outlook.com";
            $subject = "Simple test for mail function";
            $message = "This is a test to check if php mail function sends out the email";
            $headers = "From:" . $from;
        
            /*
            * Test php mail function to see if it returns "true" or "false"
            * Remember that if mail returns true does not guarantee
            * that you will also receive the email
            */
            if(mail($to,$subject,$message, $headers))
            {
                echo "Test email send.";
            } 
            else 
            {
                echo "Failed to send.";
            }
        }
    }