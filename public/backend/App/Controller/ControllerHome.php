<?php
    namespace App\Controller;
    
    use App\Controller\Controller;
    use Src\Classes\ClassEmail;
        
    use App\Model\User;
    use App\Model\Financial;
    
    use App\DAO\UserDAO;
    use App\DAO\FinancialDAO;

    /**
     * @method $this->render->json($data[])
     */
    class ControllerHome extends Controller {

        public function index(){
            $data['title'] = "lista de endpoints";
            $this->view('endpoints',$data);
        }

        public function tests(){
            $data['title'] = "testes";
            $this->view('tests/test',$data);
        }
    }