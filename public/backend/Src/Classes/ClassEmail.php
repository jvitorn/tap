<?php 
	namespace Src\Classes;

	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class ClassEmail {

    	private $mail;

    	public function __construct($dataArray,$title,$content){
            
            $contact    = $dataArray['user'];
            $this->mail = new PHPMailer(true);
            
            try {
                //Server settings
                $this->mail->CharSet    = 'UTF-8';
                $this->mail->SMTPDebug  = 0;
                $this->mail->isSMTP();
                $this->mail->Host       = MAIL_HOST;
                $this->mail->SMTPAuth   = true;
                $this->mail->Username   = MAIL_ADDRESS;
                $this->mail->Password   = MAIL_PW;
                $this->mail->SMTPSecure = MAIL_TYPE;
                $this->mail->Port       = MAIL_PORT;
                $this->mail->isHTML(true);

                $this->mail->setFrom(MAIL_ADDRESS, MAIL_OWNER);
                $this->mail->addAddress($contact['user_email'], $contact['user_name']);

                $this->mail->Subject = $title;
                $this->mail->Body    = $content;

                $this->create_email($dataArray);

            }catch(Exception $e) {
                // return "Message could not be sent. Mailer Error: {$e->ErrorInfo}";
            }
    	}

        public function create_email($data){

            foreach($data as $key => $value){
                foreach($value as $k => $val){
                    if(!is_array($val)){
                        $string = '{'.$key.'}';
                        $this->mail->Body = str_replace($string,$val, $this->mail->Body);    
                    }
                }
            }

            $template = file_get_contents(DIR_EMAIL_TEMPLATES.'template.php');
            $this->mail->Body = str_replace('{{content}}', $this->mail->Body, $template);
        }

    	public function send(){
    		try{
    			$this->mail->send();
                return 'success';
            } catch (\Exception $e) {
                return "Erro: não foi possivel enviar o email";
            }
    	}        
    }
