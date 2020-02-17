<?php 
    namespace Src\Classes;

    class Token {

        private $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        private $signature;

        public function __construct(){
            $this->header = base64_encode(json_encode($this->header));
        }

        public function get_token_data($token){
            if(count(explode('.',$token)) == 3){
                $exp = explode('.',$token);
                $data = base64_decode($exp[1]);
                return json_decode($data);
            }
            return array();   
        }

        public function generate($userData = []){

            $payload = $this->generatePayload($userData);

            $payload = json_encode($payload);
            $payload = base64_encode($payload);

            $signature = hash_hmac('sha256',"$this->header.$payload",KEY,true);
            $signature = base64_encode($signature);
            $token = "$this->header.$payload.$signature";
            return $token;
        }

        public function validate($token){
            
            $exp = explode('.',$token);
            
            if(count($exp) == 3){
                
                $gerado = hash_hmac('sha256',$exp[0].".".$exp[1],KEY,true);
                $gerado = base64_encode($gerado);
                
                if($exp[2] == $gerado){
                    $data = $this->get_token_data($token);
                    if($data->exp > date("U")) return true;
                }
            }
        }

        private function generatePayload($data){
            
            $data['iat'] = date('U');
            $data['exp'] = (date('U') + $this->expiration_time());
            $data['iss'] = 'tap.com.br';

            unset($data['auth']);

            return $data;
        }

        private function expiration_time(){
            $sec  = 60;
            $min  = 60;
            $hour = 24;
            $days = 30;

            return ($sec * $min * $hour * $days);
        }
    }