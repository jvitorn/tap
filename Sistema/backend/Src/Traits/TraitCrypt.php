<?php
    namespace Src\Traits;
    
    trait TraitCrypt {
        
        public function encrypt($string){
            $result = '';
            for($i = 0; $i<strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr(KEY, ($i % strlen(KEY))-1, 1);
                $char = chr(ord($char)+ord($keychar));
                $result.=$char;
            }
            return base64_encode($result);
        }
        
        public function decrypt($string){
            $result = '';
            $string = base64_decode($string);
        
          for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr(KEY, ($i % strlen(KEY))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
          }
          return $result;
        }
    }