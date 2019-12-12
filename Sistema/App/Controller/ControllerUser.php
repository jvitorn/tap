<?php 
	namespace App\Controller;

	use App\Controller\Controller;

	use App\Model\User;
	
	use App\DAO\UserDAO;

	class ControllerUser extends Controller {

		public function list($params = null){
			
			$param = isset($params['post'])? $params['post']: null;
			$user = new User($param);

			$userList = UserDAO::find($user);
			
			$data['user'] = $userList;

			$this->render->view('user/list', $data);
		}

		public function new($param){
			
			$post = $param['post'];
			$action = isset($post['action']) ? $post['action']:null;

			if(count($param) > 0 && $action == 'new' ){

				$json = ['status' => 0, 'msg' => 'Erro: não foi possivel cadastrar o usuario!'];			
				$data['post']['senha'] = '123';
				$user = new User($data['post']);
				$user->setUpdatedAt(date('Y-m-d h:i:s'));
				$user->setCreatedAt(date('Y-m-d h:i:s'));
								
				if( is_array( UserDAO::create($user) ) ){
					$json['status'] = 1;
					$json['msg'] = 'Usuario cadastrado com sucesso!';
				}

				echo json_encode($json);
			}else{

				$user =  new User;

	            $data['title'] = 'Novo usuário';
	            $data['user'] = $user->getVars();
	            $this->render->view('user/detail',$data);

			}

		}

		public function edit($param){

			if( isset($param['post']['edit']) ){

				$json = ['status' => 0, 'msg' => 'Ops, nao foi possivel editar este usuário'];
				$user = new User($param['post']);

				if(UserDAO::edit($user)){
					$json['status'] = 1;
					$json['msg'] = 'Usuário atualizado com sucesso!';
				}

				echo json_encode($json);

			}else{

				//se for o usuário com id 1 (admin) nao é possivel editar
				if($param['url'][0] == 1) header('Location:'.PAINEL);
				
				$user = new User();
				$user->setId($param['url'][0]);

	            $data['title'] 	= 'Editar usuário';
	            $data['user']	= UserDAO::find($user)[0];
	            $this->render->view('user/detail', $data);

			}	
		}

		public function remove($param){

			$json = ['status' => 0, 'msg' => 'Ops, nao foi possivel excluir este usuário'];
			$user = new User($param['post']);

			if(UserDAO::remove($user)){
				$json['status'] = 1;
				$json['msg'] = "Usuário deletado com sucesso";
			}
			
			echo json_encode($json);

		}
	}