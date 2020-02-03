<?php
    namespace Src\Traits;
    
    trait TraitUrlParser {
        
        static public function parseUrl(){
            return explode("/",rtrim($_GET["url"]),FILTER_SANITIZE_URL);
        }

        static public function get_url(){
        	return rtrim($_GET["url"]);
        }
    }