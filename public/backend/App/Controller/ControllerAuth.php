<?php
    namespace App\Controller;

    use App\Controller\Controller;
    use App\Model\User;

    /**
     * This class is responsible for business rules involving access validation. 
     */
    
    class ControllerAuth extends Controller{
        
        
        public function login(){
            echo $this->token();
        }

        public function validate_token($data){
            $token   = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.";
            $token .= "eyJpc3MiOiJ0YXAiLCJpZCI6IjEiLCJuYW1lIj";
            $token .= "oiamRjIiwiZW1haWwiOiJqZGNAZXhlbXBsby5jb";
            $token .= "20iLCJoZWlnaHQiOiIxLjY1Iiwid2VpZ2h0IjoiNTUuNTAwIiwiMCI6IiJ9.";
            $token .= "zELyWRuz8phIkRZQg7+eim9RQvxUkCD7NS27uhWjLEM=";
            
            if($token == $this->token()){
                echo "Ok";
            }else{
                echo "deu ruim";
            }
        }

        public function logout(User $user){
           
        }       

        public function token(){
            $key = "1234567890";

            $header = ['typ' => 'JWT', 'alg' => 'HS256'];
            $header = json_encode($header);
            $header = base64_encode($header);

            $payload = [
                'iss'       => 'tap',
                'id'        => '1',
                'name'      => 'jdc',
                'email'     => 'jdc@exemplo.com',
                'height'    => '1.65',
                'weight'    => '55.500',
                ''
            ];
            $payload = json_encode($payload);
            $payload = base64_encode($payload);

            $signature = hash_hmac('sha256',"$header.$payload",$key,true);
            $signature = base64_encode($signature);
            $token = "$header.$payload.$signature";
            return $token;
        }
    }