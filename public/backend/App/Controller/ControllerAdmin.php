<?php 
	namespace App\Controller;

    use App\Controller\ControllerUser;
    use App\Controller\ControllerAuth;
    use App\Controller\ControllerCategory;

	use App\Model\User;
	
	use App\DAO\UserDAO;

	/**
     * @method $this->render->json($dataArray);
     */
	class ControllerAdmin extends ControllerUser{

        public function __construct(){
            parent::__construct();
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
        public function new_category($data = []){            
            $data['is_public'] = 1;
            $data['active'] = 1;
            $cat    = new ControllerCategory();
            $res = $cat->create($this->user, $data);
            $this->render->json($res);
        }

        public function edit_category($data = []){        
            $data['is_public'] = 1;
            $cat    = new ControllerCategory();
            $res = $cat->edit($this->user, $data);
            $this->render->json($res);
        }

        public function list_categories($data){
            $data['is_public'] = 1;
            $cat    = new ControllerCategory();
            $res = $cat->list($this->user, $data);
            $this->render->json($res);
        }

        public function remove_category($id){           
            $data['is_public'] = 1;
            $cat    = new ControllerCategory();
            $res = $cat->remove($this->user,['id' => $id]);
            $this->render->json($res);
        }
	}