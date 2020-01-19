<?php 
	namespace Src\Classes;

	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class ClassEmail {

    	private $mail;
    	private $enderecos;

    	public function __construct($enderecos){
    		$this->enderecos = $enderecos;

    		$this->mail = new PHPMailer(true);
    		try {
                //Server settings
                $this->mail->CharSet = 'UTF-8';
                $this->mail->SMTPDebug = 1;
                $this->mail->isSMTP();
                $this->mail->Host       = MAIL_HOST;
                $this->mail->SMTPAuth   = true;
                $this->mail->Username   = MAIL_ADDRESS;
                $this->mail->Password   = MAIL_PW;
                $this->mail->SMTPSecure = MAIL_TYPE;
                $this->mail->Port       = MAIL_PORT;

                $this->mail->setFrom(MAIL_ADDRESS, MAIL_OWNER);

                foreach($this->enderecos as $key => $usuario){
                	$this->mail->addAddress($usuario->getEmail(), $usuario->getNome() );
                }
            }catch(Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    	}

    	public function Conteudo_redefinirSenha(){

			$this->mail->isHTML(true);
            $this->mail->Subject = 'Redefinir Senha';
            $this->mail->Body    =  file_get_contents(DIR_REQ.'Src/Includes/Email/redefinirSenha.php');
            $this->mail->Body = str_replace('$url', DIR_PAGE.'redefinir-senha/'.$this->enderecos[0]->getRedefinePwUrl() , $this->mail->Body);
    	}

    	public function Conteudo_novidade(){

    	}

    	public function Conteudo_aviso(){

    	}

    	public function enviar(){
    		try{
    			$this->mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    	}
    }
