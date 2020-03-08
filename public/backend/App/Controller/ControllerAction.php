<?php 
	namespace App\Controller;

    use App\Controller\Controller;

	use App\Model\User;
    use App\Model\Category;
    use App\Model\Action;

    use App\DAO\ActionDAO;
    
	/**
	 * @method $this->render->json($dataArray);
	 */
	class ControllerAction extends Controller {

        public function __construct(){
            parent::__construct();
            $this->validate_access(['user','adm']);
        }

        public function create($data = []){

            $data['created_at'] = date('Y-m-d h:i:s');

            $json['status'] =  "error";
            
            if(isset($data['category'])){

                $res = ActionDAO::create(new Action($this->user->getId(), $data['category'], $data) );

                if($res == "success"){
                    
                    $json['status'] = 'success';
                    $json['msg']    = 'Ação criada com sucesso!';

                }else{

                    $json['msg']    = "Erro: não foi possivel criar a ação!\r\n";
                    $json['msg']   .= $res;

                }

            }else{

                $json['msg'] = "a categoria deve ser informada. ";

            }

            $this->render->json($json);
        }

        public function edit($data = []){
           
            $json['status'] = 'error';

            if(isset($data['category'])){

                unset($data['created_at']);

                $data['updated_at'] = date('Y-m-d h:i:s');
                
                $res = ActionDAO::edit( new Action($this->user->getId(),$data['category'],$data) );

                if($res == 'success'){

                    $json['status'] = 'success';
                    $json['msg']    = 'Ação atualizada com sucesso!';

                }else{

                    $json['msg']    = "Erro: não foi possivel atualizar a ação!\r\n";
                    $json['msg']   .= $res;

                }

            }else{

                $json['msg'] = "O campo categoria deve ser informado. ";

            }

            $this->render->json($json);
        }

        public function find($data = []){

            $json = ['status' => 'error','msg' => ''];

            if(isset($data['category'])){

                $action = new Action($this->user->getId(),$data['category']);
                
                if(isset($data['id']) && is_numeric($data['id'])) $action->setId($data['id']);

                if(isset($data['name']) && !empty($data['name'])) $action->setName($data['name']);

                $json = ActionDAO::find($action);

            }else{

                $json['msg'] = "A categoria deve ser informada. ";

            }

            $this->render->json($json);
        }

        public function remove($id){

            $json = ['status' => 'error','msg' => ''];

            if(!isset($id) || !is_numeric($id)){

                $json['msg'] = 'O ID do item precisa ser informado';

            }else{

                $res = ActionDAO::remove(new Action($this->user->getId(),0,['id' => $id]));

                if($res == 'success'){

                    $json['status'] = 'success';
                    $json['msg']    = 'Ação deletada com sucesso';

                }else{

                    $json['msg']    = "Erro: não foi possivel deletar a ação\r\n";
                    $json['msg']   .= $res;

                }    
            }

            $this->render->json($json);
        }
	}