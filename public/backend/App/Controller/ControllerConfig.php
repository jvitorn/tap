<?php 
    namespace App\Controller;

    use App\Controller\Controller;

    use App\Model\Config;

    use App\DAO\ConfigDAO;

	/**
     * @method $this->render->json($dataArray);
     */
	class ControllerConfig extends Controller{

        public function list($data){
            $configs  = ConfigDAO::find(new Config($data));
            $configs  = $this->prepare_array($configs);
            $this->render->json($configs);
        }

        public function edit($data){

            unset($data['type']);
            $res = ConfigDAO::edit(new Config($data));

            if($res == "success"){
                
                $json['status'] = "success";
                $json['msg']    = "Configuração atualizada com sucesso. ";

            }else{

                $json['status'] = "error";
                $json['msg']    = "Não foi possivel editar a configuração. ";
            }

            $this->render->json($json);
        }
    }