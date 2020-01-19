<?php
    //é o caminho até este arquivo.
    namespace Src\Classes;

    class ClassRoutes {

        static private $get     = [];
        static private $post    = [];
        static private $put     = [];
        static private $delete  = [];
        
        use \Src\Traits\TraitUrlParser;

        static public function getRequestMethod(){
            return $_SERVER['REQUEST_METHOD'];
        }

        static private function newRoute($method, $route, $controller, $params){
            
            $c = explode("@",$controller)[0];
            $a = explode("@",$controller)[1];

            self::${$method}  += [$route =>[
                'Controller' => $c,
                'Action'     => $a,
                'Params'     => $params
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

        static public function GET($route , $controllerAction,$params = []){
            self::newRoute('get',$route , $controllerAction, $params);
        }

        static public function POST($route , $controllerAction, $params = []){
            self::newRoute('post',$route , $controllerAction ,$params);
        }

        static public function PUT($route , $controllerAction, $params = []){
            self::newRoute('put',$route , $controllerAction ,$params);
        }

        static public function DELETE($route , $controllerAction, $params = []){
            self::newRoute('delete',$route , $controllerAction ,$params);
        }
        
        public function getRoute(){
       
            $url = self::parseUrl();
            $i = $url[0];

            $method = 'routes'.self::getRequestMethod();
                       
            if( array_key_exists($i, self::{$method}() )){
              
                if(file_exists(DIR_CONTROLLER.self::{$method}()[$i]['Controller'].".php")){
                    return self::{$method}()[$i];
                }else{
                    return 'Controller';
                }

            }
        }
    }