<?php 
	namespace App\Controller;

    use App\Controller\Controller;
    use App\Controller\ControllerAuth;

	use App\Model\User;
	
	use App\DAO\UserDAO;

	/**
	 * @method $this->transform_obj_in_array($ObjArray, "$name");
	 */
	class ControllerUser extends Controller {

        public function list($data = []){
            $this->validate_access('adm');

			$user   = new User($data);
			$users  = UserDAO::find($user);
			$this->render->json($users);
		}

        public function add($data = []){

            $user = new User();
            $data = $user->fill_required_fields($data);

            if($data == ''){
                
                $data = UserDAO::create($user);

                if(is_numeric($data)){
                    $res['status'] 	= 'success';
                    $res['msg']		= 'Usuário cadastrado com sucesso!';
                    $res['id']		= $data;
                }else{
                    $res['status'] = 'error';
                    if($data){
                        $res['msg'] = $data;
                    }else{
                        $res['msg'] = 'Erro: não foi possivel cadastrar o usuário.';
                    }
                }
            }else{
                $res['status'] = 'error';
                $res['msg'] = $data;
            }
			$this->render->json($res);
		}

        public function edit($data = []){
            $this->validate_access(['user','adm']);
            
            $this->user->editPublicColumns($data);
			if(UserDAO::edit($this->user)){
				$res['status'] 	= 'success';
				$res['msg']		= 'Usuário atualizado com sucesso!';
			}else{
				$res['status'] = 'error';
				$res['msg'] = 'Erro: não foi possivel atualizar o usuário.';
			}

            $this->render->json($res);
		}

        public function remove($data = []){

            $this->validate_access(['user','adm']);

            if($this->user->getId() == $data['id']){
                $data = UserDAO::remove($this->user);
                if(is_bool($data)){
                    $res['status'] 	= 'success';
                    $res['msg']		= 'Usuário deletado com sucesso!';
                }else{
                    $res['status'] = 'error';
                    if($data != ''){
                        $res['msg'] = $data;
                    }else{
                        $res['msg'] = 'Erro: não foi possivel deletar o usuário.';
                    }
                    
                }
            }
			$this->render->json($res);
		}
	}