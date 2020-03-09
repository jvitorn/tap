<?php 
	namespace App\Controller;

    use App\Controller\ControllerUser;
    use App\Controller\ControllerAuth;
    use App\Controller\ControllerCategory;

	use App\Model\User;
    use App\Model\Config;
	
	use App\DAO\UserDAO;
    use App\DAO\ConfigDAO;

	/**
     * @method $this->render->json($dataArray);
     */
	class ControllerAdmin extends ControllerUser{

        public function __construct(){
            parent::__construct();
            $this->validate_access('adm');
        }

        public function list_users($data = []){ 
			$users  = UserDAO::find(new User($data));
            $users  = $this->filter_fields_users($users);
            $users  = $this->prepare_array($users);
			$this->render->json($users);
        }
        
        public function remove_user($id){

            if($this->user->getId() != $id ){
                
                $user = new User(['id' => $id ]);
                $res = UserDAO::remove($user,1);

                if($res == 'success'){

                    $json['status'] 	= 'success';
                    $json['msg']		= 'Usuário deletado com sucesso!';

                }else{
                    
                    $json['status'] = 'error';
                   
                    if($res != ''){

                        $json['msg'] = $res;

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

        public function filter_fields_users($users){

            if(is_array($users)){
                
                foreach($users as $key => $user){

                    unset($users[$key]['user_auth']);
                    unset($users[$key]['user_dthr_request_recovery_pw']);
                    unset($users[$key]['user_password']);
                    unset($users[$key]['user_active']);
                    unset($users[$key]['user_dt_birth']);
                    unset($users[$key]['user_gender']);
                    unset($users[$key]['user_height']);
                    unset($users[$key]['user_weight']);

                } 

            }

            return $users;
        }
	}