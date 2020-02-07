<?php
    namespace App\Controller;
    
    use App\Controller\Controller;
    use Src\Classes\ClassEmail;
    use App\Controller\ControllerUser;
        
    class ControllerPainel extends Controller{

    	private function callController($controller, $action, $param = null){
            
            $post = isset($_POST) ? $_POST : null;

            $params = ['post' => $post, 'url' => $param];

    		$controller = "App\\Controller\\". $controller;
    		$obj = new $controller;
    		if(method_exists($obj,$action)){
        		call_user_func_array([$obj, $action], ['method' => $params]);
        	}else{
        		header('Location: '.PAINEL);
        	}
    	}
        
        public function index(){
            $data['title'] = "Painel";
            $this->render->view('painel/home', $data);
        }

        public function users($action, $param = null){
        	$this->callController('ControllerUser', $action, $param);
        }

        public function login(){
            $data['title'] = "Login";
            $this->render->view('painel/login', $data);
        }
    }