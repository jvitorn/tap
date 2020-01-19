<?php
    namespace App\Controller;

    use Src\Classes\ClassRender;

    /**
     * Classe base para todos os Controllers
     * Contem um método index e um metodo Error padrao,
     * que podem ser sobrescritos caso necessário
     */
   
    class Controller {

        protected $render;
        
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
    }