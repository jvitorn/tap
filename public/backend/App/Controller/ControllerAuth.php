<?php
    namespace App\Controller;

    use App\Controller\Controller;
    use App\Model\User;
    use App\DAO\UserDAO;

    use Src\Classes\Token;

    /**
     * This class is responsible for business rules involving access validation. 
     */
    
    class ControllerAuth extends Controller{

        public function getAuthorizationHeader(){
            $headers = null;
            
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }else if(isset($_SERVER['HTTP_AUTHORIZATION'])){
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            }else if(function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                // print_r($requestHeaders);
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }

        private function getBearerToken() {
            $headers = $this->getAuthorizationHeader();

            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            return null;
        }
        
        public function login($data){

            if(isset($data['email']) && isset($data['password'])){
                
                $user = UserDAO::Login(new User($data));

                if(is_array($user)){
                    $token = (new Token())->generate($user);
                    $data = ['token' => $token];
                    $this->render->json($data);
                }else{
                    $data = [
                        'status' => 'error',
                        'msg' =>"Email ou senha inválidos"
                    ];
                    
                    $this->render->json($data);
                }
            }
        }

        public function logout(){
            
            $this->validate_access(['user','adm']);
            
            $data['status'] = 'error';
            $data['msg']    = 'Não foi possivel efetuar o logout no servidor';
            
            if(UserDAO::logout($this->user)){
                $data['status'] = 'success';
                $data['msg']    = 'deslogado com sucesso';
            }

            $this->render->json($data);
        }

        public function validate(){
            $token = $this->getBearerToken();
            
            if(UserDAO::is_logged(new User( (new Token())->get_token_data($token)))){
                return (new Token())->validate($token);
            }
        }

        public function get_token_data(){
            $token = $this->getBearerToken();
            return (new Token())->get_token_data($token);
        }
    }