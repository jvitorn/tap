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

        public function create(User $user, $data = []){

            $json['status'] = 'error';
            $json['msg']    = '';

            if(!isset($data['name'])){
                $json['msg'] .= "O campo NAME deve ser informado!\r\n";
            }else{
                $data['created_at'] = date('Y-m-d h:i:s');
                
                $cat = new Category($user, ['id' => $data['category']]);

                $res = ActionDAO::create(new Action($user, $cat, $data) );

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

        public function edit(User $user, $data = []){
           
            $json = ['status' => 'error','msg' => ''];

            unset($data['created_at']);
            $data['updated_at'] = date('Y-m-d h:i:s');

            $cat = new Category($user, ['id' => $data['category']]);
            
            $res = ActionDAO::edit(new Action($user, $cat, $data));

            if($res == 'success'){
                $json['status'] = 'success';
                $json['msg']    = 'Ação atualizada com sucesso!';
            }else{
                $json['msg']    = "Erro: não foi possivel atualizar a ação!\r\n";
                $json['msg']   .= $res;
            }

            return $json;
        }

        public function list(User $user, $data){
            $json = ['status' => 'error','msg' => ''];

            if(isset($data['category'])){
                $cat = new Category($user, ['id' => $data['category']]);
            }else{
                $cat = $cat = new Category($user);
            }

            $json = ActionDAO::find(new Action($user, $cat, $data));

            return $json;
        }

        public function remove(User $user, $id){

            $json = ['status' => 'error','msg' => ''];
            $paramStatusOk = true;

            if(!isset($id) || !is_numeric($id)){
                $json['msg'] = 'O ID do item precisa ser informado';
                $paramStatusOk = false;
            }

            if($paramStatusOk){
                $cat = $cat = new Category($user);
                $res = ActionDAO::remove(new Action($user,$cat,['id' => $id]));

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