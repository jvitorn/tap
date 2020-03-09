<?php
    namespace App\Controller;
    
    use Src\Classes\ClassRender;
    use Src\Classes\Token;

    use App\Model\User;

    /**
     * Classe base para todos os Controllers
     * Contem um método index e um metodo Error padrao,
     * que podem ser sobrescritos caso necessário
     */
   
    class Controller {

        public $render;
        protected $user;
        
        use \Src\Traits\TraitCrypt;

        public function __construct(){
        	$this->render = new ClassRender();
        }
        
        public function index(){
            
        }

        public function Error(){
            $data['title']  = "Erro 404";
            $data['msg']    = "O método chamado nao existe";
            $this->render->view('Error',$data);
        }
        
        protected function validate_access($permissao){
            
            $auth = new Token();

            if( $auth->validate() ){

                if(!$this->has_permission($auth->get_token_data()->type, $permissao) ){
                    $this->no_permission_allowed();
                }else{
                    $this->user = new User($auth->get_token_data());
                }

            }else{
                $this->no_permission_allowed();
            }
        }

        private function no_permission_allowed(){
            $data['status'] = 'error';
            $data['error'] = 'Permissão negada';
            $this->render->json($data);
            die;
        }

        private function has_permission($user_type, $permissao){
            if(is_array($permissao)){
                if(\in_array($user_type, $permissao)) return true;
            }else{
                if($user_type == $permissao) return true;
            }
        }

        protected function prepare_array($data){
            if(is_array($data)){

                $count = count($data);

                foreach ($data as $key => $value) {
                    
                    if(is_array($value)){
                        foreach($value as $k => $v){
                            if(is_array($v)){
                               $c = count($v);
                                $data[$key][$k."Count"] = $c;     
                            }
                        }
                    }
                }

                $data['rowCount'] = $count;
                
                return $data;    
            }            
        }
    }