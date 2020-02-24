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

        public function action_type(User $user, $data = []){

            $json['status'] = 'error';
            
            if(isset($data['id'])){
                $at = new ActionType($user, $data);
                $res = ActionTypeDAO::edit($at);

                if($res){
                    $json['status'] = 'success';
                    $json['msg']    = 'Tipo de ação atualizada com sucesso!';
                }else{
                    $json['msg']    = "Erro: não foi possivel atualizar o tipo de ação!\r\n";
                    $json['msg']   .= $res;
                }

            }else{
                
                $at = new ActionType($user, $data);
                $res = ActionTypeDAO::create($at);

                if(is_numeric($res)){
                    $json['status'] = 'success';
                    $json['msg']    = 'Tipo de ação criada com sucesso!';
                }else{
                    $json['msg']    = "Erro: não foi possivel criar o tipo de ação!\r\n";
                    $json['msg']   .= $res;
                }
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
            return ActionTypeDAO::remove(new ActionType($user, $data));
        }
	}