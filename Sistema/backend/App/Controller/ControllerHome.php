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

            $user = new User();
            $like = 'email LIKE "jdc%"';
            $cols = 'id, name, email';
            $users = UserDAO::find($user,$like,$cols);

            $res = $this->transform_obj_in_array($users, 'users');

            $this->render->json($res);
        }
    }