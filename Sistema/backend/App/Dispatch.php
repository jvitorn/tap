<?php
    namespace App;
    
    use Src\Classes\ClassRoutes;
    use App\Routes;
    
    class Dispatch extends ClassRoutes{

        private $obj;

        private $method;

        private $param = [];
        private $startParams = 2;
        private $actionParams;

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
                $this->actionParams = $route['Params'];
            }else{
                $routeController = "Controller";
                $action = "Error";
            }
            
            $controller = "App\\Controller\\". $routeController;
           
            $this->obj = new $controller;
            $this->addMethod($action);
        }
        
        private function addMethod($action = null){

            if(!empty($action)){
                $this->setMethod($action);
                $this->startParams = 1;
            }else{

                if(isset($this->parseUrl()[1]) && !empty($this->parseUrl()[1])){
                
                    $action = str_replace('-','_',$this->parseUrl()[1]);
                    if( method_exists($this->obj,$action) ) $this->setMethod($action);

                }else{
                    if( method_exists($this->obj,'index') ) 
                        $this->setMethod('index');
                    else
                        $this->setMethod('Error');
                }
            }

            self::addParam();
            call_user_func_array([$this->obj,$this->getMethod()],$this->getParam());
        }
        
        private function addParam(){
            
            $arrayCount = count($this->parseUrl());
            
            if($arrayCount > $this->startParams){

                $countIndice = 0;
                
                foreach($this->parseUrl() as $key => $value){    

                    if($key >= $this->startParams && isset($this->actionParams[$countIndice]) ){

                        $nmIndice = $this->actionParams[$countIndice];

                        $this->setParam($this->param += [$nmIndice => $value]);

                        $countIndice++;
                    }
                }
            }
        }
    }