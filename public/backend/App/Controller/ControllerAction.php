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

        public function create($data = []){

            $json['status'] = 'error';
            $json['msg']    = '';

            if(!isset($data['name'])){
                $json['msg'] .= "O campo NAME deve ser informado!\r\n";
            }else{
                $data['created_at'] = date('Y-m-d h:i:s');
                $res = ActionDAO::create( new Action($data) );

                if(is_numeric($res)){
                    $json['status'] = 'success';
                    $json['msg']    = 'Ação criada com sucesso!';
                }else{
                    $json['msg']    = "Erro: não foi possivel criar a ação!\r\n";
                    $json['msg']   .= $res;
                }
            }

            return $json;
        }

        public function edit($data = []){
           
            $json = ['status' => 'error','msg' => ''];

            unset($data['created_at']);
            $data['updated_at'] = date('Y-m-d h:i:s');
            $res = ActionDAO::edit(new Action($data));

            if($res == 'success'){
                $json['status'] = 'success';
                $json['msg']    = 'Ação atualizada com sucesso!';
            }else{
                $json['msg']    = "Erro: não foi possivel atualizar a ação!\r\n";
                $json['msg']   .= $res;
            }

            return $json;
        }

        public function list($data){
            $json = ['status' => 'error','msg' => ''];
            $json = ActionDAO::find(new Action($data));
            return $json;
        }

        public function remove($data){
            $json = ['status' => 'error','msg' => ''];
            $paramStatusOk = true;

            if(!isset($data['id']) || !is_numeric($data['id'])){
                $json['msg'] = 'O ID do item precisa ser informado';
                $paramStatusOk = false;
            }

            if($paramStatusOk){
                $res = ActionDAO::remove(new Action($data));

                if($res == 'success'){
                    $json['status'] = 'success';
                    $json['msg']    = 'Categoria deletada com sucesso';
                }else{
                    $json['status'] = 'success';
                    $json['msg']    = "Erro: não foi possivel deletar a categoria\r\n";
                    $json['msg']   .= $res;
                }    
            }

            return $json;
        }
	}