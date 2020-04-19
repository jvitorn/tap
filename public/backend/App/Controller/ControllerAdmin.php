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
     * @method $this->json($dataArray);
     */
	class ControllerAdmin extends ControllerUser{

        public function __construct(){
            parent::__construct();
            $this->validate_access('adm');
        }

        public function list_users($data = []){
            $data['type'] = 'user';
			$users  = UserDAO::find(new User($data));
            $users  = $this->filter_fields_users($users);
            $users  = $this->prepare_array($users,'users');

			$this->json($users);
        }

        public function add_admin($data = []){

            $data['type'] = 'adm';

            $user = new User($data);
            $auth = $user->generateAuthCode();

            $id = UserDAO::create($user);

            if(is_numeric($id)){
                
                $json['status']  = 'success';
                $json['msg']     = 'Administrador cadastrado com sucesso!';
                
                $user->setId($id);

                $arrUser = UserDAO::find($user)[0];

                $arrUser['auth'] = $auth;

                $dataArray = [ 'user' => $arrUser ];

                $resEmail = $this->send_email($dataArray,'confirm_account_request');
                               
                if($resEmail == "success"){
                    $json['status']  = 'success';
                    $json['msg']    .= ' um código de autenticação foi enviado para o email cadastrado!';
                }else{
                    $json['status']  = 'error';
                    $json['msg']     = 'Não foi possivel enviar o email de confirmação';
                    $json['msg']     = 'verifique se o email está correto!';
                    $data = UserDAO::remove(new User(['id' => $data]));
                }

            }else{
                $json['status'] = 'error';
                if($data){
                    $json['msg'] = $id;
                }else{
                    $json['msg'] = 'Erro: não foi possivel cadastrar o administrador.';
                }
            }

            $this->json($json);
        }

        public function list_admins($data = []){
            $data['type'] = 'adm';
            $users  = UserDAO::find(new User($data));
            $users  = $this->filter_fields_users($users);
            $users  = $this->prepare_array($users,'admins');

            $this->json($users);
        }
        
        public function remove_user($id){

            if($this->user()->getId() != $id ){
                
                if($id == 1 ||  $id == 2){
                    
                    $json['status'] ='error';
                    $json['msg'] = 'ATENÇÃO: não é possivel deletar este usuario.';

                }else{

                    $user = new User(['id' => $id ]);
                    $res = UserDAO::remove($user,1);

                    if($res == 'success'){

                        $json['status']     = 'success';
                        $json['msg']        = 'Usuário deletado com sucesso!';

                    }else{
                        
                        $json['status'] = 'error';
                       
                        if($res != ''){

                            $json['msg'] = $res;

                        }else{

                            $json['msg'] = 'Erro: não foi possivel deletar o usuário.';

                        }

                    }
                }

            }else{

                $json['status']  = 'error';
                $json['msg']     = "Voce não pode se excluir através desta função";

            }
            
            $this->json($json);
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