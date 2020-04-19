<?php 
    namespace Src\Classes;

    use Src\Classes\Authorization;

    use App\Model\Config;
    use App\DAO\ConfigDAO;

    class Token {

        private $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        private $signature;

        public function __construct(){
            $this->header = base64_encode(json_encode($this->header));
        }

        private function base64_url_decode($input) {
            return base64_decode(strtr($input, ' _-', '+/='));
        }

        private function base64_url_encode($input) {
            return strtr(base64_encode($input), '/=', '_-');
        }

        public function get_token_data(){
            
            $token  = (new Authorization())->getBearerToken();

            if(count(explode('.',$token)) == 3){
                
                $exp = explode('.',$token);
                $data = $this->base64_url_decode($exp[1]);
                return json_decode($data);

            }

            return array();   
        }

        public function generate($userData = []){

            $payload = $this->generatePayload($userData);
            $payload = $this->base64_url_encode(json_encode($payload));

            $signature = hash_hmac('sha256',"$this->header.$payload",KEY,true);
            $signature = $this->base64_url_encode($signature);
            $token = "$this->header.$payload.$signature";

            return $token;
        }

        public function validate(){

            $token  = (new Authorization())->getBearerToken();
            
            $exp    = explode('.',$token);

            if(count($exp) == 3){
                
                $gerado = hash_hmac('sha256',$exp[0].".".$exp[1],KEY,true);
                $gerado = $this->base64_url_encode($gerado);

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