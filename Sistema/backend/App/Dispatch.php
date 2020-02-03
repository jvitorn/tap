<?php
    namespace App;
    
    use Src\Classes\ClassRoutes;
    use App\Routes;
    
    class Dispatch extends ClassRoutes{

        private $obj;
        private $method;
        private $param = [];

        protected function getMethod(){ return $this->method; }
        protected function getParam(){ return $this->param; }

        protected function setMethod($method){
            $method = str_replace("-","_",$method);
            $this->method = $method;
        }
        
        protected function setParam($param){ $this->param = $param; }
        
        public function __construct(){
            self::AddController();
        }
        
        public function AddController(){
            
            $route = $this->getRoute();

            if( is_array($route) ){
                $routeController = $route['Controller'];
                $action = $route['Action'];
                self::addParams($route['url']);
            }else{
                $routeController = "Controller";
                $action = "Error";
            }
            
            $controller = "App\\Controller\\". $routeController;
           
            $this->obj = new $controller;
            $this->addMethod($action);
        }
        
        private function addMethod($action = 'index'){
            if( method_exists($this->obj,$action) ) $this->setMethod($action);
            call_user_func_array([$this->obj,$this->getMethod()],$this->getParam());
        }
        
        private function addParams($url){
            $this->setParam(parent::get_params($url));
        }
    }