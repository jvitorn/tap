<?php
    namespace App\Controller;
    
    use App\Controller\Controller;
    use Src\Classes\ClassEmail;
        
    class ControllerHome extends Controller {
       
        public function index(){
            $data['title'] = "INICIO";
            $this->render->view('home', $data);
        }

        public function method1($p1 = null,$p2 = null){
            echo "method 1 parametro 1: $p1, parametro 2: $p2";
        }

        public function method2(){
            echo "method 2";
        }

        public function method3(){
            echo "method 3";
        }

        public function teste(){
            $arr['nome'] = $_POST['nome'].'-retornado';
            echo json_encode($arr);
        }

    }