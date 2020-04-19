<?php 
	namespace App\Controller;

    use App\Controller\Controller;

	use App\Model\User;
    use App\Model\Email;
	
    use App\DAO\UserDAO;
    use App\DAO\EmailDAO;
    
    use Src\Classes\ClassEmail;

	/**
	 * @method $this->json($dataArray);
	 */
	class ControllerUser extends Controller {

        public function __construct(){
            parent::__construct();
        }

        public function add($data = []){

            $data['type'] = 'user';
            
            $user = new User($data);
            $auth = $user->generateAuthCode();

            $id = UserDAO::create($user);

            if(is_numeric($id)){
                
                $json['status']  = 'success';
                $json['msg']     = 'Usuário cadastrado com sucesso!';
                
                $user->setId($id);

                $arrUser = UserDAO::find($user)[0];

                $arrUser['auth'] = $auth;

                $dataArray = [ 'user' => $arrUser ];

                $resEmail = $this->send_email($dataArray,'confirm_account_request');
                               
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
                    $json['msg'] = $id;
                }else{
                    $json['msg'] = 'Erro: não foi possivel cadastrar o usuário.';
                }
            }
			$this->json($json);
		}

        public function edit($data = []){
            $this->validate_access(['user','adm']);
            
            $data['type'] = 'user';
            $user = new User($data);
            $user->setId($this->user()->getId());

			if(UserDAO::edit($user)){
				$json['status'] 	= 'success';
				$json['msg']		= 'Usuário atualizado com sucesso!';
			}else{
				$json['status'] = 'error';
				$json['msg'] = 'Erro: não foi possivel atualizar o usuário.';
			}

            $this->json($json);
		}

        public function confirm_account($data = []){

            if(isset($data['auth']) && strlen($data['auth']) == 6){

                $res = UserDAO::active(new User($data));
                
                if($res == 'success'){
                    $json['status'] = 'success';
                    $json['msg']    = 'Usuário ativado com sucesso';

                    $arrUser    = UserDAO::find(new User(['email'=> $data['email']]) )[0];
                    $dataArray  = [ 'user' => $arrUser ];
                    $resEmail   = $this->send_email($dataArray,'confirm_account_success');

                }else{
                    $json['status'] = 'error';
                    $json['msg']    = $res;
                }    
            }else{
                $json['status'] = 'error';
                $json['msg ']   = 'codigo inválido';
            }

            $this->json($json);
        }

        public function reset_password_request($data = []){
            
            $user = new User();
            $user->setEmail($data['email']);

            $code = $user->generateAuthCode();

            $arrUser = UserDAO::reset_password_request($user);
            
            if(is_array($arrUser) && count($arrUser) > 0){
   
                $arrUser['auth'] = $code;
                $dataArray       = ['user' => $arrUser ];
                
                $resEmail        = $this->send_email($dataArray,'reset_password_request');

                if($resEmail == 'success'){

                    $json['status'] = 'success';
                    $json['msg']    = 'Pedido enviado com sucesso, verifique seu email!';

                }else{

                    $json['status'] = 'error';
                    $json['msg']    = 'Erro: não foi possivel enviar codigo de redefinição de senha.';

                }

            }else{

                $json['status'] = 'error';
                $json['msg']    = 'Este email não existe, ou o usuário está inativo';

            }

            $this->json($json);
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

            $this->json($json);
        }

        public function reset_password($data = []){
            
            $user = new User($data);

            $res = UserDAO::reset_password($user);

            if($res == "success"){

                $json['status'] = 'success';
                $json['msg']    = 'Senha atualizada com sucesso!';

                $arrUser = UserDAO::find(new User(['id' => $user->getId()]))[0];

                $dataArray = ['user' => $arrUser ];

                $this->send_email($dataArray,'reset_password_success');

            }else{

                $json['msg']  = 'Erro: não foi possivel atualizar a senha!';
                $json['msg'] .= "\r\n".$res;

            }

            $this->json($json);
        }

        public function delete_account_request($data){
            $this->validate_access(['user','adm']);       
            
            $code = $this->user()->generateAuthCode();

            if( UserDAO::edit($this->user()) ){

                $arrUser = UserDAO::find(new User(['id' => $this->user()->getId()]))[0];

                $arrUser['auth'] = $code;
                $dataArray       = ['user' => $arrUser];

                $resEmail = $this->send_email($dataArray,'delete_account_request');

                if($resEmail == 'success'){
                    
                    $json['status'] = 'success';
                    $json['msg']    = 'Pedido enviado com sucesso, verifique seu email!';

                }

            }else{

                $json['status'] = 'error';
                $json['msg']    = 'Erro: não foi possivel enviar o pedido de exclusão da conta';

            }

            $this->json($json);
        }

        public function delete_account($code = null){
            $this->validate_access(['user','adm']);
            
            $this->user()->setAuth($code);
            $res = UserDAO::remove($this->user());
            
            if($res == "success"){
                
                $json['status']     = 'success';
                $json['msg']        = 'Usuário deletado com sucesso!';

                $arrUser = [
                    'user_name'  => $this->user()->getName(),
                    'user_email' => $this->user()->getEmail()
                ];
                
                $dataArray = ['user' => $arrUser ];
                $resEmail = $this->send_email($dataArray,'delete_account_success');

            }else{

                $json['status'] = 'error';

                if($res != ''){

                    $json['msg'] = $res;

                }else{

                    $json['msg'] = 'Erro: não foi possivel deletar o usuário.';

                }

            }
            
            $this->json($json);
        }

        protected function send_email($dataArray, $email_type){
            $tEmail =  EmailDAO::find(new Email(['type' => $email_type]))[0];
            $cEmail = new ClassEmail($dataArray,$tEmail['email_title'],$tEmail['email_content']);
            return $cEmail->send();
        }
	}