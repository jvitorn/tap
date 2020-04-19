<?php
    namespace App\Controller;

    use App\Controller\Controller;
    use App\Model\User;
    use App\DAO\UserDAO;

    use Src\Classes\Token;

    /**
     * This class is responsible for business rules involving access validation.
     */
    
    class ControllerAuth extends Controller {
        
        public function login($data){

            $json['status'] = 'error';

            if(isset($data['email']) && isset($data['password'])){
                
                $user = new User($data);
                $user = UserDAO::Login($user);

                if(is_array($user)){

                    /*adiciona a hash unica ao array. */
                    $user['jti'] = md5($user['id'].date('hisYdm'));
                    $token = (new Token())->generate($user);
                    $data = ['token' => $token];
                    $json = $data;

                }else{

                    $json['msg'] = "Email ou senha inválidos";                   
                    
                }

            }else{

                $json['msg'] = 'Os campos EMAIL e PASSWORD devem ser informados. ';

            }

            $this->json($json);
        }

        public function validate(){

            $res = (new Token())->validate();

            if($res){

                $json['status'] = 'success';
                $json['msg']    = 'token validado. ';

            }else{

                $json['status'] = 'error';
                $json['msg']    = 'token invalido. ';

            }

            $this->json($json);
        }
    }