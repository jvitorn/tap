<?php 
	namespace App\Classes;

	class Authorization {

		public function getAuthorizationHeader(){
            
            $headers = null;
            
            if(isset($_SERVER['Authorization'])){

                $headers = trim($_SERVER["Authorization"]);

            }else if(isset($_SERVER['HTTP_AUTHORIZATION'])){
                
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);

            }else if(function_exists('apache_request_headers')){
                
                $requestHeaders = apache_request_headers();
                $arr_map = array_map('ucwords', array_keys($requestHeaders)),array_values($requestHeaders);
                $requestHeaders = array_combine($arr_map);

                if (isset($requestHeaders['Authorization'])){
                    $headers = trim($requestHeaders['Authorization']);
                }
            }

            return $headers;
        }

    	private function getBearerToken(){

	        $headers = $this->getAuthorizationHeader();

	        if(!empty($headers)){
	            
	            if(preg_match('/Bearer\s(\S+)/', $headers, $matches)) return $matches[1];

	        }

	        return null;
        }
	}