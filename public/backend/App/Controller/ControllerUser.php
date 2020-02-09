<?php 
	namespace App\Controller;

	use App\Controller\Controller;

	use App\Model\User;
	
	use App\DAO\UserDAO;

	/**
	 * @method $this->transform_obj_in_array($ObjArray, "$name");
	 */
	class ControllerUser extends Controller {

		public function list($data = []){
			$data['cols']  = 'id, name, email, created_at, updated_at, type';
			$data['cols'] .= ',height, weight, gender, active, dt_birth';

			$user = new User($data);
			$users = UserDAO::find($user,$data);
			$users = $this->transform_obj_in_array($users,'users');
			$this->render->json($users);
		}

		public function add($data = []){

			$data['type'] = 'user';
			$data['height'] = str_replace(',','.', $data['height']);
			$data['weight'] = str_replace(',','.', $data['weight']);
			$data['active'] = 1;
			$data['created_at'] = date('Y-m-d h:i:s');

			$user = new User($data);
			$data = UserDAO::create($user);

			if($data['error'] == '' && isset($data['res']['id'])){
				$res['status'] 	= 'success';
				$res['msg']		= 'Usuário cadastrado com sucesso!';
				$res['id']		= $data['res']['id'];
			}else{
				$res['status'] = 'error';
				if(strstr($data['error'],'nm_email_UNIQUE')){
					$res['error'] = 'Erro: email já cadastrado.';	
				}else{
					$res['error'] = 'Erro: não foi possivel cadastrar o usuário.';
				}
			}

			$this->render->json($res);
		}

		public function edit($data = []){
            
            if(isset($data['height'])){
                $data['height'] = str_replace(',','.', $data['height']);
            }

            if(isset($data['weight'])){
                $data['weight'] = str_replace(',','.', $data['weight']);
            }
            
            $data['updated_at'] = date('Y-m-d h:i:s');
            
            unset($data['auth']);
            unset($data['type']);
            unset($data['created_at']);
            unset($data['updated_at']);
            unset($data['cd_recovery_pw']);
            unset($data['dthr_request_recovery_pw']);

            $user = new User($data);
            
			if(UserDAO::edit($user)){
				$res['status'] 	= 'success';
				$res['msg']		= 'Usuário atualizado com sucesso!';
			}else{
				$res['status'] = 'error';
				$res['error'] = 'Erro: não foi possivel atualizar o usuário.';
			}

            $this->render->json($res);
		}

		public function remove($data = []){
            
            $user = new User();
            $user->setId($data['id']);
            
			if(UserDAO::remove($user)){
				$res['status'] 	= 'success';
				$res['msg']		= 'Usuário deletado com sucesso!';
			}else{
				$res['status'] = 'error';
				$res['error'] = 'Erro: não foi possivel deletar o usuário.';
            }
            
			$this->render->json($data);
		}
	}