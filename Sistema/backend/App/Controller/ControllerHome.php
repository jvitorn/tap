<?php
    namespace App\Controller;
    
    use App\Controller\Controller;
    use Src\Classes\ClassEmail;
        
    class ControllerHome extends Controller {

        public function index(){
            $data['title'] = "INDEX";
            $data['descricao'] = "bla, blabla, bla bla bla";
            
            $this->render->json($data);
            // $this->render->view('home', $data);
        }
       
        public function get(){
            $data['title'] = "GET";
            $data['descricao'] = "bla, blabla, bla bla bla";
            
            $this->render->json($data);
            // $this->render->view('home', $data);
        }

        public function add($id, $nome){
            $data['title'] = "POST";
            $data['descricao'] = "bla, blabla, bla bla bla";
            $data['nome'] = $nome;
            $data['id'] = $id;
            
            $this->render->json($data);
            // $this->render->view('home', $data);
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