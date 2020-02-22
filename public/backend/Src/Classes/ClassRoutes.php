<?php
    //é o caminho até este arquivo.
    namespace Src\Classes;

    class ClassRoutes {

        static private $get     = [];
        static private $post    = [];
        static private $put     = [];
        static private $delete  = [];

        static protected $params;
        
        use \Src\Traits\TraitUrlParser;

        static public function getRequestMethod(){
            return $_SERVER['REQUEST_METHOD'];
        }

        static private function newRoute($method, $route, $controller){
            
            $c = explode("@",$controller)[0];
            $a = explode("@",$controller)[1];

            self::${$method}  += [$route =>[
                'Controller' => $c,
                'Action'     => $a,
                'url'        => $route
            ]];
        }

        static public function routesGET(){
           return self::$get;
        }

        static public function routesPOST(){
            return self::$post;
        }

        static public function routesPUT(){
           return self::$put;
        }

        static public function routesDELETE(){
            return self::$delete;
        }

        static public function GET($route , $controllerAction){
            self::newRoute('get',$route , $controllerAction);
        }

        static public function POST($route , $controllerAction){
            self::newRoute('post',$route , $controllerAction ,$_POST);
        }

        static public function PUT($route , $controllerAction){

            $paramAndValues = explode("&", $_SERVER['QUERY_STRING']);
            $params         = [];
            
            foreach($paramAndValues as $key => $pnv){
                
                $pv = explode("=",$pnv);

                if(count($pv) == 2){
                    $params[$pv[0]] = $pv[1];
                }else{
                    $params[$pv[0]] = null;
                }
            }
            // print_r($params);

            self::newRoute('put',$route , $controllerAction ,$params);
        }

        static public function DELETE($route , $controllerAction){
            self::newRoute('delete',$route , $controllerAction);
        }

        static public function get_POST_params(){
            return $_POST;
        }

        static public function get_GET_params($url){
            unset($_GET['url']);
            return $_GET;
        }

        static public function get_PUT_params($url){
            parse_str(file_get_contents('php://input'), $_PUT);
            return $_PUT;
        }

        static public function get_DELETE_params($url){
            $url = self::parseUrl();
            $id = end($url);
            return $id;
        }

        static protected function get_params($url){

            $method = 'get_'.self::getRequestMethod()."_params";
            return array(self::{$method} ($url) );
        }
       
        public function getRoute(){
       

            $method = self::getRequestMethod();

            if($method == "DELETE" && count(self::parseUrl()) > 1){
                $urlP = self::parseUrl();
                $urlLength = strlen(self::get_url());
                $paramLength = strlen( $urlP[ count($urlP) -1 ] ) + 1;

                $url = substr(self::get_url(),0, $urlLength - $paramLength);
            }else{
                $url = self::parseUrl();
                $last = end($url);
                
                if($last == null){
                    $url = substr(self::get_url(),0,-1);
                }else{
                    $url = self::get_url();
                }
            }

            if(empty($url)) $url = "";
            
            if(array_key_exists($url, self::{'routes'.$method}() )){
              
                if(file_exists(DIR_CONTROLLER.self::{'routes'.$method}()[$url]['Controller'].".php")){ 
                    return self::{'routes'.$method}()[$url];
                }else{
                    return 'Controller';
                }
            }
        }
    }