<?php 
	namespace App\Controller;

    use App\Controller\Controller;
    use App\Controller\ControllerEmail;
    use App\Controller\ControllerCategory;
    use App\Controller\ControllerAction;

	use App\Model\User;
    use App\Model\Category;
    use App\Model\Action;
	
    use App\DAO\UserDAO;
    
	/**
	 * @method $this->render->json($dataArray);
	 */
	class ControllerUser extends Controller {

        public function __construct(){
            parent::__construct();
        }

        public function add($data = []){

            $user = new User();

            $auth = $this->generateAuthCode();
            $data['auth'] = $auth;
            $data = $user->fill_user_required_fields($data);

            if($data == ''){

                $data = UserDAO::create($user);

                if(is_numeric($data)){
                    
                    $json['status']  = 'success';
                    $json['msg']     = 'Usuário cadastrado com sucesso!';

                    $arrUser = $user->getAttributesAsArray();

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
            }else{
                $json['status'] = 'error';
                $json['msg'] = $data;
            }
			$this->render->json($json);
		}

        public function edit($data = []){
            $this->validate_access(['user','adm']);
            
            $this->user->editPublicColumns($data);
			if(UserDAO::edit($this->user)){
				$json['status'] 	= 'success';
				$json['msg']		= 'Usuário atualizado com sucesso!';
			}else{
				$json['status'] = 'error';
				$json['msg'] = 'Erro: não foi possivel atualizar o usuário.';
			}

            $this->render->json($json);
		}

        public function confirm_account($data = []){

            if(isset($data['code']) && isset($data['email'])){
                if(strlen($data['code']) == 6){

                    $res = UserDAO::active(
                        new User(['email' => $data['email'], 'auth' => $data['code']])
                    );
                    
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

            }else{
                $json['status']     = 'error';
                $json['msg']        = 'os campos CODE e ID devem ser informados';
            }

            $this->render->json($json);
        }

        public function reset_password_request($data = []){
            
            $code = $this->generateAuthCode();
            $arrUser = UserDAO::reset_password_request( new User(['email' => $data['email']]), $code);
            
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
            
            $json['status'] = 'error';
            $json['msg']    = '';
            $paramsStatusOK = true;
            
            if(!isset($data['code'])){
                $paramsStatusOK  = false;    
                $json['msg']    .= "O campo CODIGO deve ser informado!\r\n";
            }

            if(!isset($data['email'])){
                $paramsStatusOK  = false;    
                $json['msg']    .= "O campo EMAIL deve ser informado!\r\n";
            }

            if($paramsStatusOK){
                $id = UserDAO::verify_reset_password_code(
                    new User(['auth' =>$data['code'],'email' => $data['email'],'active'=> '1'])
                );
                
                if(is_numeric($id)){
                    $json['status'] = 'success';
                }else{
                    $json['status'] = 'error';
                    $json['msg']    = 'Erro: código inválido';
                }
            }

            $this->render->json($json);
        }

        public function reset_password($data = []){
            $paramsStatusOK = true;
            $json['status'] =  'error';
            $json['msg']    = '';

            if(!isset($data['password'])){
                $json['msg'] .= "O campo PASSWORD deve ser informado\r\n";
                $paramsStatusOK = false;
            }

            if(!isset($data['email'])){
                $json['msg'] .= "O campo EMAIL deve ser informado\r\n";
                $paramsStatusOK = false;
            }

            if(!isset($data['code'])){
                $json['msg'] .= "O campo CODE deve ser informado\r\n";
                $paramsStatusOK = false;
            }

            if($paramsStatusOK){
                $user = new User(['email' => $data['email'],'auth' => $data['code']]);

                if(UserDAO::reset_password($user, $data['password'])){
                    $json['status'] = 'success';
                    $json['msg']    = 'Senha atualizada com sucesso!';

                    $cEmail = new ControllerEmail();
                    $arrUser = UserDAO::find( new User(['email' =>$data['email']]) )[0];
                    $dataArray = ['user' => $arrUser ];
                    $cEmail->send_reset_password_success($arrUser,$dataArray);
                }else{
                    $json['msg']    = 'Erro: não foi possivel atualizar a senha!';
                }
            }

            $this->render->json($json);
        }

        public function delete_account_request($data){
            $this->validate_access(['user','adm']);
            
            $code = $this->generateAuthCode();
            $this->user->setAuth($code);

            if( UserDAO::edit($this->user) ){
                $cEmail = new ControllerEmail();
                $arrUser = $this->user->getAttributesAsArray();
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
            $paramsStatusOK = true;

            if(!isset($code)){
                $json['status'] = 'error';
                $json['msg']    = 'O código de verificação deve ser informado!';
                $paramsStatusOK = false;
            }

            if($paramsStatusOK){

                $this->user->setAuth($code);
                $data = UserDAO::remove($this->user);
                
                if(is_bool($data)){
                    $json['status']     = 'success';
                    $json['msg']        = 'Usuário deletado com sucesso!';

                    $cEmail = new ControllerEmail();
                    $arrUser = $this->user->getAttributesAsArray();
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
            }
            $this->render->json($json);
        }

        private function generateAuthCode(){
           return substr(md5(date('idmyhs')),0,6);
        }

        /**
         * METODOS DE MANIPULAÇÃO DE CATEGORIAS
         */
        public function new_category($data = []){
            $this->validate_access(['user','adm']);
            
            $data['is_public'] = 0;
            $data['active'] = 1;
            $cAT    = new ControllerCategory();
            $res = $cAT->create($this->user, $data);

            $this->render->json($res);
        }

        public function edit_category($data = []){
            $this->validate_access(['user','adm']);
            
            $data['is_public']  = 0;
            $cAT    = new ControllerCategory();
            $res = $cAT->edit($this->user, $data);

            $this->render->json($res);
        }

        public function list_categories($data){
            $this->validate_access(['user','adm']);
            
            $cAT    = new ControllerCategory();

            // pegando as categorias do usuário
            $data['is_public']  = 0;
            $json   = $cAT->list($this->user, $data);

            // pegando as categorias publicas
            $data['is_public']  = 1;
            $data['active']     = 1;
            $res  = $cAT->list($this->user, $data);

            if(is_array($res)) $json = array_merge($res, $res);

            $this->render->json($json);
        }

        public function my_categories(){
            $this->validate_access(['user','adm']);
            
            $data['is_public'] = 0;
            $cAT    = new ControllerCategory();
            $json = $cAT->list($this->user, $data);
            $this->render->json($json);
        }

        public function remove_category($id){
            $this->validate_access(['user','adm']);
            
            $data['is_public'] = 0;
            $cAT    = new ControllerCategory();
            $res = $cAT->remove($this->user,['id' => $id]);
            $this->render->json($res);
        }

        /**
         * METODOS DE MANIPULAÇÃO DE AÇÕES
         */
        public function new_action($data = []){
            $this->validate_access(['user','adm']);

            $data['user'] = $this->user;
            $data['category'] = new Category($this->user, ['id' => $data['category']]);
            $json   = (new ControllerAction())->create($data);

            $this->render->json($json);
        }

        public function edit_action($data = []){
            $this->validate_access(['user','adm']);
            $json = ['status' => 'error', 'msg' =>''];
            
            $data['user'] = $this->user;
            $data['category'] = new Category($this->user, ['id' => $data['category']]);
            $json   = (new ControllerAction())->edit($data);
        

            $this->render->json($json);
        }

        public function list_actions($data = []){
            $this->validate_access(['user','adm']);
            $json   = ['status' => 'error', 'msg' =>''];

            $data['user'] = $this->user;
            if(isset($data['category'])){
                $data['category'] = new Category($this->user, ['id' => $data['category']]);    
            }
            
            $json   = (new ControllerAction())->list($data);
            $this->render->json($json);
        }

        public function remove_action($id = null){
            $this->validate_access(['user','adm']);
            $data['user'] = $this->user;
            $data['id'] = $id;
            $json   = (new ControllerAction())->remove($data);
            $this->render->json($json);
        }
	}