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

			$emails  = $this->prepare_array($emails,'emails');

			$this->json($emails);
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

			$this->json($json);
		}
	}