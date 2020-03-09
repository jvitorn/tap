<?php
    namespace App\Controller;

    use App\Controller\Controller;
    use App\Model\User;
    use App\DAO\UserDAO;

    use Src\Classes\Authorization;
    use Src\Classes\Token;

    /**
     * This class is responsible for business rules involving access validation.
     */
    
    class ControllerAuth extends Controller{
        
        public function login($data){

            if(isset($data['email']) && isset($data['password'])){
                
                $user = UserDAO::Login(new User($data));

                if(is_array($user)){

                    /*adiciona a hash unica ao array. */
                    $user['jti'] = md5($user['id'].date('hisYdm'));

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

        public function validate(){
            $token = (new Authorization())->getBearerToken();
            $data = (new Token())->get_token_data($token);
            
            return (new Token())->validate($token);
        }

        public function get_token_data(){
            $token = (new Authorization())->getBearerToken();
            return (new Token())->get_token_data($token);
        }
    }