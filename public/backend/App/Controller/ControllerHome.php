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
            $this->render->view('endpoints',$data);
        }
    }