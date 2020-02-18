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
            $this->from_mail = 'jdc_santos@outlook.com';
            $this->from_name = "TAP";
            $this->mail_to = $user;
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
            $this->headers();
            // the message
            $msg = "First line of text\nSecond line of text";

            // use wordwrap() if lines are longer than 70 characters
            $msg = wordwrap($msg,70);

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // send email
            try{
                $success = mail("jdc_santos@outlook.com","My subject",$msg,$headers);
                 
                print_r(error_get_last());
                

            }catch(\Exception $e){
                echo "nao foi";
                print_r($e);
            }
            
        }
    }