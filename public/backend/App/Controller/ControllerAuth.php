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
        
        public function login($data){

            if(isset($data['email']) && isset($data['password'])){
                $data = ['email' => $data['email'], 'password' => $data['password']];
                $user = UserDAO::Login(new User($data));

                if(\is_object($user)){
                    $user = array_filter($user->getAttributesAsArray());
                    $token = (new Token())->generate($user);
                    $data = ['token' => $token];
                    $this->render->json($data);
                    
                }else{
                    $data['error'] = "Login inválido";
                    $this->render->json($data);
                }
            }
        }

        public function validate($data){
            $token = $data['token'];
            $tk = new Token();
            $tk->validate($token);
        }

        public function logout(User $user){
           
        }
    }