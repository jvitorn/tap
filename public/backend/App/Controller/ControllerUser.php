<?php 
	namespace App\Controller;

    use App\Controller\Controller;
    use App\Controller\ControllerEmail;

	use App\Model\User;
	
    use App\DAO\UserDAO;
    
	/**
	 * @method $this->render->json($dataArray);
	 */
	class ControllerUser extends Controller {

        public function __construct(){
            parent::__construct();
        }

        public function add($data = []){

            $user = new User($data);
            $auth = $user->generateAuthCode();

            $id = UserDAO::create($user);

            if(is_numeric($id)){
                
                $json['status']  = 'success';
                $json['msg']     = 'Usuário cadastrado com sucesso!';
                
                $user->setId($id);

                $arrUser = UserDAO::find($user)[0];

                $arrUser['auth'] = $auth;

                $cEmail = new ControllerEmail();
                $arrData = [ 'user' => $arrUser ];
                $resEmail = $cEmail->send_confirm_account_request($arrUser, $arrData);
                
                if($resEmail == "success"){
                    $json['status']  = 'success';
                    $json['msg']    .= ', verifique seu email!';
                }else{
                    $json['status']  = 'error';
                    $json['msg']     = 'Não foi possivel enviar o email de confirmação';
                    $json['msg']     = 'verifique se o email está correto!';
                    $data = UserDAO::remove(new User(['id' => $data]));
                }

            }else{
                $json['status'] = 'error';
                if($data){
                    $json['msg'] = $data;
                }else{
                    $json['msg'] = 'Erro: não foi possivel cadastrar o usuário.';
                }
            }
			$this->render->json($json);
		}

        public function edit($data = []){
            $this->validate_access(['user','adm']);
            
            $user = new User($data);
            $user->setId($this->user->getId());

			if(UserDAO::edit($user)){
				$json['status'] 	= 'success';
				$json['msg']		= 'Usuário atualizado com sucesso!';
			}else{
				$json['status'] = 'error';
				$json['msg'] = 'Erro: não foi possivel atualizar o usuário.';
			}

            $this->render->json($json);
		}

        public function confirm_account($data = []){

            if(isset($data['auth']) && strlen($data['auth']) == 6){

                $res = UserDAO::active(new User($data));
                
                if($res == 'success'){
                    $json['status'] = 'success';
                    $json['msg']    = 'Usuário ativado com sucesso';

                    $arrUser = UserDAO::find(new User(['email'=> $data['email']]) )[0];

                    $cEmail = new ControllerEmail();
                    $arrData = [ 'user' => $arrUser ];
                    $resEmail = $cEmail->send_confirm_account_success($arrUser, $arrData);

                }else{
                    $json['status'] = 'error';
                    $json['msg']    = $res;
                }    
            }else{
                $json['status'] = 'error';
                $json['msg ']   = 'codigo inválido';
            }

            $this->render->json($json);
        }

        public function reset_password_request($data = []){
            
            $user = new User();
            $user->setEmail($data['email']);

            $code = $user->generateAuthCode();

            $arrUser = UserDAO::reset_password_request($user);
            
            if(is_array($arrUser) && count($arrUser) > 0){
                $cEmail = new ControllerEmail();

                $arrUser['auth'] = $code;
                $dataArray = ['user' => $arrUser ];
                $res = $cEmail->send_reset_password_request($arrUser,$dataArray);

                if($res == 'success'){
                    $json['status'] = 'success';
                    $json['msg']    = 'Pedido enviado com sucesso, verifique seu email!';
                }else{
                    $json['status'] = 'error';
                    $json['msg']    = 'Erro: não foi possivel enviar codigo de redefinição de senha para este email.';
                }

            }else{
                $json['status'] = 'error';
                $json['msg']    = 'Este email não existe, ou o usuário está inativo';
            }

            $this->render->json($json);
        }

        public function verify_reset_password_code($data = []){

            $res = UserDAO::verify_reset_password_code(new User($data));
            
            if(is_numeric($res)){
                $json['status'] = 'success';
                $json['msg']    = 'Sucesso, código valido!';
            }else{
                $json['status'] = 'error';
                $json['msg']    = 'Erro: código inválido';
                $json['msg']   .= $res;
            }

            $this->render->json($json);
        }

        public function reset_password($data = []){
            
            $user = new User($data);

            $res = UserDAO::reset_password($user);

            if($res == "success"){
                $json['status'] = 'success';
                $json['msg']    = 'Senha atualizada com sucesso!';

                $cEmail = new ControllerEmail();


                $arrUser = UserDAO::find(new User(['id' => $user->getId()]))[0];

                $dataArray = ['user' => $arrUser ];
                $cEmail->send_reset_password_success($arrUser,$dataArray);
            }else{
                $json['msg']  = 'Erro: não foi possivel atualizar a senha!';
                $json['msg'] .= "\r\n".$res;
            }

            $this->render->json($json);
        }

        public function delete_account_request($data){
            $this->validate_access(['user','adm']);       
            
            $code = $this->user->generateAuthCode();

            if( UserDAO::edit($this->user) ){

                $cEmail  = new ControllerEmail();

                $arrUser = UserDAO::find(new User(['id' => $this->user->getId()]))[0];

                $arrUser['auth'] = $code;
                $dataArray = ['user' => $arrUser];
                $res = $cEmail->send_delete_account_request($arrUser,$dataArray);

                if($res == 'success'){
                    $json['status'] = 'success';
                    $json['msg']    = 'Pedido enviado com sucesso, verifique seu email!';
                }
            }else{
                $json['status'] = 'error';
                $json['msg']    = 'Erro: não foi possivel enviar o pedido de exclusão da conta';
            }

            $this->render->json($json);
        }

        public function delete_account($code = null){
            $this->validate_access(['user','adm']);
            
            $this->user->setAuth($code);
            $data = UserDAO::remove($this->user);
            
            if($data == "success"){
                $json['status']     = 'success';
                $json['msg']        = 'Usuário deletado com sucesso!';

                $cEmail = new ControllerEmail();
                $arrUser = [
                    'user_name'  => $this->user->getName(),
                    'user_email' => $this->user->getEmail()
                ];
                
                $dataArray = ['user' => $arrUser ];
                $cEmail->send_delete_account_success($arrUser,$dataArray);
            }else{
                $json['status'] = 'error';
                if($data != ''){
                    $json['msg'] = $data;
                }else{
                    $json['msg'] = 'Erro: não foi possivel deletar o usuário.';
                }   
            }
            
            $this->render->json($json);
        }
	}