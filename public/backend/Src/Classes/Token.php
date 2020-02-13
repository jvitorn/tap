<?php 
    namespace Src\Classes;

    class Token {

        private $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        private $signature;

        public function __construct(){
            $this->header = base64_encode(json_encode($this->header));
        }

        public function getTokenData($token){
            $exp = explode('.',$token);
            $data = base64_decode($exp[1]);
            return json_decode($data);
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
                    return true;
                }
            }
        }

        private function generatePayload($data){
            
            $data['jti'] = $data['auth'];
            $data['iat'] = date('U');
            $data['iss'] = 'tap.com.br';

            unset($data['auth']);

            return $data;
        }
    }