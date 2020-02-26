<?php 
	namespace App\Controller;

    use App\Controller\Controller;

	use App\Model\User;
    use App\Model\ActionType;
	
    use App\DAO\ActionTypeDAO;
    
	/**
	 * @method $this->render->json($dataArray);
	 */
	class ControllerActionType extends Controller {

        public function new_action_type(User $user, $data = []){

            $json['status'] = 'error';

            $at = new ActionType($user, $data);
            $res = ActionTypeDAO::create($at);

            if(is_numeric($res)){
                $json['status'] = 'success';
                $json['msg']    = 'Tipo de ação criada com sucesso!';
            }else{
                $json['msg']    = "Erro: não foi possivel criar o tipo de ação!\r\n";
                $json['msg']   .= $res;
            }
            
			$this->render->json($json);
        }

         public function edit_action_type(User $user, $data = []){
            $json['status'] = 'error';

            if(isset($data['id'])){
                $at = new ActionType($user, $data);
                $res = ActionTypeDAO::edit($at);

                if($res == 'success'){
                    $json['status'] = 'success';
                    $json['msg']    = 'Tipo de ação atualizada com sucesso!';
                }else{
                    $json['msg']    = "Erro: não foi possivel atualizar o tipo de ação!\r\n";
                    $json['msg']   .= $res;
                }

            }else{
                 $json['msg']    = "Erro:o campo ID deve ser informado!\r\n";
            }

            $this->render->json($json);
         }

        
        public function list(User $user, $data){
            $at = new ActionType($user, $data);
            $json = ActionTypeDAO::find($at);
            $this->render->json($json);
        }

        public function remove($user, $data){
            if(!isset($data['id'])) return 'O ID do item precisa ser informado';
            $res = ActionTypeDAO::remove(new ActionType($user, $data));

            if($res == 'success'){
                $json['status'] = 'success';
                $json['msg']    = 'Tipo de ação deletada com sucesso';
            }else{
                $json['status'] = 'success';
                $json['msg']    = "Erro: não foi possivel deletar o item\r\n";
                $json['msg']   .= $res;
            }

            $this->render->json($json);
        }
	}