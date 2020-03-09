<?php 
	namespace App\Controller;

	use App\Controller\Controller;

	use App\Model\User;
	use App\Model\Email;

	use App\DAO\UserDAO;
	use App\DAO\EmailDAO;

	use Src\Classes\ClassEmail;

	class ControllerEmail extends Controller {

		public function __construct(){
			parent::__construct();
		}

		public function list($data){
			$this->validate_access('adm');

			$emails = EmailDAO::find(new Email($data));

			$emails  = $this->prepare_array($emails);

			$this->render->json($emails);
		}

		public function edit($data){
			$this->validate_access('adm');

			unset($data['type']);

			$res = EmailDAO::edit(new Email($data));

			if($res == "success"){
			
				$json['status'] = 'success';
				$json['msg']	= 'Email atualizado com sucesso. ';
			
			}else{

				$json['status'] = 'error';
				$json['msg']	= 'Não foi possivel atualizar o email';
				$json['msg']   .= '';

			}

			$this->render->json($json);
		}

		public function send_confirm_account_request($user, $data){
			return $this->send_email($user,$data,'confirm_account_request');
		}

		public function send_confirm_account_success($user, $data){
			return $this->send_email($user,$data,'confirm_account_success');
		}

		public function send_reset_password_request($user,$data){
			return $this->send_email($user,$data,'reset_password_request');
		}

		public function send_reset_password_success($user,$data){
			return $this->send_email($user,$data,'reset_password_success');
		}

		public function send_delete_account_request($user,$data){
			return $this->send_email($user,$data,'delete_account_request');
		}

		public function send_delete_account_success($user,$data){
			return $this->send_email($user,$data,'delete_account_success');
		}

		private function send_email($user, $data, $email_type){
			$mEmail =  EmailDAO::find(new Email(['type' => $email_type]))[0];
			$email 	= new ClassEmail($user,$mEmail['email_title'],$mEmail['email_content'], $data);
			$email->create_email($data);
            return $email->send();
		}
	}