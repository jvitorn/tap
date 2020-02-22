<?php 
	namespace Src\Classes;

	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class ClassEmail {

    	private $mail;

    	public function __construct($contact,$title,$content){
            
            $this->contact  = $contact;
            
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
                $this->mail->addAddress($contact['email'], $contact['name']);

                $this->mail->Subject = $title;
                $this->mail->Body    = $content;

            }catch(Exception $e) {
                // return "Message could not be sent. Mailer Error: {$e->ErrorInfo}";
            }
    	}

        /**
         * $data deverá conter array com os dados
         * $data[$key] deverá conter o nome da tabela (user, task,post, etc...)
         * $data[$key][$value] deverá conter o array com os dados da tabela/entidade
         * $data[$key][$k] deverá conter a chave do dado, usando o nome do campo como chave
         * $data[$key][$k][$val] deverá conter o valor da chave.
         *
         * Exemplo:
         *
         * $data =[
         *      'user' =>[
         *          'name'      => 'jdc',
         *          'active'    => '123'
         *      ],
         *      'task' =>[
         *          'name'      => 'fazer TCC',
         *          'prazo'     => '12/06/2020'
         *       ]
         *  ];
         *
         */

        public function create_email($data){

            foreach($data as $key => $value){
                foreach($value as $k => $val){
                    $string = '{'.$key.'.'.$k.'}';
                    $this->mail->Body = str_replace($string,$val, $this->mail->Body);
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
