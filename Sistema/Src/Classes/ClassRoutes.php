<?php
    //é o caminho até este arquivo.
    namespace Src\Classes;

    class ClassRoutes {
        
        static private $routes = [];
        
        use \Src\Traits\TraitUrlParser;

        static public function route($route , $controller, $action = null){

            self::$routes += [$route =>[
                'Controller' => $controller,
                'Action'     => $action
            ]];

        }
        
        public function getRoute(){
       
            $url = self::parseUrl();
            $i = $url[0];
                       
            if(array_key_exists($i, self::$routes)){
              
                if(file_exists(DIR_CONTROLLER.self::$routes[$i]['Controller'].".php")){
                    return self::$routes[$i];
                }else{
                    return 'Controller';
                }

            }else{
                return "ControllerError";
            }
        }
    }