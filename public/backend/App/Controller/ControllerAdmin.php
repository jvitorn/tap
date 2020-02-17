<?php 
	namespace App\Controller;

    use App\Controller\ControllerUser;
    use App\Controller\ControllerAuth;

	use App\Model\User;
	
	use App\DAO\UserDAO;

	/**
	 * @method $this->transform_obj_in_array($ObjArray, "$name");
	 */
	class ControllerAdmin extends ControllerUser{

        public function list($data = []){
            $this->validate_access('adm');
			$user   = new User($data);
			$users  = UserDAO::find($user);
			$this->render->json($users);
        }
        
        public function remove($data = []){

            $this->validate_access('adm');

            if($this->user->getId() != $data['id'] ){
                $user = new User( ['id' => $data['id'] ]);

                $data = UserDAO::remove($user);
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
            }else{
                $res['status']  = 'error';
                $res['msg']     = "Voce não pode se excluir através desta função"; 
            }
            
            $this->render->json($res);
		}
	}