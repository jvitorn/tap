<?php 
	namespace App\Controller;

    use App\Controller\ControllerUser;
    use App\Controller\ControllerAuth;
    use App\Controller\ControllerActionType;

	use App\Model\User;
	
	use App\DAO\UserDAO;

	/**
	 * @method $this->transform_obj_in_array($ObjArray, "$name");
	 */
	class ControllerAdmin extends ControllerUser{

        public function __construct(){
            $this->validate_access('adm');
        }

        public function list($data = []){ 
			$user   = new User($data);
			$users  = UserDAO::find($user);
			$this->render->json($users);
        }
        
        public function remove_user($id){

            if($this->user->getId() != $id ){
                $user = new User( ['id' => $id ]);

                $data = UserDAO::remove($user);
                if(is_bool($data)){
                    $json['status'] 	= 'success';
                    $json['msg']		= 'Usuário deletado com sucesso!';
                }else{
                    $json['status'] = 'error';
                    if($data != ''){
                        $json['msg'] = $data;
                    }else{
                        $json['msg'] = 'Erro: não foi possivel deletar o usuário.';
                    }
                }
            }else{
                $json['status']  = 'error';
                $json['msg']     = "Voce não pode se excluir através desta função"; 
            }
            
            $this->render->json($json);
		}

        /* adicionar tipos de ações apenas para quem criou */
        public function new_action_type($data = []){            
            $data['is_public'] = 1;
            $data['active'] = 1;
            $cAT    = new ControllerActionType();
            $res = $cAT->new_action_type($this->user, $data);
        }

        public function edit_action_type($data = []){        
            $data['is_public'] = 1;
            $cAT    = new ControllerActionType();
            $res = $cAT->edit_action_type($this->user, $data);
        }

        public function list_action_type($data){
            $data['is_public'] = 1;
            $cAT    = new ControllerActionType();
            $res = $cAT->list($this->user, $data);
        }

        public function remove_action_type($id){           
            $data['is_public'] = 1;
            $cAT    = new ControllerActionType();
            $cAT->remove($this->user,['id' => $id]);  
        }
	}